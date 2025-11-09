@extends("layouts.base", ["title" => "New Category"])

@section("content")
  <div class="flex justify-center items-start pt-10">
    <div
      class="w-full max-w-lg bg-black/30 backdrop-blur-2xl rounded-2xl shadow-2xl p-8 border border-white/10"
    >
      <h1 class="text-3xl font-semibold text-gray-100 mb-8 tracking-wide drop-shadow-lg">
        New Category
      </h1>

      <form action="{{ route("categories.store") }}" method="POST" class="space-y-6">
        @csrf

        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1">Name</label>
          <input
            name="name"
            value="{{ old("name") }}"
            class="w-full rounded-lg border border-white/10 bg-black/40 text-gray-100 placeholder-gray-500 px-4 py-2 focus:ring-2 focus:ring-white/20 focus:border-white/20 transition duration-200 backdrop-blur-md"
            placeholder="Enter category name..."
            required
          />
          @error("name")
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <a
            href="{{ route("categories.index") }}"
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
