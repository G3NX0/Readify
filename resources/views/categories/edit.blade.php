@extends('layouts.base', ['title' => 'Edit Category'])

@section('content')
  <h1 class="text-2xl font-semibold mb-6">Edit Category</h1>

  <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4 max-w-lg">
    @csrf
    @method('PUT')
    <div>
      <label class="block text-sm font-medium text-gray-700">Name</label>
      <input name="name" value="{{ old('name', $category->name) }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" required />
      @error('name')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div class="flex gap-2">
      <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded border">Cancel</a>
      <button class="px-3 py-2 rounded bg-gray-900 text-white">Update</button>
    </div>
  </form>
@endsection

