@extends('layouts.base', ['title' => 'Books'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Books</h1>
    <a href="{{ route('books.create') }}" class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-white hover:bg-black">New Book</a>
  </div>

  <form method="GET" class="mb-4 flex flex-wrap gap-3 items-center">
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

  <div class="overflow-hidden rounded bg-white shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Author</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Synopsis</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @forelse($books as $book)
          <tr>
            <td class="px-4 py-2 font-medium">{{ $book->title }}</td>
            <td class="px-4 py-2">{{ $book->author ?? '-' }}</td>
            <td class="px-4 py-2">{{ $book->category->name ?? '-' }}</td>
            <td class="px-4 py-2 text-gray-600">{{ Str::limit($book->synopsis, 120) }}</td>
            <td class="px-4 py-2 text-right">
              <a href="{{ route('books.edit', $book) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
              <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this book?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="px-4 py-6 text-gray-500" colspan="4">No books yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $books->links() }}</div>
@endsection
