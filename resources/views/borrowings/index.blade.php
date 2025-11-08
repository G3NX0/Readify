@extends('layouts.base', ['title' => 'Borrowings'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
      <a href="{{ url()->previous() ?: route('borrow.portal') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back</a>
      <h1 class="text-2xl font-semibold">Borrowings</h1>
    </div>
    <a href="{{ route('borrowings.create') }}" class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-white hover:bg-black">New Borrowing</a>
  </div>

  <div class="overflow-hidden rounded bg-white shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Book</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Borrower</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Borrowed</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Due</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Returned</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fine (Rp)</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @forelse($borrowings as $borrowing)
          <tr>
            <td class="px-4 py-2">{{ $borrowing->book->title ?? '-' }}</td>
            <td class="px-4 py-2">{{ $borrowing->borrower_name }}</td>
            <td class="px-4 py-2">{{ $borrowing->borrowed_at?->format('Y-m-d') }}</td>
            <td class="px-4 py-2">{{ $borrowing->due_date?->format('Y-m-d') }}</td>
            <td class="px-4 py-2">{{ $borrowing->returned_at?->format('Y-m-d') ?? '—' }}</td>
            <td class="px-4 py-2">
              @php $currentFine = $borrowing->returned_at ? $borrowing->fine_amount : $borrowing->calculateFine((int)config('borrowing.fine_per_day',10000)); @endphp
              {{ number_format($currentFine, 0, ',', '.') }}
            </td>
            <td class="px-4 py-2 text-right">
              @if(!$borrowing->returned_at)
                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                  @csrf
                  <button class="text-green-700 hover:underline" onclick="return confirm('Mark as returned? Fine will be calculated.')">Return</button>
                </form>
              @else
                <span class="text-gray-400">Completed</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td class="px-4 py-6 text-gray-500" colspan="7">No borrowings yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $borrowings->links() }}</div>
  <p class="text-sm text-gray-500 mt-2">Note: Denda keterlambatan Rp{{ number_format((int)config('borrowing.fine_per_day',10000),0,',','.') }} per hari.</p>
@endsection
