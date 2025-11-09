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
    $validated = $request->validate([
      "book_id" => ["required", "exists:books,id"],
      "borrower_name" => ["required", "string", "max:255"],
      "borrowed_at" => ["required", "date"],
      "due_date" => ["required", "date", "after_or_equal:borrowed_at"],
    ]);

    // If the book is currently borrowed (no returned_at), prevent borrowing again
    $activeExists = Borrowing::where("book_id", $validated["book_id"])
      ->whereNull("returned_at")
      ->exists();
    if ($activeExists) {
      return back()
        ->withErrors(["book_id" => "Buku ini sedang dipinjam dan belum dikembalikan."])
        ->withInput();
    }

    Borrowing::create($validated + ["fine_amount" => 0]);
    if (session("is_admin")) {
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
