<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome · Readify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .gradient-bg {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
      }
      .glass {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
      }
      .cta {
        background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        color: #000;
      }
      .cta:hover {
        filter: brightness(0.95);
      }
    </style>
  </head>
  <body class="min-h-screen gradient-bg">
    @include("partials.splash")
    <main class="min-h-screen grid place-items-center px-6">
      <div class="glass max-w-xl w-full rounded-2xl p-8 text-white text-center">
        <h1 class="text-3xl font-extrabold mb-2">Selamat Datang di Readify</h1>
        <p class="text-gray-300 mb-6">Kelola koleksi, peminjaman, dan pengembalian buku secara rapi. Silakan masuk atau daftar dulu sebelum melanjutkan.</p>

        <div class="flex items-center justify-center gap-3">
          <a href="{{ route('login') }}" class="cta px-5 py-2 rounded-lg font-semibold">Login</a>
          <a href="{{ route('register') }}" class="border border-white/30 px-5 py-2 rounded-lg font-semibold hover:bg-white/10">Register</a>
        </div>
      </div>
    </main>
  </body>
 </html>
