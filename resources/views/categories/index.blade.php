@extends('layouts.base', ['title' => 'Categories'])

@section('content')
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Categories</h1>
    <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded bg-gray-900 px-3 py-2 text-white hover:bg-black">New Category</a>
  </div>

  <div class="overflow-hidden rounded bg-white shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @forelse($categories as $category)
          <tr>
            <td class="px-4 py-2">{{ $category->name }}</td>
            <td class="px-4 py-2 text-right">
              <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
              <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this category?')">Delete</button>
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

