<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kontroler Kategori
 *
 * Mode campur: santai + formal. Intinya CRUD kategori buat rapihin koleksi.
 */
class CategoryController extends Controller
{
    /**
     * Daftar kategori dengan pagination.
     */
    public function index(): View
    {
        $categories = Category::orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Form bikin kategori baru.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru (nama unik).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('status', 'Category created');
    }

    /**
     * Form edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update kategori (nama tetap unik, tapi skip id sendiri).
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('status', 'Category updated');
    }

    /**
     * Hapus kategori. Catatan: relasi ke buku diatur oleh constraint DB.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('categories.index')->with('status', 'Category deleted');
    }
}
