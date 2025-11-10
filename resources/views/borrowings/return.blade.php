@extends('layouts.base', ['title' => 'Pengembalian'])

@section('content')
  <style>
    .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
    .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color:#fff; color-scheme:dark; }
    .input-field:focus { outline:none; border-color: rgba(255,255,255,0.4); }
    /* Samakan tone dropdown seperti halaman Borrow */
    .select-dark { color-scheme: dark; }
    .select-dark option { background: #0b0b0b; color: #fff; }
  </style>

  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-semibold text-white mb-4">Pengembalian Buku</h1>

    @if(session('status'))
      <div class="mb-4 rounded border border-white/20 bg-black/60 p-3 text-white">{{ session('status') }}</div>
    @endif
    @if($errors->any())
      <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">{{ $errors->first() }}</div>
    @endif

    <div class="glass rounded-xl p-6 text-white">
      <form action="{{ route('returns.request') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm mb-1">Peminjaman Aktif Anda</label>
          <select name="borrowing_id" class="select-dark input-field w-full rounded px-3 py-2" required>
            <option value="">Pilih peminjaman…</option>
            @foreach ($activeMine as $b)
              <option value="{{ $b->id }}">{{ $b->book->title }} · Dipinjam: {{ $b->borrowed_at?->format('Y-m-d') }} · Jatuh tempo: {{ $b->due_date?->format('Y-m-d') }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm mb-1">Catatan (opsional)</label>
          <input type="text" name="return_note" class="input-field w-full rounded px-3 py-2" placeholder="Kondisi buku / keterangan lain" />
        </div>
        <div class="flex items-center justify-end gap-2">
          <a href="{{ route('borrow.portal') }}" class="text-sm text-gray-300 hover:text-white">Kembali</a>
          <button class="rounded bg-white px-4 py-2 text-black font-semibold">Ajukan Pengembalian</button>
        </div>
      </form>
    </div>

    <p class="text-sm text-white/70 mt-3">Catatan: Pengembalian akan memiliki status "returned" setelah dikonfirmasi admin.</p>
  </div>
  @if(!empty($isAdmin) && $isAdmin)
    <div class="max-w-4xl mx-auto mt-8">
      <h2 class="text-xl font-semibold text-white mb-3">Konfirmasi Pengembalian (Admin)</h2>
      @if(($pendingReturns ?? collect())->isEmpty())
        <p class="text-gray-300">Belum ada permintaan pengembalian.</p>
      @else
        <div class="space-y-3">
          @foreach ($pendingReturns as $r)
            <div class="glass rounded-xl p-4 text-white flex items-center justify-between">
              <div>
                <div class="font-semibold">{{ $r->book->title ?? '—' }}</div>
                <div class="text-sm text-white/70">
                  Pemimjam: {{ $r->borrower_name }}
                  @if($r->user)
                    ({{ $r->user->email }})
                  @endif
                  · Diminta: {{ optional($r->return_requested_at)->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                </div>
                @if($r->return_note)
                  <div class="text-sm text-white/80">Catatan: {{ $r->return_note }}</div>
                @endif
              </div>
              <form action="{{ route('borrowings.return', $r) }}" method="POST">
                @csrf
                <button class="btn-outline" style="border-radius:10px; padding:8px 12px">Konfirmasi Returned</button>
              </form>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  @endif
@endsection
