@extends('layouts.base', ['title' => 'Catalog'])

@section('content')
  <style>
    .glass-effect { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
    .record-card { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border:1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px); transition: all .2s ease; }
    .record-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(255,255,255,0.08); }
    .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color:#fff; color-scheme: dark; }
    .input-field:focus { outline:none; border-color: rgba(255,255,255,0.4); }
    .input-field option { background:#0b0b0b; color:#fff; }
    .submit-button { background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%); color:#000; }
    .badge { background: rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:11px; padding:2px 8px; border-radius:9999px; }
    .unavailable { color:#f87171; border-color: rgba(248,113,113,0.3); background: rgba(248,113,113,0.15); }
    /* Horizontal scroll row styling (match borrow scrollbar tone) */
    .scroll-row { display:flex; gap:16px; overflow-x:auto; overflow-y:hidden; padding-bottom: 12px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-gutter: stable both-edges; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.15) rgba(255,255,255,0.06); touch-action: pan-x; overscroll-behavior-x: contain; will-change: scroll-position; }
    .scroll-row > a { scroll-snap-align: start; }
    .scrollbar-styled::-webkit-scrollbar { height: 8px; }
    .scrollbar-styled::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.18); border-radius: 8px; }
    .scrollbar-styled::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.24); }
    .scrollbar-styled::-webkit-scrollbar-track { background: rgba(0,0,0,0.35); border-radius: 8px; }
    @media (max-width: 768px) { .scroll-row { gap:12px; } }
    /* Arrows + fades */
    .h-scroll-wrap { position: relative; padding: 0 12px; }
    .h-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 999px; display: grid; place-items: center; color: #000; background: #fff; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 18px rgba(0,0,0,0.25); opacity: .9; z-index: 2; }
    .h-arrow:hover { filter: brightness(.95); }
    .h-arrow.left { left: 0; }
    .h-arrow.right { right: 0; }
    .fade-left, .fade-right { position: absolute; top: 0; bottom: 12px; width: 24px; pointer-events: none; z-index: 1; }
    .fade-left { left: 12px; background: linear-gradient(90deg, rgba(0,0,0,0.8), transparent); }
    .fade-right { right: 12px; background: linear-gradient(270deg, rgba(0,0,0,0.8), transparent); }
    /* Drag to scroll UX */
    .scroll-row { cursor: grab; }
    .scroll-row.dragging { cursor: grabbing; }
    .scroll-row.dragging, .scroll-row.dragging * { user-select: none !important; -webkit-user-select: none !important; }
    /* Hide arrows by default; reveal near edges */
    .h-arrow { opacity: 0; pointer-events: none; transition: opacity .15s ease, transform .15s ease; transform: translateY(-50%) scale(.92); }
    .h-scroll-wrap.edge-left .h-arrow.left,
    .h-scroll-wrap.edge-right .h-arrow.right { opacity: .95; pointer-events: auto; transform: translateY(-50%) scale(1); }
    @media (max-width: 768px) { .h-arrow { display: none; } }
  </style>

  <div class="mb-6">
    <h1 class="text-2xl font-semibold text-white">Book Catalog</h1>
    <p class="text-gray-300">Browse books by category and search quickly</p>
  </div>

  <form method="GET" class="mb-8 glass-effect rounded-xl p-4 flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[220px]">
      <label class="text-sm text-gray-300 block mb-1">Category</label>
      <select 
        name="category_id" 
        class="input-field rounded-lg px-3 py-2 w-full text-sm focus:ring-1 focus:ring-gray-400 transition"
        onchange="this.form.submit()"
      >
        <option value="">All</option>
        @foreach($filterCategories as $category)
          <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex flex-1 items-end gap-2 min-w-[280px]">
      <div class="flex-1">
        <label class="text-sm text-gray-300 block mb-1">Search</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}" 
            placeholder="Title, author, or synopsis" 
            class="input-field pl-9 pr-3 py-2 w-full rounded-lg text-sm"
          />
        </div>
      </div>

      <div class="flex gap-2">
        <button 
          type="submit" 
          class="bg-white text-black font-semibold text-sm px-4 py-2 rounded-lg hover:bg-gray-200 transition"
        >
          Go
        </button>
        <a 
          href="{{ route('books.catalog') }}" 
          class="border border-gray-500 text-gray-300 text-sm px-4 py-2 rounded-lg hover:bg-gray-800 hover:text-white transition"
        >
          Reset
        </a>
      </div>
    </div>
  </form>

  @forelse($sections as $category)
    <section class="mb-10">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-white text-xl font-semibold">{{ $category->name }}</h2>
        <span class="badge">{{ $category->books->count() }} books</span>
      </div>
      <div class="h-scroll-wrap">
        <button class="h-arrow left" aria-label="Scroll left" data-target="row-{{ $category->id }}">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div id="row-{{ $category->id }}" class="scroll-row scrollbar-styled">
        @foreach($category->books as $book)
          <a href="{{ route('books.show', $book) }}" draggable="false" class="record-card block p-5 rounded-xl text-current no-underline" style="min-width:280px;max-width:320px;">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg, rgba(255,255,255,.9), rgba(255,255,255,.6)); display:flex;align-items:center;justify-content:center; color:#000; font-weight:700;">{{ Str::substr($book->title,0,1) }}</div>
                <h3 class="text-white font-semibold text-lg">{{ $book->title }}</h3>
              </div>
              @if(in_array($book->id, $unavailableBookIds))
                <span class="badge unavailable">Unavailable</span>
              @endif
            </div>
            <p class="text-gray-300 text-sm mb-2">{{ $book->author ?? 'Unknown' }} &middot; {{ $category->name }}</p>
            <p class="text-gray-200 text-sm">{{ Str::limit($book->synopsis, 160) }}</p>
          </a>
        @endforeach
        </div>
        <button class="h-arrow right" aria-label="Scroll right" data-target="row-{{ $category->id }}">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <span class="fade-left"></span>
        <span class="fade-right"></span>
      </div>
    </section>
  @empty
    <div class="text-gray-300">No books found.</div>
@endforelse
  <script>
    (function(){
      const wraps = document.querySelectorAll('.h-scroll-wrap');
      const SNAP_MS_AFTER_TOUCH = 180;
      function snapToNearest(row){
        const cards = row.querySelectorAll('a');
        if(!cards.length) return;
        let bestLeft = 0, bestDist = Infinity;
        const current = row.scrollLeft;
        cards.forEach(card => {
          const x = card.offsetLeft;
          const d = Math.abs(x - current);
          if (d < bestDist) { bestDist = d; bestLeft = x; }
        });
        row.scrollTo({ left: bestLeft, behavior: 'smooth' });
      }
      function updateState(wrap){
        const row = wrap.querySelector('.scroll-row');
        const left = wrap.querySelector('.h-arrow.left');
        const right = wrap.querySelector('.h-arrow.right');
        const fadeL = wrap.querySelector('.fade-left');
        const fadeR = wrap.querySelector('.fade-right');
        const atStart = row.scrollLeft <= 1;
        const atEnd = (row.scrollLeft + row.clientWidth) >= (row.scrollWidth - 1);
        if (left) left.style.visibility = atStart ? 'hidden' : 'visible';
        if (right) right.style.visibility = atEnd ? 'hidden' : 'visible';
        if (fadeL) fadeL.style.opacity = atStart ? 0 : 1;
        if (fadeR) fadeR.style.opacity = atEnd ? 0 : 1;
      }
      wraps.forEach((wrap)=>{
        const row = wrap.querySelector('.scroll-row');
        const step = () => Math.max(240, Math.round(row.clientWidth * 0.8));
        wrap.querySelector('.h-arrow.left').addEventListener('click', ()=> row.scrollBy({left: -step(), behavior:'smooth'}));
        wrap.querySelector('.h-arrow.right').addEventListener('click', ()=> row.scrollBy({left: step(), behavior:'smooth'}));
        row.addEventListener('scroll', ()=> updateState(wrap));
        window.addEventListener('resize', ()=> updateState(wrap));
        updateState(wrap);

        // Drag-to-scroll (mouse + touch)
        let isDown = false, startX = 0, startY = 0, startLeft = 0, moved = false;
        let lastX = 0, lastT = 0, velocity = 0, rafId = null;
        const getX = (e) => (e.touches && e.touches[0] ? e.touches[0].clientX : e.clientX);
        const getY = (e) => (e.touches && e.touches[0] ? e.touches[0].clientY : e.clientY);
        const stopMomentum = ()=> { if (rafId) { cancelAnimationFrame(rafId); rafId = null; } velocity = 0; };
        row.addEventListener('mousedown', (e)=>{ isDown = true; moved = false; row.classList.add('dragging'); startX = getX(e); startLeft = row.scrollLeft; lastX = startX; lastT = performance.now(); stopMomentum(); e.preventDefault(); });
        // On touch devices, rely on native pan-x for smoothness
        row.addEventListener('touchstart', (e)=>{ isDown = true; moved = false; startX = getX(e); startY = getY(e); startLeft = row.scrollLeft; stopMomentum(); if (row._snapTimer) { clearTimeout(row._snapTimer); row._snapTimer = null; } }, {passive:true});
        const onMove = (e)=>{
          if(!isDown) return;
          // Use native smooth scroll on touch; do not override
          if (e.touches) return;
          const now = performance.now();
          const x = getX(e);
          const dx = x - startX;
          moved = true; e.preventDefault();
          row.scrollLeft = startLeft - dx;
          // estimate velocity (px/ms)
          const dt = Math.max(1, now - lastT);
          velocity = (x - lastX) / dt; // positive means moving right
          lastX = x; lastT = now;
        };
        row.addEventListener('mousemove', onMove);
        // Do not call preventDefault on touchmove to keep native momentum
        row.addEventListener('touchmove', (e)=>{ /* native pan-x handles */ }, {passive:true});
        const endDrag = ()=>{
          isDown = false; row.classList.remove('dragging');
          // Momentum/inertia for mouse drag only
          if (!moved || Math.abs(velocity) < 0.01) { velocity = 0; snapToNearest(row); return; }
          const friction = 0.94; // decay per frame ~60fps
          let prev = performance.now();
          const animate = (t)=>{
            const dt = Math.min(32, t - prev); // cap dt to avoid jumps
            prev = t;
            row.scrollLeft -= velocity * dt; // minus because positive velocity means drag to right
            // clamp
            if (row.scrollLeft < 0) row.scrollLeft = 0;
            const max = row.scrollWidth - row.clientWidth;
            if (row.scrollLeft > max) row.scrollLeft = max;
            updateState(wrap);
            velocity *= friction;
            if (Math.abs(velocity) >= 0.01 && row.scrollLeft > 0 && row.scrollLeft < max) {
              rafId = requestAnimationFrame(animate);
            } else {
              velocity = 0; rafId = null; snapToNearest(row);
            }
          };
          rafId = requestAnimationFrame(animate);
        };
        row.addEventListener('mouseleave', endDrag);
        row.addEventListener('mouseup', endDrag);
        row.addEventListener('touchend', ()=>{ endDrag(); row._snapTimer = setTimeout(()=>{ if(!isDown && !rafId) snapToNearest(row); }, SNAP_MS_AFTER_TOUCH); });
        // Prevent opening links when dragging
        row.querySelectorAll('a').forEach(a=>{
          a.addEventListener('click', (e)=>{ if(moved) { e.preventDefault(); e.stopPropagation(); } });
        });

        // Show arrows only when pointer near edges
        const onHover = (e) => {
          const rect = wrap.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const margin = Math.max(40, Math.round(rect.width * 0.08));
          const nearLeft = x <= margin;
          const nearRight = x >= rect.width - margin;
          wrap.classList.toggle('edge-left', nearLeft && !nearRight);
          wrap.classList.toggle('edge-right', nearRight && !nearLeft);
          if (!nearLeft && !nearRight) { wrap.classList.remove('edge-left'); wrap.classList.remove('edge-right'); }
        };
        wrap.addEventListener('mousemove', onHover);
        wrap.addEventListener('mouseleave', ()=> { wrap.classList.remove('edge-left'); wrap.classList.remove('edge-right'); });
      });
    })();
  </script>
@endsection
