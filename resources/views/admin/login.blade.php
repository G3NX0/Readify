<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .gradient-bg { background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%); }
      .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
    </style>
  </head>
  <body class="min-h-screen gradient-bg flex items-center justify-center p-6">
    <div class="glass w-full max-w-sm rounded-xl p-6 text-white">
      <h1 class="text-xl font-semibold mb-2">Admin Login</h1>
      <p class="text-sm text-gray-300 mb-4">Masukkan password admin untuk mengakses halaman khusus admin.</p>
      @if($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">
          {{ $errors->first() }}
        </div>
      @endif
      <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="intended" value="{{ $intended }}" />
        <div>
          <label class="block text-sm mb-1">Admin Password</label>
          <input type="password" name="password" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div class="flex items-center justify-between">
          <a href="/" class="text-sm text-gray-300 hover:text-white">← Kembali</a>
          <button class="rounded bg-white px-4 py-2 text-black font-semibold">Login</button>
        </div>
      </form>
    </div>
  </body>
 </html>

