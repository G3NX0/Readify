<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $books = $user->favoriteBooks()->with('category')->orderBy('title')->get();
        return view('favorites.index', compact('books'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();
        $user->favoriteBooks()->syncWithoutDetaching([$book->id]);
        return back()->with('status', 'Ditambahkan ke favorit.');
    }

    public function destroy(Request $request, Book $book): RedirectResponse
    {
        $request->user()->favoriteBooks()->detach($book->id);
        return back()->with('status', 'Dihapus dari favorit.');
    }
}

