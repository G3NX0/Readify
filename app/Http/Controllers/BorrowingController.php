<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(): View
    {
        $borrowings = Borrowing::with('book')->orderByDesc('created_at')->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function portal(): View
    {
        $categories = Category::with('books')->orderBy('name')->get();
        $borrowings = Borrowing::with('book.category')->orderByDesc('created_at')->get();
        $unavailableBookIds = Borrowing::whereNull('returned_at')->pluck('book_id')->all();
        $categoryBooks = $categories->mapWithKeys(function ($c) {
            return [
                $c->id => $c->books->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'title' => $b->title,
                        'author' => $b->author,
                        'synopsis' => $b->synopsis,
                    ];
                })->values()->all(),
            ];
        })->toArray();
        return view('borrowings.portal', compact('categories', 'borrowings', 'categoryBooks', 'unavailableBookIds'));
    }

    public function create(): View
    {
        $books = Book::orderBy('title')->get();
        return view('borrowings.create', compact('books'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrowed_at' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:borrowed_at'],
        ]);

        // If the book is currently borrowed (no returned_at), prevent borrowing again
        $activeExists = Borrowing::where('book_id', $validated['book_id'])
            ->whereNull('returned_at')
            ->exists();
        if ($activeExists) {
            return back()->withErrors(['book_id' => 'Buku ini sedang dipinjam dan belum dikembalikan.'])->withInput();
        }

        Borrowing::create($validated + ['fine_amount' => 0]);
        if (session('is_admin')) {
            return redirect()->route('borrowings.index')->with('status', 'Borrowing recorded');
        }
        return redirect()->route('borrow.portal')->with('status', 'Berhasil meminjam buku.');
    }

    public function returnBook(Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->returned_at) {
            return redirect()->route('borrowings.index')->with('status', 'Already returned');
        }

        $borrowing->returned_at = Carbon::now()->toDateString();
        $borrowing->fine_amount = $borrowing->calculateFine((int)config('borrowing.fine_per_day', 10000));
        $borrowing->save();

        return redirect()->route('borrowings.index')->with('status', 'Book returned');
    }
}
