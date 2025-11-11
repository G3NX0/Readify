<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminInfoController extends Controller
{
    public function techStack(): View
    {
        $languages = [
            [
                'title' => 'PHP 8.x (Laravel 11)',
                'description' => 'Kerangka utama untuk routing, middleware, artisan command, dan struktur MVC. Laravel jadi tulang punggung—tinggal atur route, middleware, artisan, beres.',
            ],
            [
                'title' => 'Blade Template + Utility CSS',
                'description' => 'Lapisan presentasi custom, memanfaatkan Blade + utility ala Tailwind untuk glass/gradient. UI bisa diganti tone tanpa build step rumit.',
            ],
            [
                'title' => 'JavaScript (ES6) + Chart.js',
                'description' => 'Interaktivitas: live search, splash logic, dropdown return, analytics chart interaktif yang bisa ganti tipe.',
            ],
            [
                'title' => 'MySQL',
                'description' => 'Menampung users, books, categories, borrowings, favorites, dsb. Relasi dirapikan supaya query efisien.',
            ],
            [
                'title' => 'HTML5/CSS3',
                'description' => 'Struktur semantik + efek glassmorphism/gradient sehingga dashboard terasa premium tanpa framework UI berat.',
            ],
        ];

        $features = [
            [
                'title' => 'Autentikasi & Role-Based Access',
                'description' => 'Login/register custom, onboarding, guard admin vs user, navbar dinamis. Admin dapat menu ekstra (Books, Analytics, Stack), user biasa melihat item relevan saja.',
                'details' => [
                    'Custom controller, middleware role:admin, onboarding gating sebelum home.',
                    'Navbar adaptif: ikon favorit, link return, link admin muncul sesuai role.',
                ],
            ],
            [
                'title' => 'Catalog & Favorit',
                'description' => 'Live search AJAX, filter kategori, status ketersediaan, ikon love untuk favorit yang masuk ke halaman khusus.',
                'details' => [
                    'Favorit Many-to-Many user↔book dengan grid responsif.',
                    'Section partial memberikan feedback cepat tanpa reload.',
                ],
            ],
            [
                'title' => 'Borrow & Return Workflow',
                'description' => 'Portal peminjaman (auto borrower name), user dapat ajukan pengembalian, admin konfirmasi, timestamp pakai timezone lokal.',
                'details' => [
                    'Validasi ketersediaan buku, pencatatan user_id/borrower_name.',
                    'Return form hanya menampilkan pinjaman aktif milik user.',
                    'Admin melihat badge “return requested” + tombol konfirmasi.',
                ],
            ],
            [
                'title' => 'Profil, Avatar, & Riwayat',
                'description' => 'Edit profil lengkap, upload foto (preview & fallback), riwayat peminjaman privat dengan status borrowed/requested/returned.',
                'details' => [
                    'Upload foto tanpa symlink, otomatis salin ke storage/public + public/uploads.',
                    'Riwayat user memanfaatkan user_id atau nama agar catatan lama tetap terlihat.',
                ],
            ],
            [
                'title' => 'Analytics Dashboard',
                'description' => 'Chart interaktif (books/categories/weekday) dengan filter metric/limit/period/bentuk + quick stats responsif.',
                'details' => [
                    'Chart.js mendukung bar/line/pie/doughnut + palet warna otomatis.',
                    'Summary card (total, top book/category, hari tersibuk) menyesuaikan rentang.',
                ],
            ],
            [
                'title' => 'UX Enhancements',
                'description' => 'Splash screen READIFY, dropdown dark theme, grid horizontal, ikon favorit di navbar, responsif mobile.',
                'details' => [
                    'Splash sekali-per-sesi, durasi 2s, animasi orb + beam, menghormati prefers-reduced-motion.',
                    'Grid horizontal memanfaatkan flex/auto-fit agar hemat ruang di layar lebar dan tetap nyaman di mobile.',
                ],
            ],
        ];

        return view('admin.stack', compact('languages', 'features'));
    }
}
