<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('is_admin')) {
            $intended = $request->fullUrl();
            return redirect()->route('admin.login', ['intended' => $intended]);
        }

        return $next($request);
    }
}

