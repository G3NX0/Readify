@extends('layouts.base', ['title' => 'Tech Stack & Fitur'])

@section('content')
  <style>
    .glass-card { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius: 16px; backdrop-filter: blur(14px); }
    .horizontal-card { display:flex; flex-direction:column; gap:1rem; }
    @media (min-width: 640px) { .horizontal-card { flex-direction:row; align-items:flex-start; } }
    .card-body { flex:1; }
    .card-detail { min-width: 220px; }
  </style>

  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-semibold text-white">Tech Stack & Fitur</h1>
      <p class="text-gray-300">Ringkasan formal dan informal tentang apa yang membangun Readify.</p>
    </div>

    <div class="glass-card p-6 space-y-4">
      <h2 class="text-lg text-white font-semibold">Bahasa & Tools</h2>
      @foreach ($languages as $lang)
        <div class="glass-card p-4 horizontal-card">
          <div class="card-body">
            <div class="text-white font-semibold mb-1">{{ $lang['title'] }}</div>
            <p class="text-sm text-white/85 leading-relaxed">{{ $lang['description'] }}</p>
          </div>
        </div>
      @endforeach
    </div>

    <div class="glass-card p-6 space-y-4">
      <h2 class="text-lg text-white font-semibold">Fitur</h2>
      @foreach ($features as $feat)
        <div class="glass-card p-4 horizontal-card">
          <div class="card-body">
            <div class="text-white font-semibold mb-1">{{ $feat['title'] }}</div>
            <p class="text-sm text-white/85 leading-relaxed">{{ $feat['description'] }}</p>
          </div>
          @if (!empty($feat['details']))
            <div class="card-detail">
              <ul class="list-disc text-sm text-white/70 ml-5 space-y-1">
                @foreach ($feat['details'] as $line)
                  <li>{{ $line }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
@endsection
