<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('home');
});

// Admin auth
Route::get('admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin-only
Route::middleware('admin')->group(function () {
    // Categories CRUD
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Books CRUD
    Route::resource('books', BookController::class)->except(['show']);

    // Borrowings management
    Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
});

// Public
Route::get('catalog', [BookController::class, 'catalog'])->name('books.catalog');
Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');
// Borrowing portal (Get Started)
Route::get('borrow', [BorrowingController::class, 'portal'])->name('borrow.portal');
Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
