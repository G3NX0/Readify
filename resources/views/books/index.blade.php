@extends("layouts.base", ["title" => "Books"])

@section("content")
  <style>
    .filter-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
      justify-content: space-between;
    }

    .left-group {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
      flex: 1;
      min-width: 0;
    }

    .input-wrap {
      position: relative;
      flex: 0 1 280px;
      min-width: 0;
    }

    .input-wrap svg {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0.7;
      z-index: 2;
    }

    .input-dark {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #fff;
      border-radius: 10px;
      padding: 10px 12px;
      padding-left: 38px;
      width: 100%;
      height: 40px;
      box-sizing: border-box;
      line-height: 20px; /* Pastikan line-height konsisten */
    }

    .input-dark::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .select-wrap {
      position: relative;
      flex: 0 1 200px;
      min-width: 0;
    }

    .select-dark {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #fff;
      padding: 11px 14px; /* Padding atas-bawah disesuaikan */
      padding-right: 32px;
      border-radius: 10px;
      height: 40px;
      width: 100%;
      box-sizing: border-box;
      appearance: none;
      text-align: left;
      line-height: 18px; /* Line-height untuk penjajaran vertikal */
      font-size: 14px; /* Ukuran font standar */
    }

    .select-wrap::after {
      content: '▼';
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.7);
      font-size: 10px;
      pointer-events: none;
    }

    .select-dark option {
      background: #111;
      padding: 8px 12px;
      line-height: 1.4;
    }

    .btn-ghost {
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: #fff;
      padding: 10px 20px;
      border-radius: 10px;
      height: 40px;
      flex-shrink: 0;
      white-space: nowrap;
      display: flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
      font-size: 14px;
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .new-book-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #fff;
      padding: 10px 16px;
      height: 40px;
      white-space: nowrap;
      transition: all 0.2s;
      line-height: 1;
      font-size: 14px;
    }

    .new-book-btn:hover {
      background: rgba(255, 255, 255, 0.12);
      border-color: rgba(255, 255, 255, 0.25);
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    .table-card {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      overflow: hidden;
    }

    .table-min {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    .table-min thead th {
      background: rgba(255, 255, 255, 0.04);
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-weight: 600;
      padding: 12px 16px;
    }

    .table-min tbody tr {
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .table-min tbody td {
      padding: 14px 16px;
      color: rgba(255, 255, 255, 0.92);
      vertical-align: top;
    }

    .table-min tbody tr:hover {
      background: rgba(255, 255, 255, 0.035);
    }

    .muted {
      color: rgba(255, 255, 255, 0.75);
    }

    .badge-cat {
      display: inline-block;
      border: 1px solid rgba(255, 255, 255, 0.25);
      background: rgba(255, 255, 255, 0.06);
      color: #fff;
      font-size: 0.78rem;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .icon-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: #fff;
      transition: background 0.2s;
      margin-left: 4px;
    }

    .icon-btn:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .icon-btn-danger {
      border-color: rgba(239, 68, 68, 0.5);
      color: #fca5a5;
    }

    .icon-btn-danger:hover {
      background: rgba(239, 68, 68, 0.08);
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
      .filter-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
      }

      .left-group {
        flex-direction: column;
        gap: 16px;
      }

      .input-wrap,
      .select-wrap {
        flex: 1 1 auto;
        width: 100%;
      }

      .btn-ghost,
      .new-book-btn {
        width: 100%;
        justify-content: center;
      }
    }

    /* Untuk layar sangat kecil */
    @media (max-width: 480px) {
      .filter-bar {
        gap: 12px;
      }

      .left-group {
        gap: 12px;
      }
    }
  </style>

  <h1 class="text-2xl font-semibold mb-6 text-white">Books Table</h1>

  <form id="search-form" class="mb-6">
    <div class="filter-bar">
      <div class="left-group">
        <div class="select-wrap">
          <select name="category_id" class="select-dark" id="category-select">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
              <option
                value="{{ $category->id }}"
                @selected(request("category_id") == $category->id)
              >
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="input-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
            <line
              x1="16.65"
              y1="16.65"
              x2="21"
              y2="21"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
            />
          </svg>
          <input
            type="text"
            id="search-input"
            name="q"
            value="{{ request("q") }}"
            placeholder="Search title, author, synopsis"
            class="input-dark"
          />
        </div>

        <button type="submit" class="btn-ghost">Search</button>
      </div>
      <a
        href="{{ route("books.create") }}"
        class="rounded-lg bg-white text-black px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition"
      >
        <i class="bi bi-plus-lg mr-1"></i>
        New Book
      </a>
    </div>
  </form>

  <div id="search-results">
    @include("books.partials.table")
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('search-form');
      const input = document.getElementById('search-input');
      const category = document.getElementById('category-select');
      const results = document.getElementById('search-results');

      let timeout = null;

      const fetchResults = () => {
        const params = new URLSearchParams({
          q: input.value,
          category_id: category.value,
        });

        results.innerHTML = `<div class="flex justify-center py-6 text-gray-400 animate-pulse">Loading...</div>`;

        fetch(`{{ route("books.index") }}?${params.toString()}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
          .then((res) => res.text())
          .then((html) => {
            results.innerHTML = html;
            try {
              history.replaceState(null, '', `?${params.toString()}`);
            } catch (e) {}
          })
          .catch(
            () =>
              (results.innerHTML = `<div class="text-center py-6 text-red-500">Error loading data</div>`),
          );
      };

      input.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchResults, 300);
      });

      category.addEventListener('change', fetchResults);

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchResults();
      });

      // Intercept pagination links inside results to load via AJAX
      results.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;
        const href = a.getAttribute('href') || '';
        // Handle only pagination links (contain page=)
        if (href.includes('page=')) {
          e.preventDefault();
          results.innerHTML = `<div class=\"flex justify-center py-6 text-gray-400 animate-pulse\">Loading...</div>`;
          fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((r) => r.text())
            .then((html) => {
              results.innerHTML = html;
              try {
                history.replaceState(null, '', href);
              } catch (e) {}
            })
            .catch(
              () =>
                (results.innerHTML = `<div class=\"text-center py-6 text-red-500\">Error loading data</div>`),
            );
        }
      });
    });
  </script>
@endsection
