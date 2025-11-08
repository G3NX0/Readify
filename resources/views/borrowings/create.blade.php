@extends('layouts.base', ['title' => 'New Borrowing'])

@section('content')
  <h1 class="text-2xl font-semibold mb-6">New Borrowing</h1>

  <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-4 max-w-2xl">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-300">Book</label>
      <select name="book_id" class="select-dark mt-1" required>
        <option value="" disabled selected>Select book</option>
        @foreach($books as $book)
          <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->title }}</option>
        @endforeach
      </select>
      @error('book_id')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Borrower Name</label>
      <input name="borrower_name" value="{{ old('borrower_name') }}" class="mt-1 w-full rounded border-gray-300 focus:border-gray-900 focus:ring-gray-900" required />
      @error('borrower_name')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
      @enderror
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Borrowed At</label>
        <input type="date" name="borrowed_at" value="{{ old('borrowed_at', now()->toDateString()) }}" class="mt-1 w-full rounded border-gray-300" required />
        @error('borrowed_at')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Due Date</label>
        <input type="date" name="due_date" value="{{ old('due_date') }}" class="mt-1 w-full rounded border-gray-300" required />
        @error('due_date')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <p class="text-sm text-gray-600">Note: Denda keterlambatan Rp10.000 per hari.</p>
    <div class="flex gap-2">
      <a href="{{ route('borrowings.index') }}" class="px-3 py-2 rounded border">Cancel</a>
      <button class="px-3 py-2 rounded bg-gray-900 text-white">Save</button>
    </div>
  </form>
@endsection
