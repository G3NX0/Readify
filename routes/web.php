<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminInfoController;

Route::get('/', function () {
    return auth()->check() ? view('home') : view('onboard');
})->name('onboard');

// Autentikasi bawaan (login/register/logout)
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');

// Khusus Admin (fitur manajerial)
Route::middleware(['auth', 'role:admin'])->group(function () {
  // CRUD Kategori — bikin, ubah, hapus (inti pengelompokan)
  Route::resource("categories", CategoryController::class)->except(["show"]);

  // CRUD Buku — tambah, edit, hapus (inti koleksi)
  Route::resource("books", BookController::class)->except(["show"]);
  Route::get("/books/live-search", [BookController::class, "liveSearch"])->name(
    "books.live-search",
  );

  // Manajemen Peminjaman — pantau & proses pengembalian
  Route::get("borrowings", [BorrowingController::class, "index"])->name("borrowings.index");
  Route::get("borrowings/create", [BorrowingController::class, "create"])->name(
    "borrowings.create",
  );
  Route::post("borrowings/{borrowing}/return", [BorrowingController::class, "returnBook"])->name(
    "borrowings.return",
  );
  // Analytics (admin)
  Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
  Route::get('analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');
  Route::get('analytics/summary', [AnalyticsController::class, 'summary'])->name('analytics.summary');

  // Informasi stack & fitur
  Route::get('admin/stack', [AdminInfoController::class, 'techStack'])->name('admin.stack');
});

// Area pengguna (harus login terlebih dahulu)
Route::middleware('auth')->group(function () {
    Route::get('catalog', [BookController::class, 'catalog'])->name('books.catalog');
    Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');
    // Portal Peminjaman (Get Started) — biar cepat pinjam
    Route::get('borrow', [BorrowingController::class, 'portal'])->name('borrow.portal');
    Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');

    // Pengembalian oleh pengguna (request)
    Route::get('returns', [BorrowingController::class, 'returnForm'])->name('returns.form');
    Route::post('returns', [BorrowingController::class, 'requestReturn'])->name('returns.request');

    // Profil pengguna
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Favorit
    Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('favorites/{book}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{book}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Avatar profil (opsional user ID)
Route::get('profile/photo/{user?}', [ProfileController::class, 'photo'])->name('profile.photo');
