<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Borrowing System - Readify</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { box-sizing: border-box; margin: 0; padding: 0; height: 100%; }
    html { height: 100%; }
    .gradient-bg { background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%); }
    .glass-effect { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
    .glow-effect { box-shadow: 0 0 30px rgba(255, 255, 255, 0.1); }
    .hover-glow:hover { box-shadow: 0 0 40px rgba(255, 255, 255, 0.2); transform: translateY(-2px); transition: all 0.3s ease; }
    .text-glow { text-shadow: 0 0 20px rgba(255, 255, 255, 0.3); }
    .form-card { background: linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.04)); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.15); transition: all 0.3s ease; }
    .form-card:hover { background: linear-gradient(145deg, rgba(255,255,255,0.1), rgba(255,255,255,0.06)); box-shadow: 0 10px 40px rgba(255,255,255,0.15); }
    .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); transition: all 0.3s ease; color:#fff; color-scheme: dark; }
    .input-field:focus { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.4); outline: none; }
    .input-field option { background:#0b0b0b; color:#fff; }
    .submit-button { background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%); color: #000000; border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 0 30px rgba(255, 255, 255, 0.2); transition: all 0.3s ease; }
    .submit-button:hover:not(:disabled) { background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); box-shadow: 0 0 50px rgba(255,255,255,0.4); transform: translateY(-2px); }
    .record-card { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s ease; }
    .record-card:hover { background: linear-gradient(145deg, rgba(255,255,255,0.08), rgba(255,255,255,0.05)); box-shadow: 0 8px 32px rgba(255,255,255,0.1); transform: translateY(-2px); }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-borrowed { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .status-returned { background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
    .navbar { position: static; z-index: 50; }
    .nav-link { position: relative; color: #fff; text-decoration: none; font-weight: 500; transition: color 0.3s ease; }
    .nav-link::after { content: ""; position: absolute; left: 0; bottom: -4px; height: 2px; width: 0%; background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.8), rgba(255,255,255,0.1)); transition: width 0.3s ease; }
    .nav-link:hover { color: rgba(255,255,255,0.85); }
    .nav-link:hover::after { width: 100%; }
    /* Scrollable list styling */
    .scrollable-list { max-height: 65vh; overflow-y: auto; padding-right: 6px; }
    .scrollable-list::-webkit-scrollbar { width: 8px; }
    .scrollable-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 8px; }
    .scrollable-list::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    @media (max-width: 768px) {
      .navbar-links { display: none !important; }
      .main-grid { grid-template-columns: 1fr !important; gap: 20px !important; }
      .form-grid { grid-template-columns: 1fr !important; }
      .record-header { flex-direction: column !important; align-items: flex-start !important; gap: 12px !important; }
      .record-actions { align-self: stretch !important; justify-content: space-between !important; }
    }
  </style>
 </head>
 <body>
  <div class="min-h-full gradient-bg">
    <!-- Navigation (not sticky/fixed) -->
    @include('partials.navbar')

    <!-- Header -->
    <header style="padding: 40px 24px 20px; text-align: center; position: relative;">
      <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 20px;">
          <div style="display:flex; justify-content: space-between; align-items:center; max-width: 800px; margin: 0 auto 8px;">
            <a href="/" style="color:#ccc; text-decoration:none;">← Back</a>
            @if(session('is_admin'))
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button style="background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; padding:6px 12px; border-radius:6px;">Logout</button>
              </form>
            @else
              <a href="{{ route('admin.login') }}" style="color:#fff; text-decoration:none;">Admin Login</a>
            @endif
          </div>
          <h1 class="text-glow" style="font-size: 40px; font-weight: 800; color: #fff; margin: 0 0 8px 0;">Book Borrowing System</h1>
          <p style="font-size: 18px; color: #888; margin: 0;">Manage book borrowing and returns efficiently</p>
          @if(session('status'))
            <div style="margin-top:12px" class="glass-effect" >
              <div style="padding:10px; border-radius:8px; color:#d1fae5;">{{ session('status') }}</div>
            </div>
          @endif
        </div>
      </div>
      <div style="position: absolute; top: 10%; left: 20%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
      <div style="position: absolute; top: 30%; right: 15%; width: 150px; height: 150px; background: radial-gradient(circle, rgba(255, 255, 255, 0.02) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
    </header>

    <!-- Main Content -->
    <main style="padding: 20px 24px 60px; max-width: 1200px; margin: 0 auto;">
      @if(session('status'))
        <div class="mb-6 rounded border border-green-200 bg-green-50 p-3 text-green-800">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-6 rounded border border-red-300 bg-red-50 p-3 text-red-800">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="main-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px; align-items: start;">
        <!-- Borrowing Form (public can borrow) -->
        <div class="form-card glow-effect" style="padding: 32px; border-radius: 20px;">
          <div style="margin-bottom: 24px;">
            <h2 style="font-size: 28px; font-weight: 700; color: #fff; margin: 0 0 8px 0;">Borrow a Book</h2>
            <p style="color: #888; font-size: 16px; margin: 0;">Fill out the form below to borrow a book</p>
          </div>
          <form action="{{ route('borrowings.store') }}" method="POST" class="flex flex-col gap-5">
            @csrf
            <div>
              <label class="block text-sm font-medium" style="color:#fff">Book Category *</label>
              <select id="categorySelect" class="input-field mt-2 w-full rounded px-3 py-3" style="background: rgba(255,255,255,0.05)" required>
                <option value="">Select a category...</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium" style="color:#fff">Book Title *</label>
              <select id="bookSelect" name="book_id" class="input-field mt-2 w-full rounded px-3 py-3" required>
                <option value="">Select a book...</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium" style="color:#fff">Author</label>
              <input id="authorField" class="input-field mt-2 w-full rounded px-3 py-3" placeholder="Author will fill automatically" readonly />
            </div>
            <div>
              <label class="block text-sm font-medium" style="color:#fff">Borrower Name *</label>
              <input name="borrower_name" class="input-field mt-2 w-full rounded px-3 py-3" placeholder="Enter your name" required />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium" style="color:#fff">Borrow Date *</label>
                <input type="date" name="borrowed_at" id="borrowDate" class="input-field mt-2 w-full rounded px-3 py-3" required />
              </div>
              <div>
                <label class="block text-sm font-medium" style="color:#fff">Expected Return Date *</label>
                <input type="date" name="due_date" id="returnDate" class="input-field mt-2 w-full rounded px-3 py-3" required />
              </div>
            </div>
            <button class="submit-button hover-glow rounded px-4 py-3 font-semibold" type="submit">Borrow Book</button>
          </form>
        </div>

        <!-- Records List -->
        <div>
          <div style="margin-bottom: 24px;">
            <h2 style="font-size: 28px; font-weight: 700; color: #fff; margin: 0 0 8px 0;">Borrowing Records</h2>
            <p style="color: #888; font-size: 16px; margin: 0;">Track all borrowed books and their status</p>
          </div>

          <!-- Scrollable list container -->
          <div class="scrollable-list">
          @forelse($borrowings as $borrowing)
            <div class="record-card" style="padding: 24px; border-radius: 12px; margin-bottom: 16px;">
              <div class="record-header" style="display:flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div style="flex:1">
                  <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px; flex-wrap:wrap;">
                    <h3 style="color:#fff; font-size:18px; font-weight:600; margin:0;">{{ $borrowing->book->title }}</h3>
                    <span style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                      {{ $borrowing->book->category->name ?? '—' }}
                    </span>
                    @php $fine = $borrowing->calculateFine((int)config('borrowing.fine_per_day',10000)); @endphp
                    @if($fine > 0 && !$borrowing->returned_at)
                      <span style="background: rgba(220, 53, 69, 0.2); color:#dc3545; padding:2px 8px; border-radius:12px; font-size:11px; font-weight:500; border:1px solid rgba(220,53,69,0.3);">OVERDUE</span>
                    @endif
                  </div>
                  <p style="color: rgba(255,255,255,0.6); font-size:14px; margin:0;">Borrowed by: {{ $borrowing->borrower_name }}</p>
                  @if($borrowing->book->author)
                    <p style="color: rgba(255,255,255,0.6); font-size:14px; margin:2px 0 0 0;">Author: {{ $borrowing->book->author }}</p>
                  @endif
                  @if($fine > 0 && !$borrowing->returned_at)
                    <p style="color:#dc3545; font-size:14px; margin:4px 0 0 0; font-weight:600;">Fine: Rp{{ number_format($fine,0,',','.') }}</p>
                  @endif
                </div>
                <div class="record-actions" style="display:flex; align-items:center; gap:12px;">
                  <span class="status-badge {{ $borrowing->returned_at ? 'status-returned' : 'status-borrowed' }}">{{ $borrowing->returned_at ? 'returned' : 'borrowed' }}</span>
                  @if(!$borrowing->returned_at && session('is_admin'))
                    <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                      @csrf
                      <button class="hover-glow" style="background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; padding:8px 16px; border-radius:8px; font-size:12px; cursor:pointer; transition:all 0.3s;">Mark Returned</button>
                    </form>
                  @endif
                </div>
              </div>
              <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; padding-top:16px; border-top:1px solid rgba(255,255,255,0.1);">
                <div>
                  <p style="color: rgba(255,255,255,0.5); font-size:12px; margin:0 0 4px 0; text-transform:uppercase; letter-spacing:0.5px;">Borrow Date</p>
                  <p style="color: rgba(255,255,255,0.85); font-size:14px; margin:0;">{{ optional($borrowing->borrowed_at)->format('Y-m-d') }}</p>
                </div>
                <div>
                  <p style="color: rgba(255,255,255,0.5); font-size:12px; margin:0 0 4px 0; text-transform:uppercase; letter-spacing:0.5px;">Return Date</p>
                  <p style="color: rgba(255,255,255,0.85); font-size:14px; margin:0;">{{ optional($borrowing->due_date)->format('Y-m-d') }}</p>
                </div>
              </div>
            </div>
          @empty
            <div class="glass-effect" style="padding: 40px; border-radius: 16px; text-align: center;">
              <div style="margin-bottom: 16px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.5;">
                  <path d="M4 19.5C4 18.1193 5.11929 17 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M6.5 2H20V22H6.5C5.11929 22 4 20.8807 4 19.5V4.5C4 3.11929 5.11929 2 6.5 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <p style="color: rgba(255,255,255,0.7); font-size: 16px; margin: 0;">No borrowing records yet. Start by borrowing your first book!</p>
            </div>
          @endforelse
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Build a category -> books map from server data
    const categoryBooks = @json($categoryBooks);
    const unavailableBookIds = @json($unavailableBookIds);

    const categorySelect = document.getElementById('categorySelect');
    const bookSelect = document.getElementById('bookSelect');
    const borrowDateInput = document.getElementById('borrowDate');
    const returnDateInput = document.getElementById('returnDate');
    const preCat = @json((int) request('category_id'));
    const preBook = @json((int) request('book_id'));

    function populateBooks() {
      const categoryId = categorySelect.value;
      bookSelect.innerHTML = '<option value="">Select a book...</option>';
      if (!categoryId || !categoryBooks[categoryId]) return;
      categoryBooks[categoryId].forEach(b => {
        const opt = document.createElement('option');
        opt.value = b.id; opt.textContent = b.title; opt.dataset.author = b.author || '';
        if (unavailableBookIds.includes(b.id)) { opt.disabled = true; opt.textContent += ' (Unavailable)'; }
        bookSelect.appendChild(opt);
      });
    }

    categorySelect && categorySelect.addEventListener('change', populateBooks);

    bookSelect && bookSelect.addEventListener('change', function(){
      const selected = this.options[this.selectedIndex];
      const author = selected ? (selected.dataset.author || '') : '';
      const field = document.getElementById('authorField');
      if (field) field.value = author;
    });

    // Set default dates: today and +14 days
    (function setDefaultDates(){
      const today = new Date();
      const yyyy = today.getFullYear();
      const mm = String(today.getMonth()+1).padStart(2,'0');
      const dd = String(today.getDate()).padStart(2,'0');
      const todayStr = `${yyyy}-${mm}-${dd}`;
      const ret = new Date(today); ret.setDate(ret.getDate()+14);
      const ryyyy = ret.getFullYear(); const rmm = String(ret.getMonth()+1).padStart(2,'0'); const rdd = String(ret.getDate()).padStart(2,'0');
      const retStr = `${ryyyy}-${rmm}-${rdd}`;
      if (borrowDateInput) borrowDateInput.value = todayStr;
      if (returnDateInput) returnDateInput.value = retStr;
    })();

    // Preselect category/book if provided via query string
    (function preselect(){
      if (!categorySelect) return;
      if (preCat) {
        categorySelect.value = String(preCat);
        populateBooks();
        if (preBook) {
          bookSelect.value = String(preBook);
          const selected = bookSelect.options[bookSelect.selectedIndex];
          const field = document.getElementById('authorField');
          if (field && selected) field.value = selected.dataset.author || '';
        }
      }
    })();
  </script>
 </body>
 </html>
