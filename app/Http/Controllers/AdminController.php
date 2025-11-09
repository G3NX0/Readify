<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kontroler Admin
 *
 * Catatan singkat (semi formal):
 * - Menangani login/logout admin sederhana berbasis password .env
 * - Santai tapi tetap aman; jangan simpan password di kode ya.
 */
class AdminController extends Controller
{
  /**
   * Tampilkan form login admin.
   * Hint: bawa juga URL tujuan (intended) biar abis login langsung nyasar ke halaman yang benar.
   */
  public function showLogin(Request $request): View
  {
    $intended = $request->query("intended");
    return view("admin.login", compact("intended"));
  }

  /**
   * Proses login admin.
   * Validasi simple, cocok buat internal. Kalau mau serius, pindah ke guard Laravel.
   */
  public function login(Request $request): RedirectResponse
  {
    $request->validate(["password" => ["required", "string"]]);
    $password = (string) config("admin.password", "secret");
    if (hash_equals($password, (string) $request->string("password"))) {
      $request->session()->regenerate();
      $request->session()->put("is_admin", true);
      $target = $request->string("intended") ?: route("borrowings.index");
      return redirect()->to((string) $target);
    }
    return back()
      ->withErrors(["password" => "Invalid admin password"])
      ->withInput();
  }

  /**
   * Keluar dari sesi admin. Simpel dan efektif.
   */
  public function logout(Request $request): RedirectResponse
  {
    $request->session()->forget("is_admin");
    return redirect("/");
  }
}
