@extends('layouts.base', ['title' => 'Profil'])

@section('content')
  <style>
    .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
  </style>
  <div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-semibold text-white mb-4">Profil</h1>

    @if($errors->any())
      <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">{{ $errors->first() }}</div>
    @endif

    <div class="glass rounded-xl p-6 text-white">
      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        @php
          $currentFilename = $user->profile_photo_path
            ? \Illuminate\Support\Str::afterLast(str_replace('\\', '/', $user->profile_photo_path), '/')
            : null;
        @endphp
        <div class="col-span-1 md:col-span-2 flex items-center gap-4 mb-2">
          <div class="w-16 h-16 rounded-full overflow-hidden bg-white/10 border border-white/20 flex items-center justify-center">
            <img id="profilePreview" src="{{ route('profile.photo', $user->id) }}?v={{ $user->updated_at?->timestamp }}" alt="PP" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,{{ base64_encode("<?xml version='1.0' encoding='UTF-8'?><svg xmlns='http://www.w3.org/2000/svg' width='200' height='200'><rect width='100%' height='100%' fill='%230b0b0b'/><text x='50%' y='55%' text-anchor='middle' dominant-baseline='middle' fill='%23fff' font-family='Arial' font-size='96'>" . strtoupper(substr($user->name ?? 'U', 0, 1)) . "</text></svg>") }}'" />
          </div>
          <div>
            <label class="block text-sm mb-1">Foto Profil</label>
            <input id="photoInput" type="file" name="photo" accept="image/*" class="hidden" onchange="(function(i,n,img){ if(i && i.files && i.files[0]){ n.textContent=i.files[0].name; const url=URL.createObjectURL(i.files[0]); img.src=url; img.onload=function(){URL.revokeObjectURL(url)} } })(this, document.getElementById('photoFilename'), document.getElementById('profilePreview'))" />
            <label for="photoInput" class="inline-flex items-center gap-2 px-3 py-1.5 rounded bg-white text-black text-sm font-medium cursor-pointer">
              Pilih File
            </label>
            <span id="photoFilename" class="ml-2 text-xs text-gray-300">{{ $currentFilename ? 'Avatar tersimpan' : 'Belum ada file' }}</span>
          </div>
        </div>

        <div>
          <label class="block text-sm mb-1">Nama</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Username</label>
          <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Password Baru (opsional)</label>
          <input type="password" name="password" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm mb-1">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" />
        </div>
        <div class="md:col-span-2 flex items-center justify-end gap-2">
          <a href="/" class="text-sm text-gray-300 hover:text-white">Kembali</a>
          <button class="rounded bg-white px-4 py-2 text-black font-semibold">Simpan</button>
        </div>
      </form>
    </div>

    <div class="glass rounded-xl p-6 text-white mt-6">
      <h2 class="text-lg font-semibold mb-3">Riwayat Peminjaman</h2>
      @php
        $user = auth()->user();
        $history = \App\Models\Borrowing::with('book')
          ->when($user, function ($q) use ($user) {
            $q->where(function ($sub) use ($user) {
              $sub->where('user_id', $user->id)
                  ->orWhere('borrower_name', (string) $user->name);
            });
          })
          ->orderByDesc('created_at')
          ->limit(50)
          ->get();
      @endphp
      @if ($history->isEmpty())
        <p class="text-gray-300">Belum ada riwayat.</p>
      @else
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-white/80">
                <th class="text-left py-2">Buku</th>
                <th class="text-left py-2">Dipinjam</th>
                <th class="text-left py-2">Jatuh Tempo</th>
                <th class="text-left py-2">Dikembalikan</th>
                <th class="text-left py-2">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($history as $h)
                <tr class="border-t border-white/10">
                  <td class="py-2">{{ $h->book->title ?? '—' }}</td>
                  <td class="py-2">{{ $h->borrowed_at?->format('Y-m-d') }}</td>
                  <td class="py-2">{{ $h->due_date?->format('Y-m-d') }}</td>
                  <td class="py-2">{{ $h->returned_at?->format('Y-m-d') ?? '—' }}</td>
                  <td class="py-2">
                    @if ($h->returned_at)
                      <span class="text-green-400">returned</span>
                    @elseif($h->return_requested_at)
                      <span class="text-yellow-300">return requested</span>
                    @else
                      <span class="text-orange-300">borrowed</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photoInput');
    const nameEl = document.getElementById('photoFilename');
    const img = document.getElementById('profilePreview');
    if (!input || !nameEl || !img) return;
    input.addEventListener('change', () => {
      const f = input.files && input.files[0];
      if (f) {
        // Batasi 4MB untuk mencegah 413 di server
        const MAX = 4 * 1024 * 1024;
        if (f.size > MAX) {
          alert('Ukuran file terlalu besar. Maksimal 4MB.');
          input.value = '';
          nameEl.textContent = 'Belum ada file';
          return;
        }
        nameEl.textContent = f.name;
        const url = URL.createObjectURL(f);
        img.src = url;
        img.onload = () => URL.revokeObjectURL(url);
      } else {
        nameEl.textContent = 'Belum ada file';
      }
    });
  });
  </script>
@endpush
