@extends('layouts.base', ['title' => 'Books'])

@section('content')
<style>
  .glass-container {
    background: rgba(20, 20, 20, 0.55);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  }
  .glass-input {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    backdrop-filter: blur(10px);
    transition: all 0.2s ease;
  }
  .glass-input::placeholder { color: rgba(255,255,255,0.5); }
  .glass-input:focus {
    border-color: rgba(255, 255, 255, 0.25);
    outline: none;
    box-shadow: 0 0 0 2px rgba(255,255,255,0.08);
  }
  .glass-select {
    background: rgba(255,255,255,0.05);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
    backdrop-filter: blur(10px);
  }
  .glass-select option { background: #0f0f0f; color: #fff; }
  .btn-dark { background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.12); transition: all 0.2s ease; }
  .btn-dark:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.25); }
  .btn-danger { color: #f87171; border-color: rgba(248,113,113,0.4); }
  .btn-danger:hover { background: rgba(248,113,113,0.15); }
</style>

<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-semibold text-white">Books</h1>
  <a href="{{ route('books.create') }}" class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition">
    <i class="bi bi-plus-lg mr-1"></i> Add Book
  </a>
</div>

{{-- SEARCH FORM --}}
<form method="GET" id="search-form" class="mb-6 flex flex-wrap gap-3 items-center glass-container p-4 rounded-lg">
  <div class="flex items-center gap-2">
    <label class="text-sm text-gray-300">Category:</label>
    <select name="category_id" class="glass-select rounded-md text-sm px-3 py-1.5">
      <option value="">All</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="relative flex-grow max-w-md">
    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
    <input type="text" name="q" id="search-input" placeholder="Search title, author, or synopsis..." class="glass-input w-full pl-10 pr-24 py-2 rounded-full text-sm"/>
    <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-full bg-white text-black font-semibold text-sm px-4 py-1.5 hover:bg-gray-200 transition">
      Search
    </button>
  </div>
</form>

<div id="search-results">
  @include('books.partials.table', ['books' => $books])
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('search-form');
  const input = document.getElementById('search-input');
  const categorySelect = form.querySelector('select[name="category_id"]');
  const results = document.getElementById('search-results');
  let timeout = null;

  const fetchResults = () => {
    const q = input.value.trim();
    const category = categorySelect.value;
    const params = new URLSearchParams({ q, category_id: category });

    results.innerHTML = `
      <div class="flex justify-center py-6 text-gray-400 text-sm animate-pulse">
        <span>Loading...</span>
      </div>
    `;

    fetch(`{{ route('books.index') }}?${params.toString()}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => results.innerHTML = html)
    .catch(err => {
      console.error('Fetch error:', err);
      results.innerHTML = `<div class="text-center py-6 text-red-500">Error loading data</div>`;
    });
  };

  input.addEventListener('input', () => {
    clearTimeout(timeout);
    timeout = setTimeout(fetchResults, 300);
  });

  categorySelect.addEventListener('change', fetchResults);

  form.addEventListener('submit', e => {
    e.preventDefault();
    fetchResults();
  });
});
</script>
@endsection