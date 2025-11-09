@forelse ($sections as $category)
  <section class="mb-10">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-white text-xl font-semibold">{{ $category->name }}</h2>
      <span class="badge">{{ $category->books->count() }} books</span>
    </div>
    <div class="h-scroll-wrap">
      <button class="h-arrow left" aria-label="Scroll left" data-target="row-{{ $category->id }}">
        <svg
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M15 18L9 12L15 6"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button>
      <div id="row-{{ $category->id }}" class="scroll-row scrollbar-styled">
        @foreach ($category->books as $book)
          <a
            href="{{ route("books.show", $book) }}"
            draggable="false"
            class="record-card block p-5 rounded-xl text-current no-underline"
            style="min-width: 280px; max-width: 320px"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div
                  style="
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                    background: linear-gradient(
                      135deg,
                      rgba(255, 255, 255, 0.9),
                      rgba(255, 255, 255, 0.6)
                    );
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #000;
                    font-weight: 700;
                  "
                >
                  {{ \Illuminate\Support\Str::substr($book->title, 0, 1) }}
                </div>
                <h3 class="text-white font-semibold text-lg">{{ $book->title }}</h3>
              </div>
              @if (in_array($book->id, $unavailableBookIds))
                <span class="badge unavailable">Unavailable</span>
              @endif
            </div>
            <p class="text-gray-300 text-sm mb-2">
              {{ $book->author ?? "Unknown" }} &middot; {{ $category->name }}
            </p>
            <p class="text-gray-200 text-sm clamp-3">
              {{ \Illuminate\Support\Str::limit($book->synopsis, 220) }}
            </p>
          </a>
        @endforeach
      </div>
      <button
        class="h-arrow right"
        aria-label="Scroll right"
        data-target="row-{{ $category->id }}"
      >
        <svg
          width="16"
          height="16"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M9 18L15 12L9 6"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </button>
      <span class="fade-left"></span>
      <span class="fade-right"></span>
    </div>
  </section>
@empty
  <div class="text-gray-300">No books found.</div>
@endforelse
