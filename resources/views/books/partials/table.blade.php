<div class="overflow-hidden rounded-xl glass-container">
  <table class="glass-table w-full">
    <thead>
      <tr>
        <th class="px-6 py-3 text-left text-white/90 font-semibold uppercase tracking-wider">Title</th>
        <th class="px-6 py-3 text-left text-white/90 font-semibold uppercase tracking-wider">Author</th>
        <th class="px-6 py-3 text-left text-white/90 font-semibold uppercase tracking-wider">Category</th>
        <th class="px-6 py-3 text-left text-white/90 font-semibold uppercase tracking-wider">Synopsis</th>
        <th class="px-6 py-3"></th>
      </tr>
    </thead>  
    <tbody id="book-table-body" class="divide-y divide-white/10">
      @forelse($books as $book)
        <tr class="transition hover:bg-white/5">
          <td class="px-6 py-3 font-medium text-white/90">{{ $book->title }}</td>
          <td class="px-6 py-3 text-white/90">{{ $book->author ?? '-' }}</td>
          <td class="px-6 py-3 text-white/90">{{ $book->category->name ?? '-' }}</td>
          <td class="px-6 py-3 text-white/60">{{ Str::limit($book->synopsis, 120) }}</td>
          <td class="px-6 py-3 text-right">
            <div class="flex justify-end gap-2">
              <a href="{{ route('books.edit', $book) }}" class="btn-dark text-sm px-3 py-1.5 rounded-md border hover:text-indigo-300">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-dark btn-danger text-sm px-3 py-1.5 rounded-md border">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center py-6 text-gray-400">No books found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="mt-4">
    {{ $books->links() }}
  </div>
</div>