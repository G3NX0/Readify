<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

/**
 * Middleware Role (admin / user)
 *
 * Pakai: ->middleware(['auth','role:admin']) atau ['auth','role:user']
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')->with('status', 'Silakan login sebagai ' . $role . '.');
        }

        $hasRole = Schema::hasColumn('users', 'role');
        $isAdminByIdentity = strcasecmp((string) ($user->email ?? ''), 'admin@gmail.com') === 0
            || strcasecmp((string) ($user->username ?? ''), 'admin') === 0;

        if ($role === 'admin') {
            // Jika ada kolom role dan sudah benar, lolos.
            if ($hasRole && (($user->role ?? 'user') === 'admin')) {
                return $next($request);
            }
            // Tetap izinkan admin berdasarkan identitas email/username walau kolom role ada namun belum terset.
            if ($isAdminByIdentity) {
                return $next($request);
            }
            return redirect()->route('login')->with('status', 'Silakan login sebagai ' . $role . '.');
        }

        // Untuk role lain, jika kolom tersedia maka patuhi nilainya; jika tidak, izinkan saja selama login.
        if ($hasRole) {
            if (($user->role ?? 'user') !== $role) {
                return redirect()->route('login')->with('status', 'Silakan login sebagai ' . $role . '.');
            }
        }
        return $next($request);
    }
}
