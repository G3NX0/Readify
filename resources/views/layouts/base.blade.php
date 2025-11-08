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
