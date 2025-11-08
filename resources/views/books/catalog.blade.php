@extends('layouts.base', ['title' => 'Catalog'])

@section('content')
  <style>
    .glass-effect { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
    .record-card { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border:1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px); transition: all .2s ease; }
    .record-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(255,255,255,0.08); }
    .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color:#fff; color-scheme: dark; }
    .input-field:focus { outline:none; border-color: rgba(255,255,255,0.4); }
    .input-field option { background:#0b0b0b; color:#fff; }
    .submit-button { background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%); color:#000; }
    .badge { background: rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; padding:2px 8px; border-radius:9999px; }
    .unavailable { color:#f87171; border-color: rgba(248,113,113,0.3); background: rgba(248,113,113,0.15); }
  </style>

  <div class="mb-6">
    <h1 class="text-2xl font-semibold text-white">Book Catalog</h1>
    <p class="text-gray-300">Browse books by category and search quickly</p>
  </div>

  <form method="GET" class="mb-8 glass-effect rounded-xl p-4 flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[220px]">
      <label class="text-sm text-gray-300 block mb-1">Category</label>
      <select 
        name="category_id" 
        class="input-field rounded-lg px-3 py-2 w-full text-sm focus:ring-1 focus:ring-gray-400 transition"
        onchange="this.form.submit()"
      >
        <option value="">All</option>
        @foreach($filterCategories as $category)
          <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex flex-1 items-end gap-2 min-w-[280px]">
      <div class="flex-1">
        <label class="text-sm text-gray-300 block mb-1">Search</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}" 
            placeholder="Title, author, or synopsis" 
            class="input-field pl-9 pr-3 py-2 w-full rounded-lg text-sm"
          />
        </div>
      </div>

      <div class="flex gap-2">
        <button 
          type="submit" 
          class="bg-white text-black font-semibold text-sm px-4 py-2 rounded-lg hover:bg-gray-200 transition"
        >
          Go
        </button>
        <a 
          href="{{ route('books.catalog') }}" 
          class="border border-gray-500 text-gray-300 text-sm px-4 py-2 rounded-lg hover:bg-gray-800 hover:text-white transition"
        >
          Reset
        </a>
      </div>
    </div>
  </form>

  @forelse($sections as $category)
    <section class="mb-10">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-white text-xl font-semibold">{{ $category->name }}</h2>
        <span class="badge">{{ $category->books->count() }} books</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($category->books as $book)
          <a href="{{ route('books.show', $book) }}" class="record-card block p-5 rounded-xl text-current no-underline">
            <div class="flex items-start justify-between mb-2">
              <h3 class="text-white font-semibold text-lg">{{ $book->title }}</h3>
              @if(in_array($book->id, $unavailableBookIds))
                <span class="badge unavailable">Unavailable</span>
              @endif
            </div>
            <p class="text-gray-300 text-sm mb-2">{{ $book->author ?? 'Unknown' }} &middot; {{ $category->name }}</p>
            <p class="text-gray-200 text-sm">{{ Str::limit($book->synopsis, 180) }}</p>
          </a>
        @endforeach
      </div>
    </section>
  @empty
    <div class="text-gray-300">No books found.</div>
  @endforelse
@endsection
