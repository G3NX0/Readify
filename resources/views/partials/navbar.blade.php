<nav class="navbar">
  <style>
    .mobile-menu {
      transform: translateX(100%);
      transition: transform 0.25s ease;
    }

    .mobile-menu.active {
      transform: translateX(0);
    }

    .mobile-overlay {
      background: rgba(0, 0, 0, 0.4);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }

    .mobile-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    /* Latar khusus mobile untuk kontras yang lebih baik */
    @media (max-width: 768px) {
      .navbar {
        background: #000000 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
      }
    }

    /* Pastikan menu mobile berada di atas konten halaman */
    .navbar {
      position: relative;
      z-index: 100;
    }

    #navOverlay {
      z-index: 10000;
    }

    #navMobileMenu {
      z-index: 10001;
    }
  </style>
  <div class="max-w-[1200px] mx-auto px-6 py-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div
          style="
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ffffff, rgba(255, 255, 255, 0.7));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #000;
          "
        >
          R
        </div>
        <a href="/" class="font-bold text-white text-xl">Readify</a>
      </div>

      <!-- Desktop Nav -->
      <div class="navbar-links hidden md:flex items-center gap-8">
        <a class="nav-link" href="/">Home</a>
        <a class="nav-link" href="{{ route("books.catalog") }}">Catalog</a>
        <a class="nav-link" href="{{ route("borrow.portal") }}">Borrow</a>
        @if (session("is_admin"))
          <a class="nav-link" href="{{ route("books.index") }}">Books</a>
          <a class="nav-link" href="{{ route("categories.index") }}">Categories</a>
          <a class="nav-link" href="{{ route("borrowings.index") }}">Borrowings</a>
          <form action="{{ route("admin.logout") }}" method="POST">
            @csrf
            <button class="nav-link" style="background: none; border: none; padding: 0">
              Logout
            </button>
          </form>
        @else
          <a class="nav-link" href="{{ route("admin.login") }}">Admin Login</a>
        @endif
      </div>

      <!-- Mobile button -->
      <button type="button" class="md:hidden text-white" aria-label="Open menu" onclick="(function(){ const m=document.getElementById('navMobileMenu'); const o=document.getElementById('navOverlay'); m&&m.classList.add('active'); o&&o.classList.add('active'); try{document.body.style.overflow='hidden';}catch(e){} })()">
        <svg
          width="28"
          height="28"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <line
            x1="3"
            y1="6"
            x2="21"
            y2="6"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
          />
          <line
            x1="3"
            y1="12"
            x2="21"
            y2="12"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
          />
          <line
            x1="3"
            y1="18"
            x2="21"
            y2="18"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
          />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile overlay -->
  <div id="navOverlay" class="fixed inset-0 mobile-overlay md:hidden" onclick="(function(){ const m=document.getElementById('navMobileMenu'); const o=document.getElementById('navOverlay'); m&&m.classList.remove('active'); o&&o.classList.remove('active'); try{document.body.style.overflow='';}catch(e){} })()"></div>
  <!-- Mobile Menu -->
  <div id="navMobileMenu" class="mobile-menu fixed top-0 right-0 h-full w-72 md:hidden" style="background: rgba(0,0,0,0.65); backdrop-filter: blur(14px); border-left: 1px solid rgba(255,255,255,0.15);">
    <div class="flex items-center justify-between px-5 h-16">
      <span class="text-white font-semibold">Menu</span>
      <button class="text-white" aria-label="Close menu" onclick="(function(){ const m=document.getElementById('navMobileMenu'); const o=document.getElementById('navOverlay'); m&&m.classList.remove('active'); o&&o.classList.remove('active'); try{document.body.style.overflow='';}catch(e){} })()">
        <svg
          width="22"
          height="22"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M6 6L18 18M6 18L18 6"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
          />
        </svg>
      </button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3">
      <a class="nav-link" href="/" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Home</a>
      <a class="nav-link" href="{{ route('books.catalog') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Catalog</a>
      <a class="nav-link" href="{{ route('borrow.portal') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Borrow</a>
      @if (session("is_admin"))
        <a class="nav-link" href="{{ route('books.index') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Books</a>
        <a class="nav-link" href="{{ route('categories.index') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Categories</a>
        <a class="nav-link" href="{{ route('borrowings.index') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Borrowings</a>
        <form action="{{ route("admin.logout") }}" method="POST">
          @csrf
          <button
            class="nav-link"
            style="text-align: left; background: none; border: none; padding: 0"
          >
            Logout
          </button>
        </form>
      @else
        <a class="nav-link" href="{{ route('admin.login') }}" onclick="document.getElementById('navOverlay').click(); try{document.body.style.overflow='';}catch(e){}">Admin Login</a>
      @endif
    </div>
  </div>
</nav>
