<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * AutoProvisionDatabase
 *
 * Tujuan: jika database default (MySQL/MariaDB) belum ada, buat otomatis,
 * jalankan migrasi dan seeder sehingga aplikasi siap dipakai tanpa CLI.
 *
 * Aman dijalankan berkala; operasi CREATE DATABASE bersifat idempotent.
 */
class AutoProvisionDatabase
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jalankan hanya untuk koneksi mysql/mariadb
        $default = (string) Config::get('database.default', 'mysql');
        if (!in_array($default, ['mysql', 'mariadb'], true)) {
            return $next($request);
        }

        $cfg = Config::get("database.connections.{$default}");
        $db = (string) ($cfg['database'] ?? '');
        $host = (string) ($cfg['host'] ?? '127.0.0.1');
        $port = (string) ($cfg['port'] ?? '3306');
        $user = (string) ($cfg['username'] ?? 'root');
        $pass = (string) ($cfg['password'] ?? '');

        if ($db === '') {
            return $next($request);
        }

        $lock = storage_path('framework/db_autoprovision.lock');

        $needsCreate = false;
        try {
            // Coba connect langsung ke DB; jika gagal karena tidak ada, tandai untuk dibuat
            $dsnDb = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            new \PDO($dsnDb, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\Throwable $e) {
            // Unknown database 1049
            $needsCreate = true;
        }

        if ($needsCreate) {
            try {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                // Hilangkan lock agar migrasi/seeding berjalan di langkah bawah
                if (file_exists($lock)) @unlink($lock);
            } catch (\Throwable $_) {
                // Jika gagal membuat DB, lanjutkan request normal; error akan dirender oleh handler
                return $next($request);
            }
        }

        // Migrasi setiap request (ringan jika tidak ada migrasi baru); seeding hanya sekali
        $shouldSeed = !file_exists($lock);
        try {
            Artisan::call('migrate', ['--force' => true]);
            if ($shouldSeed || !Schema::hasTable('users')) {
                Artisan::call('db:seed', [
                    '--class' => 'Database\Seeders\DatabaseSeeder',
                    '--force' => true,
                ]);
                @file_put_contents($lock, (string) now());
            }
        } catch (\Throwable $_) {
            // Diamkan, biar request tetap lanjut; error akan tertangkap saat akses DB berikutnya
        }

        return $next($request);
    }
}

