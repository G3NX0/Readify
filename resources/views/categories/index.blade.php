@extends('layouts.base', ['title' => 'Categories'])

@section('content')
  <style>
    .glass-container {
      background: rgba(20, 20, 20, 0.55);
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    table.glass-table {
      color: #f9fafb;
      width: 100%;
      border-collapse: collapse;
    }

    table.glass-table thead th {
      padding: 12px;
      font-size: 0.75rem;
      text-transform: uppercase;
      color: rgba(255,255,255,0.7);
      font-weight: 600;
      letter-spacing: 0.05em;
      text-align: left;
    }

    table.glass-table tbody td {
      padding: 14px 12px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.9);
    }

    table.glass-table tbody td.synopsis {
      color: rgba(255,255,255,0.6);
    }

    table.glass-table tbody tr:hover {
      background: rgba(255,255,255,0.04);
      transition: all 0.2s ease;
    }

    .btn-dark {
      background: rgba(255,255,255,0.08);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.12);
      transition: all 0.2s ease;
    }

    .btn-dark:hover {
      background: rgba(255,255,255,0.2);
      border-color: rgba(255,255,255,0.25);
    }

    .btn-danger {
      color: #f87171;
      border-color: rgba(248,113,113,0.4);
    }

    .btn-danger:hover {
      background: rgba(248,113,113,0.15);
    }
  </style>

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-white">Categories</h1>
    <a href="{{ route('categories.create') }}" class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition">
      <i class="bi bi-plus-lg mr-1"></i> Add Category
    </a>
  </div>

  <div class="overflow-hidden rounded-xl glass-container">
    <table class="glass-table">
      <thead>
        <tr>
          <th>Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/10">
        @forelse($categories as $category)
          <tr class="transition hover:bg-white/5">
            <td class="px-6 py-3 text-white/90 font-medium">{{ $category->name }}</td>
            <td class="px-6 py-3 text-right">
              <div class="flex justify-end gap-2">
                <a href="{{ route('categories.edit', $category) }}" class="btn-dark text-sm px-3 py-1.5 rounded-md border hover:text-indigo-300">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
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
            <td colspan="2" class="text-center py-6 text-gray-400">No categories found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $categories->links() }}
    </div>
  </div>
@endsection