@extends("layouts.base", ["title" => "Borrowings"])

@section("content")
  <style>
    .table-card {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      overflow: hidden;
    }

    .table-min {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    .table-min thead th {
      background: rgba(255, 255, 255, 0.04);
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-weight: 600;
      padding: 12px 16px;
    }

    .table-min tbody tr {
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .table-min tbody td {
      padding: 14px 16px;
      color: rgba(255, 255, 255, 0.92);
      vertical-align: top;
    }

    .table-min tbody tr:hover {
      background: rgba(255, 255, 255, 0.035);
    }

    .muted {
      color: rgba(255, 255, 255, 0.75);
    }

    .icon-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: #fff;
      transition: background 0.2s;
    }

    .icon-btn:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .icon-btn-success {
      border-color: rgba(16, 185, 129, 0.5);
      color: #a7f3d0;
    }

    .icon-btn-success:hover {
      background: rgba(16, 185, 129, 0.08);
    }
  </style>

  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
      <a
        href="{{ url()->previous() ?: route("borrow.portal") }}"
        class="inline-flex items-center gap-1 text-white/70 hover:text-white transition"
      >
        <i class="bi bi-arrow-left"></i>
        Back
      </a>
      <h1 class="text-2xl font-semibold text-white">Borrowings</h1>
    </div>

    <div class="flex items-center gap-3">
      <form method="GET">
        <label for="status" class="sr-only">Status</label>
        <select id="status" name="status" class="select-dark" onchange="this.form.submit()">
          <option value="" @selected(($status ?? null) === null || $status === "")>All</option>
          <option value="active" @selected(($status ?? null) === "active")>Active</option>
          <option value="returned" @selected(($status ?? null) === "returned")>Returned</option>
        </select>
      </form>
      <a
        href="{{ route("borrowings.create") }}"
        class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition"
      >
        <i class="bi bi-plus-lg mr-1"></i>
        New Borrowing
      </a>
    </div>
  </div>

  <div class="table-card">
    <table class="table-min">
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
        @forelse ($borrowings as $borrowing)
          <tr>
            <td class="font-semibold">{{ $borrowing->book->title ?? "-" }}</td>
            <td class="muted">{{ $borrowing->borrower_name }}</td>
            <td class="muted">{{ $borrowing->borrowed_at?->format("Y-m-d") }}</td>
            <td class="muted">{{ $borrowing->due_date?->format("Y-m-d") }}</td>
            <td class="muted">{{ $borrowing->returned_at?->format("Y-m-d") ?? "—" }}</td>
            <td class="muted">
              @php
                $currentFine = $borrowing->returned_at
                  ? $borrowing->fine_amount
                  : $borrowing->calculateFine((int) config("borrowing.fine_per_day", 10000));
              @endphp

              {{ number_format($currentFine, 0, ",", ".") }}
            </td>
            <td class="text-right whitespace-nowrap flex gap-2 justify-end">
              @if (! $borrowing->returned_at)
                <form
                  action="{{ route("borrowings.return", $borrowing) }}"
                  method="POST"
                  class="inline"
                >
                  @csrf
                  <button
                    type="submit"
                    class="icon-btn icon-btn-success"
                    title="Mark as returned"
                    onclick="return confirm('Mark as returned? Fine will be calculated.')"
                  >
                    <i class="bi bi-check2-circle"></i>
                  </button>
                </form>
              @else
                <span class="icon-btn muted" title="Completed">
                  <i class="bi bi-check-all"></i>
                </span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td class="px-4 py-6 text-gray-300" colspan="7">No borrowings yet.</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $borrowings->links() }}
    </div>
  </div>

  <p class="text-sm text-white/70 mt-2">
    Note: Denda keterlambatan
    Rp{{ number_format((int) config("borrowing.fine_per_day", 10000), 0, ",", ".") }} per hari.
  </p>
@endsection
