@extends("layouts.base", ["title" => "New Book"])

@section("content")
  <div class="flex justify-center items-start pt-10">
    <div
      class="w-full max-w-2xl bg-black/30 backdrop-blur-2xl rounded-2xl shadow-2xl p-8 border border-white/10"
    >
      <h1 class="text-3xl font-semibold text-gray-100 mb-8 tracking-wide drop-shadow-lg">
        New Book
      </h1>

      <form action="{{ route("books.store") }}" method="POST" class="space-y-6">
        @csrf

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
          <input
            name="title"
            value="{{ old("title") }}"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 placeholder-gray-500 px-4 py-2 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            placeholder="Enter book title..."
            required
          />
          @error("title")
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Author</label>
          <input
            name="author"
            value="{{ old("author") }}"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 placeholder-gray-500 px-4 py-2 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            placeholder="Enter author name..."
          />
          @error("author")
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Category</label>
          <select name="category_id" class="select-dark" required>
            <option value="" disabled selected>Select category</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @selected(old("category_id") == $category->id)>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @error("category_id")
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Synopsis</label>
          <textarea
            name="synopsis"
            rows="6"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 placeholder-gray-500 px-4 py-2 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            placeholder="Write a short synopsis..."
            required
          >
{{ old("synopsis") }}</textarea
          >
          @error("synopsis")
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <a
            href="{{ route("books.index") }}"
            class="px-4 py-2 rounded-lg border border-white/10 text-gray-300 hover:bg-white/5 hover:border-white/20 transition duration-200"
          >
            Cancel
          </a>

          <button
            type="submit"
            class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-gray-100 border border-white/10 shadow-md backdrop-blur-md transition duration-200"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
