@extends('layouts.base', ['title' => 'Login'])

@section('content')
  <style>
    .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
  </style>
  <div class="min-h-[60vh] grid place-items-center">
    <div class="glass w-full max-w-sm rounded-xl p-6 text-white">
      <h1 class="text-xl font-semibold mb-2">Masuk</h1>
      <p class="text-sm text-gray-300 mb-4">Silakan login untuk melanjutkan.</p>

      @if($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Password</label>
          <div class="relative">
            <input id="loginPassword" type="password" name="password" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2 pr-10" required />
            <button
              type="button"
              aria-label="Show password"
              onclick="(function(){ const i=document.getElementById('loginPassword'); const e=document.getElementById('toggleEye'); if(!i||!e) return; const show=i.type==='password'; i.type = show ? 'text' : 'password'; e.className = show ? 'bi bi-eye-slash' : 'bi bi-eye'; })()"
              class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white"
            >
              <i id="toggleEye" class="bi bi-eye"></i>
            </button>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <a href="{{ route('register') }}" class="text-sm text-gray-300 hover:text-white">Buat akun</a>
          <button class="rounded bg-white px-4 py-2 text-black font-semibold">Login</button>
        </div>
      </form>
    </div>
  </div>
@endsection
