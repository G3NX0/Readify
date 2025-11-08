@extends('layouts.base', ['title' => $book->title])

@section('content')
  <div class="max-w-3xl mx-auto">
    <a href="{{ url()->previous() ?: route('books.catalog') }}" class="text-sm text-gray-300 hover:text-white">← Back to Catalog</a>
    <div class="mt-4 record-card" style="padding: 24px; border-radius: 12px;">
      <h1 class="text-white text-3xl font-bold mb-2">{{ $book->title }}</h1>
      <p class="text-gray-300 mb-1">Author: <span class="text-white">{{ $book->author ?? 'Unknown' }}</span></p>
      <p class="text-gray-300 mb-4">Category: <span class="text-white">{{ $book->category->name ?? '-' }}</span></p>
      <h2 class="text-white font-semibold text-lg mb-2">Synopsis</h2>
      <p class="text-gray-200 leading-relaxed">{{ $book->synopsis }}</p>
      <div class="mt-6">
        <a href="{{ route('borrow.portal') }}" class="submit-button rounded px-4 py-2">Borrow This Book</a>
      </div>
    </div>
  </div>
@endsection

