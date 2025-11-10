<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

// Buat database sesuai env (jika belum ada)
Artisan::command('db:create', function () {
    $cfg = Config::get('database.connections.mysql');
    $db = (string) ($cfg['database'] ?? '');
    $host = (string) ($cfg['host'] ?? '127.0.0.1');
    $port = (string) ($cfg['port'] ?? '3306');
    $user = (string) ($cfg['username'] ?? 'root');
    $pass = (string) ($cfg['password'] ?? '');

    if ($db === '') {
        $this->error('DATABASE name kosong. Cek .env (DB_DATABASE).');
        return 1;
    }

    try {
        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->info("Database '{$db}' siap.");
        return 0;
    } catch (\Throwable $e) {
        $this->error('Gagal membuat database: ' . $e->getMessage());
        return 1;
    }
})->purpose('Membuat database sesuai DB_DATABASE jika belum ada');

// Sekaligus create DB + migrate + seed admin
Artisan::command('db:setup', function () {
    $this->call('db:create');
    $this->call('migrate', ['--force' => true]);
    $this->call('db:seed', [
        '--class' => 'Database\\Seeders\\AdminUserSeeder',
        '--force' => true,
    ]);
    $this->info('Setup selesai. Admin: admin@gmail.com / admin');
})->purpose('Create DB, migrate, dan seed admin');

Artisan::command("inspire", function () {
  $this->comment(Inspiring::quote());
})->purpose("Display an inspiring quote");
