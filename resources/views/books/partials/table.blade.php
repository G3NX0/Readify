<div class="table-card mt-4">
  <table class="table-min">
    <thead>
      <tr>
        <th style="width: 55px">#</th>
        <th>Title</th>
        <th style="width: 140px">Author</th>
        <th style="width: 90px; text-align: center">Category</th>
        <th>Description</th>
        <th style="width: 120px; text-align: center">Actions</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($books as $book)
        <tr>
          <td>{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>

          <td>
            <span class="font-semibold">{{ $book->title }}</span>
          </td>

          <td>{{ $book->author ?? "-" }}</td>

          <td style="text-align: center">
            @if ($book->category)
              <span class="badge-cat">{{ $book->category->name }}</span>
            @else
              <span class="muted">-</span>
            @endif
          </td>

          <td>{{ \Illuminate\Support\Str::limit($book->synopsis, 120) }}</td>

          <td style="text-align: center">
            <a href="{{ route("books.edit", $book->id) }}" class="icon-btn">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route("books.destroy", $book->id) }}" method="POST" class="inline">
              @csrf
              @method("DELETE")
              <button
                class="icon-btn icon-btn-danger"
                onclick="return confirm('Delete this book?')"
              >
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center py-6 muted">No books found</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">
  {{ $books->links() }}
</div>
