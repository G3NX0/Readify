<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        return view('analytics.index');
    }

    public function data(Request $request)
    {
        $metric = (string) $request->get('metric', 'books');
        $limit = (int) $request->get('limit', 10);
        $period = (string) $request->get('period', '30d');

        // Hitung tanggal awal berdasarkan periode
        $now = Carbon::now();
        switch ($period) {
            case '7d':
                $start = $now->copy()->subDays(7);
                break;
            case '12m':
                $start = $now->copy()->subMonths(12);
                break;
            case '30d':
            default:
                $start = $now->copy()->subDays(30);
                break;
        }

        switch ($metric) {
            case 'books':
                $rows = DB::table('borrowings as b')
                    ->join('books as k', 'k.id', '=', 'b.book_id')
                    ->select('k.title as label', DB::raw('COUNT(*) as total'))
                    ->whereRaw('COALESCE(b.borrowed_at, b.created_at) >= ?', [$start])
                    ->groupBy('k.title')
                    ->orderByDesc(DB::raw('COUNT(*)'))
                    ->limit($limit)
                    ->get();
                break;

            case 'categories':
                $rows = DB::table('borrowings as b')
                    ->join('books as k', 'k.id', '=', 'b.book_id')
                    ->join('categories as c', 'c.id', '=', 'k.category_id')
                    ->select('c.name as label', DB::raw('COUNT(*) as total'))
                    ->whereRaw('COALESCE(b.borrowed_at, b.created_at) >= ?', [$start])
                    ->groupBy('c.name')
                    ->orderByDesc(DB::raw('COUNT(*)'))
                    ->limit($limit)
                    ->get();
                break;

            case 'weekday':
                // Gunakan borrowed_at jika ada; fallback created_at
                $rows = Borrowing::query()
                    ->selectRaw("COALESCE(DAYOFWEEK(borrowed_at), DAYOFWEEK(created_at)) as dw, COUNT(*) as total")
                    ->whereRaw('COALESCE(borrowed_at, created_at) >= ?', [$start])
                    ->groupBy('dw')
                    ->orderBy('dw')
                    ->get()
                    ->map(function ($r) {
                        $map = [1 => 'Minggu', 2 => 'Senin', 3 => 'Selasa', 4 => 'Rabu', 5 => 'Kamis', 6 => 'Jumat', 7 => 'Sabtu'];
                        return (object) [
                            'label' => $map[(int) $r->dw] ?? (string) $r->dw,
                            'total' => (int) $r->total,
                        ];
                    });
                break;

            default:
                return response()->json(['error' => 'Unknown metric'], 400);
        }

        return response()->json([
            'labels' => $rows->pluck('label'),
            'values' => $rows->pluck('total')->map(fn($t) => (int) $t),
        ]);
    }

    public function summary(Request $request)
    {
        $period = (string) $request->get('period', '30d');
        $now = Carbon::now();
        switch ($period) {
            case '7d':
                $start = $now->copy()->subDays(7);
                break;
            case '12m':
                $start = $now->copy()->subMonths(12);
                break;
            case '30d':
            default:
                $start = $now->copy()->subDays(30);
                break;
        }

        // Total borrowings
        $total = Borrowing::whereRaw('COALESCE(borrowed_at, created_at) >= ?', [$start])->count();

        // Top book
        $topBook = \DB::table('borrowings as b')
            ->join('books as k', 'k.id', '=', 'b.book_id')
            ->select('k.title as label', \DB::raw('COUNT(*) as total'))
            ->whereRaw('COALESCE(b.borrowed_at, b.created_at) >= ?', [$start])
            ->groupBy('k.title')
            ->orderByDesc(\DB::raw('COUNT(*)'))
            ->limit(1)
            ->first();

        // Top category
        $topCategory = \DB::table('borrowings as b')
            ->join('books as k', 'k.id', '=', 'b.book_id')
            ->join('categories as c', 'c.id', '=', 'k.category_id')
            ->select('c.name as label', \DB::raw('COUNT(*) as total'))
            ->whereRaw('COALESCE(b.borrowed_at, b.created_at) >= ?', [$start])
            ->groupBy('c.name')
            ->orderByDesc(\DB::raw('COUNT(*)'))
            ->limit(1)
            ->first();

        // Busiest weekday
        $weekdayRow = Borrowing::query()
            ->selectRaw('COALESCE(DAYOFWEEK(borrowed_at), DAYOFWEEK(created_at)) as dw, COUNT(*) as total')
            ->whereRaw('COALESCE(borrowed_at, created_at) >= ?', [$start])
            ->groupBy('dw')
            ->orderByDesc('total')
            ->limit(1)
            ->first();
        $weekdayMap = [1 => 'Minggu', 2 => 'Senin', 3 => 'Selasa', 4 => 'Rabu', 5 => 'Kamis', 6 => 'Jumat', 7 => 'Sabtu'];
        $topWeekday = $weekdayRow ? [
            'label' => $weekdayMap[(int) $weekdayRow->dw] ?? (string) $weekdayRow->dw,
            'total' => (int) $weekdayRow->total,
        ] : null;

        return response()->json([
            'totalBorrowings' => (int) $total,
            'topBook' => $topBook ? ['label' => $topBook->label, 'total' => (int) $topBook->total] : null,
            'topCategory' => $topCategory ? ['label' => $topCategory->label, 'total' => (int) $topCategory->total] : null,
            'topWeekday' => $topWeekday,
        ]);
    }
}
