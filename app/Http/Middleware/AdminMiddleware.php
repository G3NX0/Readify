<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware Admin
 *
 * Cek flag session 'is_admin'. Kalau belum, arahkan ke halaman login sambil
 * bawa URL tujuan (biar abis login langsung lanjut). Simpel tapi efektif.
 */
class AdminMiddleware
{
  /**
   * Jalankan pemeriksaan akses admin.
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!session("is_admin")) {
      $intended = $request->fullUrl();
      return redirect()->route("admin.login", ["intended" => $intended]);
    }

    return $next($request);
  }
}
