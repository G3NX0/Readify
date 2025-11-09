@extends('layouts.base', ['title' => 'Register'])

@section('content')
  <style>
    .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); backdrop-filter: blur(16px); }
  </style>
  <div class="min-h-[60vh] grid place-items-center">
    <div class="glass w-full max-w-sm rounded-xl p-6 text-white">
      <h1 class="text-xl font-semibold mb-2">Daftar</h1>
      <p class="text-sm text-gray-300 mb-4">Buat akun untuk mulai menggunakan Readify.</p>

      @if($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-800">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm mb-1">Nama</label>
          <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Username (opsional)</label>
          <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Password</label>
          <input type="password" name="password" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div>
          <label class="block text-sm mb-1">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="w-full rounded border border-gray-500 bg-transparent px-3 py-2" required />
        </div>
        <div class="flex items-center justify-between">
          <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white">Sudah punya akun?</a>
          <button class="rounded bg-white px-4 py-2 text-black font-semibold">Daftar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
