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
        if ($hasRole) {
            if (($user->role ?? 'user') !== $role) {
                return redirect()->route('login')->with('status', 'Silakan login sebagai ' . $role . '.');
            }
            return $next($request);
        }

        // Fallback jika kolom 'role' belum ada di DB:
        // anggap admin jika email adalah admin@gmail.com
        if ($role === 'admin') {
            $isAdmin = strcasecmp((string) ($user->email ?? ''), 'admin@gmail.com') === 0
                || strcasecmp((string) ($user->username ?? ''), 'admin') === 0;
            if ($isAdmin) {
                return $next($request);
            }
            return redirect()->route('login')->with('status', 'Silakan login sebagai ' . $role . '.');
        }
        // Untuk role lain, izinkan saja jika login
        return $next($request);
    }
}
