<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
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
        $books = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('books.index', compact('books', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

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

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

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

    public function show(Book $book): View
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    public function catalog(Request $request): View
    {
        $filterCategories = Category::orderBy('name')->get();
        $q = trim((string) $request->get('q', ''));
        $categoryId = $request->get('category_id');

        $sectionsQuery = Category::query();
        if ($categoryId) {
            $sectionsQuery->where('id', (int) $categoryId);
        }

        // Only include categories that have matching books
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

        return view('books.catalog', [
            'filterCategories' => $filterCategories,
            'sections' => $sections,
            'unavailableBookIds' => $unavailableBookIds,
        ]);
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();
        return redirect()->route('books.index')->with('status', 'Book deleted');
    }
}
