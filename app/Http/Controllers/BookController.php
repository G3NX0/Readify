<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kontroler Buku
 *
 * Ringkas tapi mantap: CRUD buku, detail, katalog publik, dan live search.
 */
class BookController extends Controller
{
    /**
     * Daftar buku (admin) dengan filter & pencarian.
     * Kalau AJAX, balikin partial tabel aja biar ringan.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $query = Book::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('author', 'like', "%{$q}%")
                  ->orWhere('synopsis', 'like', "%{$q}%");
            });
        }

        $books = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        // Kalau request-nya AJAX, kirim partial tabel aja
        if ($request->ajax()) {
            return view('books.partials.table', compact('books'))->render();
        }

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Form tambah buku.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    /**
     * Simpan buku baru (judul wajib, author opsional).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
        ]);
        Book::create($validated);
        return redirect()->route('books.index')->with('status', 'Book created');
    }

    /**
     * Form edit buku.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update buku.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
        ]);
        $book->update($validated);
        return redirect()->route('books.index')->with('status', 'Book updated');
    }

    /**
     * Detail buku untuk publik (cek ketersediaan juga).
     */
    public function show(Book $book): View
    {
        $book->load('category');
        $isUnavailable = \App\Models\Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();
        return view('books.show', compact('book', 'isUnavailable'));
    }

    /**
     * Katalog publik — dikelompokkan per kategori, bisa difilter/di‑search.
     */
    public function catalog(Request $request): View
    {
        $filterCategories = Category::orderBy('name')->get();
        $q = trim((string) $request->get('q', ''));
        $categoryId = $request->get('category_id');

        $sectionsQuery = Category::query();
        if ($categoryId) {
            $sectionsQuery->where('id', (int) $categoryId);
        }

        // Sertakan hanya kategori yang punya buku sesuai filter
        $sectionsQuery->whereHas('books', function ($sub) use ($q) {
            if ($q !== '') {
                $sub->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('author', 'like', "%{$q}%")
                      ->orWhere('synopsis', 'like', "%{$q}%");
                });
            }
        });

        $sections = $sectionsQuery
            ->with(['books' => function ($bookQ) use ($q) {
                if ($q !== '') {
                    $bookQ->where(function ($w) use ($q) {
                        $w->where('title', 'like', "%{$q}%")
                          ->orWhere('author', 'like', "%{$q}%")
                          ->orWhere('synopsis', 'like', "%{$q}%");
                    });
                }
                $bookQ->orderBy('title');
            }])
            ->orderBy('name')
            ->get();

        $unavailableBookIds = \App\Models\Borrowing::whereNull('returned_at')->pluck('book_id')->all();

        if ($request->ajax()) {
            return view('books.partials.catalog_sections', [
                'sections' => $sections,
                'unavailableBookIds' => $unavailableBookIds,
            ]);
        }

        return view('books.catalog', [
            'filterCategories' => $filterCategories,
            'sections' => $sections,
            'unavailableBookIds' => $unavailableBookIds,
        ]);
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();
        return redirect()->route('books.index')->with('status', 'Book deleted');
    }

    /**
     * Endpoint JSON untuk live search (admin area).
     */
    public function liveSearch(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                ->orWhere('author', 'like', "%{$q}%")
                ->orWhere('synopsis', 'like', "%{$q}%");
            });
        }

        $books = $query->orderByDesc('created_at')->limit(10)->get();

        return response()->json($books);
    }
}
