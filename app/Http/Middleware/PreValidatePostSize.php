<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk mencegah halaman blank saat ukuran upload melebihi post_max_size.
 * Jika Content-Length melebihi batas, redirect balik dengan pesan error.
 */
class PreValidatePostSize
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strtoupper($request->method()) === 'POST') {
            $contentLength = (int) ($request->server('CONTENT_LENGTH') ?? 0);
            $max = $this->toBytes((string) ini_get('post_max_size'));
            if ($max > 0 && $contentLength > $max) {
                $message = 'Ukuran unggahan terlalu besar. Maksimum ' . $this->human($max) . '.';
                return redirect()->back()->withErrors(['photo' => $message])->withInput();
            }
        }

        return $next($request);
    }

    private function toBytes(string $val): int
    {
        $val = trim($val);
        if ($val === '') return 0;
        $last = strtolower($val[strlen($val) - 1]);
        $num = (int) $val;
        return match ($last) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => (int) $val,
        };
    }

    private function human(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 1) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}

