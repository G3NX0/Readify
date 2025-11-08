<nav class="navbar">
  <div style="max-width: 1200px; margin: 0 auto; padding: 16px 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #ffffff, rgba(255,255,255,0.7)); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #000;">R</div>
        <a href="/" style="font-size: 24px; font-weight: 700; color: #fff; text-decoration:none;">Readify</a>
      </div>
      <div class="navbar-links" style="display: flex; align-items: center; gap: 32px;">
        <a class="nav-link" href="/">Home</a>
        <a class="nav-link" href="{{ route('books.catalog') }}">Catalog</a>
        <a class="nav-link" href="{{ route('borrow.portal') }}">Borrow</a>
        @if(session('is_admin'))
          <a class="nav-link" href="{{ route('books.index') }}">Books</a>
          <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
          <a class="nav-link" href="{{ route('borrowings.index') }}">Borrowings</a>
          <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="nav-link" style="background:none; border:none; padding:0;">Logout</button>
          </form>
        @else
          <a class="nav-link" href="{{ route('admin.login') }}">Admin Login</a>
        @endif
      </div>
    </div>
  </div>
</nav>

