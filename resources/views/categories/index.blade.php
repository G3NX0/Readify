@extends('layouts.base', ['title' => 'Categories'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold mb-6 text-white">Categories Table</h1>
    <a href="{{ route('categories.create') }}"
      class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition">
      <i class="bi bi-plus-lg mr-1"></i> New Category
    </a>
  </div>

  <style>
    .table-card { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); backdrop-filter: blur(10px); border-radius: 12px; overflow: hidden; }
    .table-min { width: 100%; border-collapse: separate; border-spacing: 0; }
    .table-min thead th { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.8); font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; padding: 12px 16px; }
    .table-min tbody tr { border-bottom: 1px solid rgba(255,255,255,0.08); }
    .table-min tbody td { padding: 14px 16px; color: rgba(255,255,255,0.92); vertical-align: top; }
    .table-min tbody tr:hover { background: rgba(255,255,255,0.035); }
    .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.35); color:#fff; transition: background 0.2s; }
    .icon-btn:hover { background: rgba(255,255,255,0.06); }
    .icon-btn-danger { border-color: rgba(239,68,68,0.5); color: #fca5a5; }
    .icon-btn-danger:hover { background: rgba(239,68,68,0.08); }
  </style>

  <div class="overflow-x-auto table-card">
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
            <td class="text-right whitespace-nowrap flex gap-2 justify-end">
              <a href="{{ route('categories.edit', $category) }}" class="icon-btn" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn icon-btn-danger" onclick="return confirm('Delete this category?')" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
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