<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Error</title>
    <style>
      html, body { height: 100%; margin: 0; }
      body { background: #0b0b0b; color: #e5e7eb; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif; display: grid; place-items: center; }
      .card { max-width: 860px; width: 92%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); border-radius: 16px; padding: 20px; }
      .muted { color: #9ca3af; }
      pre { white-space: pre-wrap; word-break: break-word; background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.12); padding: 12px; border-radius: 10px; color: #d1d5db; }
      h1 { margin: 0 0 8px 0; font-size: 18px; }
      p { margin: 8px 0; }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Terjadi kesalahan aplikasi</h1>
      <p class="muted">Silakan coba lagi. Jika terus berlanjut, hubungi admin.</p>
      <hr style="border-color: rgba(255,255,255,0.1); margin: 12px 0;" />
      <p><strong>Pesan:</strong> {{ $e->getMessage() }}</p>
      <p class="muted">Di: {{ $e->getFile() }}:{{ $e->getLine() }}</p>
      @if (config('app.debug'))
        <details open>
          <summary class="muted">Stack trace</summary>
          <pre>{{ $e->getTraceAsString() }}</pre>
        </details>
      @endif
    </div>
  </body>
 </html>

