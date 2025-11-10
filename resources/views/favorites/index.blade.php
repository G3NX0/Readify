@extends("layouts.base", ["title" => "Favorites"])

@section("content")
  <style>
    .record-card { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px); transition: all .2s ease; }
    .record-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(255,255,255,0.08); }
    .badge { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; padding:2px 8px; border-radius:9999px; }
  </style>

  <div class="mb-6">
    <h1 class="text-2xl font-semibold text-white">Favorite Books</h1>
    <p class="text-gray-300">Buku-buku yang Anda tandai sebagai favorit</p>
  </div>

  @if ($books->isEmpty())
    <div class="text-gray-300">Belum ada favorit.</div>
  @else
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach ($books as $book)
        <div class="record-card p-5 rounded-xl">
          <div class="flex items-start justify-between mb-2">
            <h3 class="text-white font-semibold text-lg">{{ $book->title }}</h3>
            <form action="{{ route('favorites.destroy', $book) }}" method="POST">
              @csrf @method('DELETE')
              <button class="badge" title="Hapus favorit">Remove</button>
            </form>
          </div>
          <p class="text-gray-300 text-sm mb-2">{{ $book->author ?? 'Unknown' }} · {{ $book->category->name ?? '—' }}</p>
          <p class="text-gray-200 text-sm">{{ \Illuminate\Support\Str::limit($book->synopsis, 220) }}</p>
          <div class="mt-3">
            <a href="{{ route('books.show', $book) }}" class="text-white underline">Lihat detail</a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
@endsection

