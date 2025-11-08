@extends('layouts.base', ['title' => 'New Book'])

@section('content')
  <h1 class="text-2xl font-semibold mb-6">New Book</h1>

  <form action="{{ route('books.store') }}" method="POST" class="space-y-4 max-w-2xl">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-700">Title</label>
      <input name="title" value="{{ old('title') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" required />
      @error('title')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Author</label>
      <input name="author" value="{{ old('author') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
      @error('author')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Category</label>
      <select name="category_id" class="mt-1 w-full rounded border-gray-300" required>
        <option value="" disabled selected>Select category</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
      @error('category_id')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Synopsis</label>
      <textarea name="synopsis" rows="6" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" required>{{ old('synopsis') }}</textarea>
      @error('synopsis')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div class="flex gap-2">
      <a href="{{ route('books.index') }}" class="px-3 py-2 rounded border">Cancel</a>
      <button class="px-3 py-2 rounded bg-gray-900 text-white">Save</button>
    </div>
  </form>
@endsection
