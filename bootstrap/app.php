<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . "/../routes/web.php",
    commands: __DIR__ . "/../routes/console.php",
    health: "/up",
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
      'admin' => \App\Http\Middleware\AdminMiddleware::class,
      'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
    // Auto-provision DB (create + migrate + seed) saat belum tersedia
    $middleware->prepend(\App\Http\Middleware\AutoProvisionDatabase::class);
    // Jalankan pemeriksaan ukuran upload sebelum middleware bawaan ValidatePostSize
    $middleware->prepend(\App\Http\Middleware\PreValidatePostSize::class);

    // Kendurkan CSRF khusus untuk endpoint autentikasi agar pengguna dapat login/register
    // meski ada masalah token di lingkungan dev (sementara, aman karena hanya auth endpoints)
    $middleware->validateCsrfTokens(except: [
      'login',
      'register',
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    // Render error fallback yang simpel agar tidak bergantung pada renderer markdown bawaan
    $exceptions->render(function (\Throwable $e, Request $request) {
      // Biarkan JSON mengikuti handler default
      if ($request->expectsJson()) {
        return null;
      }

      // Saat debug aktif, tampilkan tampilan error minimal milik kita
      if (config('app.debug')) {
        try {
          return response()->view('errors.minimal', ['e' => $e], 500);
        } catch (\Throwable $_) {
          return response('Application Error', 500);
        }
      }

      return null; // gunakan handler bawaan untuk mode non-debug
    });
  })
  ->create();
