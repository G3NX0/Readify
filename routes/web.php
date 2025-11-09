<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AdminController;

Route::get("/", function () {
  return view("home");
});

// Autentikasi Admin (santai tapi tetap rapi)
Route::get("admin/login", [AdminController::class, "showLogin"])->name("admin.login");
Route::post("admin/login", [AdminController::class, "login"])
  ->middleware("throttle:5,1")
  ->name("admin.login.post");
Route::post("admin/logout", [AdminController::class, "logout"])->name("admin.logout");

// Khusus Admin (fitur manajerial)
Route::middleware("admin")->group(function () {
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
});

// Publik (semua orang bisa mengakses)
Route::get("catalog", [BookController::class, "catalog"])->name("books.catalog");
Route::get("books/{book}", [BookController::class, "show"])->name("books.show");
// Portal Peminjaman (Get Started) — biar cepat pinjam
Route::get("borrow", [BorrowingController::class, "portal"])->name("borrow.portal");
Route::post("borrowings", [BorrowingController::class, "store"])->name("borrowings.store");
