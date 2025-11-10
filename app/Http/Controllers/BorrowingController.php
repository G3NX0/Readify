<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kontroler Peminjaman
 *
 * Tugasnya: data peminjaman (admin), portal publik, simpan/return.
 */
class BorrowingController extends Controller
{
  /**
   * Daftar peminjaman untuk admin (bisa difilter status: active/returned).
   */
  public function index(Request $request): View
  {
    $status = $request->get("status");
    $query = Borrowing::with("book")->orderByDesc("created_at");

    if ($status === "active") {
      $query->whereNull("returned_at");
    } elseif ($status === "returned") {
      $query->whereNotNull("returned_at");
    }

    $borrowings = $query->paginate(10)->withQueryString();
    return view("borrowings.index", compact("borrowings", "status"));
  }

  /**
   * Portal publik: form pinjam + daftar peminjaman aktif (belum dikembalikan).
   */
  public function portal(): View
  {
    $categories = Category::with("books")->orderBy("name")->get();
    // Tampilkan hanya peminjaman yang masih aktif (belum dikembalikan) di portal publik
    $borrowings = Borrowing::with("book.category")
      ->whereNull("returned_at")
      ->orderByDesc("created_at")
      ->get();
    $unavailableBookIds = Borrowing::whereNull("returned_at")->pluck("book_id")->all();
    $categoryBooks = $categories
      ->mapWithKeys(function ($c) {
        return [
          $c->id => $c->books
            ->map(function ($b) {
              return [
                "id" => $b->id,
                "title" => $b->title,
                "author" => $b->author,
                "synopsis" => $b->synopsis,
              ];
            })
            ->values()
            ->all(),
        ];
      })
      ->toArray();
    return view(
      "borrowings.portal",
      compact("categories", "borrowings", "categoryBooks", "unavailableBookIds"),
    );
  }

  /**
   * Form pengembalian untuk pengguna: pilih dari peminjaman aktif miliknya.
   */
  public function returnForm(Request $request): View
  {
    $user = $request->user();
    $activeMine = Borrowing::with('book')
      ->whereNull('returned_at')
      ->when($user, function ($q) use ($user) {
        $q->where(function ($sub) use ($user) {
          $sub->where('user_id', $user->id)
              ->orWhere('borrower_name', (string) $user->name);
        });
      })
      ->orderByDesc('created_at')
      ->get();
    // Jika admin, tampilkan daftar permintaan pengembalian yang menunggu konfirmasi
    $isAdmin = $user && ((($user->role ?? null) === 'admin') || strcasecmp((string) ($user->email ?? ''), 'admin@gmail.com') === 0);
    $pendingReturns = collect();
    if ($isAdmin) {
      $pendingReturns = Borrowing::with('book', 'user')
        ->whereNotNull('return_requested_at')
        ->whereNull('returned_at')
        ->orderByDesc('return_requested_at')
        ->limit(50)
        ->get();
    }
    return view('borrowings.return', compact('activeMine', 'pendingReturns', 'isAdmin'));
  }

  /**
   * Ajukan permintaan pengembalian; admin yang mengonfirmasi.
   */
  public function requestReturn(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'borrowing_id' => ['required', 'exists:borrowings,id'],
      'return_note' => ['nullable', 'string', 'max:255'],
    ]);

    $borrowing = Borrowing::findOrFail($validated['borrowing_id']);
    $user = $request->user();
    // Validasi kepemilikan: user_id cocok ATAU borrower_name sama (untuk data lama)
    $owned = false;
    if ($user) {
      if ($borrowing->user_id) {
        $owned = (int) $borrowing->user_id === (int) $user->id;
      } else {
        $owned = strcasecmp((string) ($borrowing->borrower_name ?? ''), (string) ($user->name ?? '')) === 0;
      }
    }
    if (! $owned) {
      return back()->withErrors(['borrowing_id' => 'Tidak valid: peminjaman bukan milik Anda.']);
    }
    if ($borrowing->returned_at) {
      return back()->withErrors(['borrowing_id' => 'Catatan ini sudah dikembalikan.']);
    }
    $borrowing->return_requested_at = now();
    $borrowing->return_note = $validated['return_note'] ?? null;
    $borrowing->save();

    return redirect()->route('returns.form')->with('status', 'Permintaan pengembalian dikirim.');
  }

  /**
   * Form peminjaman (admin membuat catatan).
   */
  public function create(): View
  {
    $books = Book::orderBy("title")->get();
    return view("borrowings.create", compact("books"));
  }

  /**
   * Simpan peminjaman baru. Cek kalau buku masih aktif dipinjam.
   */
  public function store(Request $request): RedirectResponse
  {
    $isAdmin = auth()->check() && (((auth()->user()->role ?? null) === 'admin') || strcasecmp((string) (auth()->user()->email ?? ''), 'admin@gmail.com') === 0);

    $rules = [
      "book_id" => ["required", "exists:books,id"],
      "borrowed_at" => ["required", "date"],
      "due_date" => ["required", "date", "after_or_equal:borrowed_at"],
    ];
    if ($isAdmin) {
      $rules["borrower_name"] = ["required", "string", "max:255"];
    }

    $validated = $request->validate($rules);

    // If the book is currently borrowed (no returned_at), prevent borrowing again
    $activeExists = Borrowing::where("book_id", $validated["book_id"])
      ->whereNull("returned_at")
      ->exists();
    if ($activeExists) {
      return back()
        ->withErrors(["book_id" => "Buku ini sedang dipinjam dan belum dikembalikan."])
        ->withInput();
    }

    $borrowerName = $isAdmin && $request->filled('borrower_name') ? (string) $request->string('borrower_name') : (string) (auth()->user()->name ?? 'Pengguna');

    Borrowing::create([
      "book_id" => $validated["book_id"],
      "borrower_name" => $borrowerName,
      "borrowed_at" => $validated["borrowed_at"],
      "due_date" => $validated["due_date"],
      "user_id" => auth()->id(),
      "fine_amount" => 0,
    ]);
    if (auth()->check() && (auth()->user()->role ?? 'user') === 'admin') {
      return redirect()->route("borrowings.index")->with("status", "Borrowing recorded");
    }
    return redirect()->route("borrow.portal")->with("status", "Berhasil meminjam buku.");
  }

  /**
   * Tandai buku sudah kembali dan hitung denda (kalau telat).
   */
  public function returnBook(Borrowing $borrowing): RedirectResponse
  {
    if ($borrowing->returned_at) {
      return redirect()->route("borrowings.index")->with("status", "Already returned");
    }

    $borrowing->returned_at = Carbon::now()->toDateString();
    $borrowing->fine_amount = $borrowing->calculateFine(
      (int) config("borrowing.fine_per_day", 10000),
    );
    $borrowing->save();

    return redirect()->route("borrowings.index")->with("status", "Book returned");
  }
}
