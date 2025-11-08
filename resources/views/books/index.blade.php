@extends('layouts.base', ['title' => 'Books'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Books</h1>
    <a href="{{ route('books.create') }}" class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-white hover:bg-black">New Book</a>
  </div>

  <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
    <div>
      <label class="text-sm text-gray-600 mr-2">Category:</label>
      <select name="category_id" class="rounded border-gray-300" onchange="this.form.submit()">
        <option value="">All</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="flex items-center gap-2">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title/author/synopsis" class="rounded border-gray-300" />
      <button class="px-3 py-2 rounded bg-gray-900 text-white">Search</button>
    </div>
  </form>

  <style>
    .table-card { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius: 12px; overflow: hidden; }
    .table-min { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-min thead th { background: rgba(255,255,255,0.05); color: rgba(0,0,0,0.7); font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; padding: 12px 16px; }
    .table-min tbody tr { border-bottom: 1px solid rgba(0,0,0,0.06); }
    .table-min tbody tr:nth-child(even){ background: rgba(0,0,0,0.02); }
    .table-min tbody td { padding: 14px 16px; color: rgba(0,0,0,0.9); vertical-align: top; }
    .table-min tbody tr:hover { background: rgba(0,0,0,0.04); }
    .muted { color: rgba(0,0,0,0.65); }
    .pill-link { border:1px solid rgba(0,0,0,0.25); color:#111; padding:6px 10px; border-radius: 8px; font-size: .85rem; }
    .pill-link:hover { background: rgba(0,0,0,0.04); }
    .pill-danger { border-color: rgba(239,68,68,0.4); color: #b91c1c; }
    .pill-danger:hover { background: rgba(239,68,68,0.08); }
    .badge-cat { display:inline-block; border:1px solid rgba(0,0,0,0.2); background: rgba(0,0,0,0.03); color:#111; font-size:.78rem; padding: 2px 8px; border-radius: 999px; }
  </style>
  <div class="overflow-x-auto table-card bg-white">
    <table class="table-min">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Category</th>
          <th>Synopsis</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($books as $book)
          <tr>
            <td class="font-semibold">{{ $book->title }}</td>
            <td class="muted">{{ $book->author ?? '-' }}</td>
            <td class="muted">
              @if($book->category)
                <span class="badge-cat">{{ $book->category->name }}</span>
              @else
                -
              @endif
            </td>
            <td class="muted">{{ Str::limit($book->synopsis, 120) }}</td>
            <td class="text-right whitespace-nowrap">
              <a href="{{ route('books.edit', $book) }}" class="pill-link mr-2">Edit</a>
              <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="pill-link pill-danger" onclick="return confirm('Delete this book?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="muted px-4 py-6" colspan="5">No books yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $books->links() }}</div>
@endsection
