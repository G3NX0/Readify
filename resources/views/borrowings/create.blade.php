@extends('layouts.base', ['title' => 'New Borrowing'])

@section('content')
<div class="flex justify-center items-start pt-10">
  <div class="w-full max-w-2xl bg-black/30 backdrop-blur-2xl rounded-2xl shadow-2xl p-8 border border-white/10">

    <h1 class="text-3xl font-semibold text-gray-100 mb-8 tracking-wide drop-shadow-lg">New Borrowing</h1>

    <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-6">
      @csrf

      {{-- Book --}}
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Book</label>
        <select 
          name="book_id"
          class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 px-4 py-2 
                 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
          required
        >
          <option value="" disabled selected>Select book</option>
          @foreach($books as $book)
            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
              {{ $book->title }}
            </option>
          @endforeach
        </select>
        @error('book_id')
          <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Borrower Name --}}
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Borrower Name</label>
        <input 
          name="borrower_name"
          value="{{ old('borrower_name') }}"
          class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 placeholder-gray-500 px-4 py-2 
                 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
          placeholder="Enter borrower name..."
          required
        />
        @error('borrower_name')
          <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Borrowed & Due Dates --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Borrowed At</label>
          <input 
            type="date" 
            name="borrowed_at"
            value="{{ old('borrowed_at', now()->toDateString()) }}"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 px-4 py-2 
                   focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            required
          />
          @error('borrowed_at')
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Due Date</label>
          <input 
            type="date" 
            name="due_date"
            value="{{ old('due_date') }}"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 px-4 py-2 
                   focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            required
          />
          @error('due_date')
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <p class="text-sm text-gray-400">Note: Late fee Rp10.000 per day.</p>

      {{-- Buttons --}}
      <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('borrowings.index') }}"
           class="px-4 py-2 rounded-lg border border-white/10 text-gray-300 hover:bg-white/5 hover:border-white/20 transition duration-200">
          Cancel
        </a>

        <button type="submit"
                class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-gray-100 
                       border border-white/10 shadow-md backdrop-blur-md transition duration-200">
          Save
        </button>
      </div>

    </form>
  </div>
</div>
@endsection