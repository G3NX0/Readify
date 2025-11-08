<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Readify' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .gradient-bg { background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%); }
      .nav-link { position: relative; color:#fff; text-decoration:none; font-weight:500; transition: color .3s ease; }
      .nav-link::after { content:""; position:absolute; left:0; bottom:-4px; height:2px; width:0%; background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.8), rgba(255,255,255,0.1)); transition: width .3s ease; }
      .nav-link:hover { color: rgba(255,255,255,.85); }
      .nav-link:hover::after { width:100%; }
      /* Reusable glass + form styles */
      .glass-panel { background: linear-gradient(145deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03)); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(14px); border-radius: 16px; }
      .field-label { color: rgba(255,255,255,0.85); font-size: .9rem; margin-bottom: 6px; display:block; }
      .input-dark, .select-dark, .textarea-dark { width:100%; color:#fff; background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.2); border-radius: 10px; padding: 10px 12px; transition: border-color .2s ease; }
      .input-dark:focus, .select-dark:focus, .textarea-dark:focus { outline: none; border-color: rgba(255,255,255,0.4); }
      /* Ensure all dropdowns share the same tone */
      .select-dark { color-scheme: dark; }
      .select-dark option { background:#0b0b0b; color:#fff; }
      .btn-white { background: #fff; color:#000; font-weight:600; padding: 10px 14px; border-radius: 10px; }
      .btn-white:hover { filter: brightness(.95); }
      .btn-outline { border:1px solid rgba(255,255,255,0.35); color:#fff; padding: 10px 14px; border-radius: 10px; }
      .btn-outline:hover { background: rgba(255,255,255,0.06); }
    </style>
    <link 
      rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
  </head>
  <body class="min-h-screen gradient-bg">
    <header>
      @include('partials.navbar')
    </header>
    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
      @if(session('status'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800">{{ session('status') }}</div>
      @endif
      {{ $slot ?? '' }}
      @yield('content')
    </main>
  </body>
  </html>
