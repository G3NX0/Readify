@extends('layouts.base', ['title' => 'Categories'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Categories</h1>
    <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-white hover:bg-black">New Category</a>
  </div>

  <style>
    .table-card { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius: 12px; overflow: hidden; }
    .table-min { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-min thead th { background: rgba(255,255,255,0.05); color: rgba(0,0,0,0.7); font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; padding: 12px 16px; }
    .table-min tbody tr { border-bottom: 1px solid rgba(0,0,0,0.06); }
    .table-min tbody tr:nth-child(even){ background: rgba(0,0,0,0.02); }
    .table-min tbody td { padding: 14px 16px; color: rgba(0,0,0,0.9); vertical-align: top; }
    .table-min tbody tr:hover { background: rgba(0,0,0,0.04); }
    .pill-link { border:1px solid rgba(0,0,0,0.25); color:#111; padding:6px 10px; border-radius: 8px; font-size: .85rem; }
    .pill-link:hover { background: rgba(0,0,0,0.04); }
    .pill-danger { border-color: rgba(239,68,68,0.4); color: #b91c1c; }
    .pill-danger:hover { background: rgba(239,68,68,0.08); }
  </style>
  <div class="overflow-x-auto table-card bg-white">
    <table class="table-min">
      <thead>
        <tr>
          <th>Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>{{ $category->name }}</td>
            <td class="text-right whitespace-nowrap">
              <a href="{{ route('categories.edit', $category) }}" class="pill-link mr-2">Edit</a>
              <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="pill-link pill-danger" onclick="return confirm('Delete this category?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="px-4 py-6 text-gray-500" colspan="2">No categories yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $categories->links() }}</div>
@endsection

