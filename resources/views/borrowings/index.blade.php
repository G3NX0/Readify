@extends('layouts.base', ['title' => 'Borrowings'])

@section('content')
  <style>
    .glass-container {
      background: rgba(20, 20, 20, 0.55);
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    table.glass-table {
      width: 100%;
      border-collapse: collapse;
      color: #f9fafb;
    }

    table.glass-table thead th {
      padding: 12px;
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.05em;
      color: rgba(255,255,255,0.7);
      text-align: left;
    }

    table.glass-table tbody td {
      padding: 14px 12px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.9);
    }

    table.glass-table tbody td.fine {
      font-weight: 600;
    }

    table.glass-table tbody td.returned {
      color: rgba(255,255,255,0.6);
    }

    table.glass-table tbody tr:hover {
      background: rgba(255,255,255,0.04);
      transition: all 0.2s ease;
    }

    .btn-dark {
      background: rgba(255,255,255,0.08);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.12);
      transition: all 0.2s ease;
    }

    .btn-dark:hover {
      background: rgba(255,255,255,0.2);
      border-color: rgba(255,255,255,0.25);
    }

    .btn-success {
      color: #22c55e;
      border-color: rgba(34,197,94,0.4);
    }

    .btn-success:hover {
      background: rgba(34,197,94,0.15);
    }

    .btn-completed {
      color: rgba(255,255,255,0.6);
      border-color: rgba(255,255,255,0.2);
      background: rgba(255,255,255,0.05);
    }
  </style>

  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
      <a href="{{ url()->previous() ?: route('borrow.portal') }}"
        class="inline-flex items-center gap-1 text-white/70 hover:text-white transition">
        <i class="bi bi-arrow-left"></i> Back
      </a>
      <h1 class="text-2xl font-semibold text-white">Borrowings</h1>
    </div>

    <a href="{{ route('borrowings.create') }}"
      class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition">
      <i class="bi bi-plus-lg mr-1"></i> New Borrowing
    </a>
  </div>

  <div class="overflow-hidden rounded-xl glass-container">
    <table class="glass-table">
      <thead>
        <tr>
          <th>Book</th>
          <th>Borrower</th>
          <th>Borrowed</th>
          <th>Due</th>
          <th>Returned</th>
          <th>Fine (Rp)</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($borrowings as $borrowing)
          <tr class="transition hover:bg-white/5">
            <td class="font-medium text-white/90">{{ $borrowing->book->title ?? '-' }}</td>
            <td class="text-white/90">{{ $borrowing->borrower_name }}</td>
            <td class="text-white/90">{{ $borrowing->borrowed_at?->format('Y-m-d') }}</td>
            <td class="text-white/90">{{ $borrowing->due_date?->format('Y-m-d') }}</td>
            <td class="returned text-white/70">{{ $borrowing->returned_at?->format('Y-m-d') ?? '—' }}</td>
            <td class="fine text-white/90">
              @php
                $currentFine = $borrowing->returned_at
                    ? $borrowing->fine_amount
                    : $borrowing->calculateFine((int) config('borrowing.fine_per_day', 10000));
              @endphp
              {{ number_format($currentFine, 0, ',', '.') }}
            </td>
            <td class="text-right">
              <div class="flex justify-end gap-2">
                @if(!$borrowing->returned_at)
                  <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Mark as returned? Fine will be calculated.')">
                    @csrf
                    <button type="submit" class="btn-dark btn-success text-sm px-3 py-1.5 rounded-md border">
                      <i class="bi bi-check2-circle"></i> Return
                    </button>
                  </form>
                @else
                  <span class="btn-completed inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm border">
                    <i class="bi bi-check-all"></i> Completed
                  </span>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-6 text-gray-400">No borrowings found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $borrowings->links() }}
    </div>
  </div>

  <p class="text-sm text-white/70 mt-2">
    Note: Denda keterlambatan Rp{{ number_format((int)config('borrowing.fine_per_day',10000),0,',','.') }} per hari.
  </p>
@endsection