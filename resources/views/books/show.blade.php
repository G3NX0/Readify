@extends('layouts.base', ['title' => $book->title])

@section('content')
  <style>
    .glass-card { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(14px); border-radius: 16px; }
    .submit-button { background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%); color:#000; display:inline-flex; align-items:center; gap:8px; }
    .submit-button:hover { filter: brightness(0.95); }
    .badge { background: rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; padding:2px 8px; border-radius:9999px; }
    .badge.unavailable { color:#f87171; border-color: rgba(248,113,113,0.35); background: rgba(248,113,113,0.15); }
    .muted { color: rgba(255,255,255,0.65); }
  </style>

  <div class="max-w-4xl mx-auto">
    <a href="{{ url()->previous() ?: route('books.catalog') }}" class="text-sm text-gray-300 hover:text-white">← Back to Catalog</a>
    <div class="mt-4 glass-card p-6">
      <div class="flex items-start justify-between mb-2">
        <h1 class="text-white text-3xl font-bold">{{ $book->title }}</h1>
        @if($isUnavailable)
          <span class="badge unavailable">Unavailable</span>
        @endif
      </div>
      <p class="mb-1 muted">Author: <span class="text-white">{{ $book->author ?? 'Unknown' }}</span></p>
      <p class="mb-4 muted">Category: <span class="text-white">{{ $book->category->name ?? '-' }}</span></p>

      <h2 class="text-white font-semibold text-lg mb-2">Synopsis</h2>
      <p class="text-gray-200 leading-relaxed">{{ $book->synopsis }}</p>

      <div class="mt-6">
        @if($isUnavailable)
          <button class="submit-button rounded px-4 py-2 opacity-60 cursor-not-allowed" disabled>Borrow This Book</button>
          <p class="text-sm text-gray-300 mt-2">This book is currently borrowed. Please check back later.</p>
        @else
          <a href="{{ route('borrow.portal', ['category_id' => $book->category_id, 'book_id' => $book->id]) }}" class="submit-button rounded px-4 py-2">Borrow This Book</a>
        @endif
      </div>
    </div>
  </div>
@endsection
