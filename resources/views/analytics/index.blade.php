@extends('layouts.base', ['title' => 'Analytics'])

@section('content')
  <style>
    .glass { background: rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); backdrop-filter: blur(12px); border-radius: 16px; }
    .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color:#fff; color-scheme:dark; }
    .input-field:focus { outline:none; border-color: rgba(255,255,255,0.4); }
    .select-dark { color-scheme: dark; }
    .select-dark option { background: #0b0b0b; color:#fff; }
  </style>

  <div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Analytics</h1>
        <p class="text-gray-300">Ringkasan cepat penggunaan koleksi</p>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <label class="text-sm text-gray-300">Tampilan</label>
        <select id="metric" class="select-dark input-field rounded px-3 py-2">
          <option value="books">Buku Terbanyak Dipinjam</option>
          <option value="categories">Kategori Terbanyak Dipinjam</option>
          <option value="weekday">Hari Peminjaman Paling Sering</option>
        </select>
        <label class="text-sm text-gray-300 ml-2">Jumlah</label>
        <select id="limit" class="select-dark input-field rounded px-3 py-2">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="15">15</option>
        </select>
        <label class="text-sm text-gray-300 ml-2">Rentang</label>
        <select id="period" class="select-dark input-field rounded px-3 py-2">
          <option value="7d">7 Hari</option>
          <option value="30d" selected>30 Hari</option>
          <option value="12m">12 Bulan</option>
        </select>
        <label class="text-sm text-gray-300 ml-2">Bentuk</label>
        <select id="chartType" class="select-dark input-field rounded px-3 py-2">
          <option value="bar" selected>Kolom</option>
          <option value="line">Garis</option>
          <option value="doughnut">Donat</option>
          <option value="pie">Pie</option>
        </select>
      </div>
    </div>

    <!-- Quick stats grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
      <div class="glass p-4 rounded-xl">
        <div class="text-xs text-white/70">Total Peminjaman</div>
        <div id="s-total" class="text-2xl font-bold text-white">—</div>
      </div>
      <div class="glass p-4 rounded-xl">
        <div class="text-xs text-white/70">Buku Teratas</div>
        <div id="s-book" class="text-white">—</div>
      </div>
      <div class="glass p-4 rounded-xl">
        <div class="text-xs text-white/70">Kategori Teratas</div>
        <div id="s-cat" class="text-white">—</div>
      </div>
      <div class="glass p-4 rounded-xl">
        <div class="text-xs text-white/70">Hari Tersibuk</div>
        <div id="s-day" class="text-white">—</div>
      </div>
    </div>

    <div class="glass p-4 rounded-xl" style="min-height: 320px">
      <div style="height: 320px" class="sm:h-[360px] md:h-[420px]">
        <canvas id="chart"></canvas>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    const elMetric = document.getElementById('metric');
    const elLimit = document.getElementById('limit');
    const elType = document.getElementById('chartType');
    const elPeriod = document.getElementById('period');
    const ctx = document.getElementById('chart').getContext('2d');
    const styles = {
      grid: 'rgba(255,255,255,0.12)',
      ticks: 'rgba(255,255,255,0.8)',
      line: '#ffffff',
      fill: 'rgba(255,255,255,0.18)'
    };

    let chart = null;
    let last = { labels: [], values: [] };

    function makePalette(n) {
      const colors = [];
      for (let i = 0; i < n; i++) {
        const h = Math.floor((360 / Math.max(1, n)) * i);
        colors.push(`hsl(${h} 70% 55% / 0.85)`);
      }
      return colors;
    }

    function createChart(type, labels, values) {
      if (chart) { chart.destroy(); }
      const commonOpts = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: styles.ticks } } }
      };
      const data = { labels, datasets: [] };
      if (type === 'pie' || type === 'doughnut') {
        data.datasets.push({ label: 'Total', data: values, backgroundColor: makePalette(values.length), borderWidth: 0 });
      } else if (type === 'line') {
        data.datasets.push({ label: 'Total', data: values, borderColor: styles.line, backgroundColor: styles.fill, borderWidth: 2, tension: 0.25, fill: true, pointRadius: 3 });
      } else { // bar
        data.datasets.push({ label: 'Total', data: values, borderColor: styles.line, backgroundColor: styles.fill, borderWidth: 2 });
      }
      const options = (type === 'pie' || type === 'doughnut') ? commonOpts : {
        ...commonOpts,
        scales: {
          x: { ticks: { color: styles.ticks }, grid: { color: styles.grid } },
          y: { ticks: { color: styles.ticks }, grid: { color: styles.grid }, beginAtZero: true }
        }
      };
      chart = new Chart(ctx, { type, data, options });
    }

    function reloadChart() {
      createChart(elType.value, last.labels, last.values);
    }

    function loadSummary() {
      const u = new URL('{{ route('analytics.summary') }}', window.location.origin);
      u.searchParams.set('period', elPeriod.value);
      fetch(u).then(r => r.json()).then(j => {
        const num = (n) => (typeof n === 'number' ? n : (n ? parseInt(n, 10) : 0));
        document.getElementById('s-total').textContent = num(j.totalBorrowings).toLocaleString('id-ID');
        document.getElementById('s-book').textContent = j.topBook ? `${j.topBook.label} (${num(j.topBook.total)})` : '—';
        document.getElementById('s-cat').textContent = j.topCategory ? `${j.topCategory.label} (${num(j.topCategory.total)})` : '—';
        document.getElementById('s-day').textContent = j.topWeekday ? `${j.topWeekday.label} (${num(j.topWeekday.total)})` : '—';
      }).catch(() => {});
    }

    function loadData() {
      const url = new URL('{{ route('analytics.data') }}', window.location.origin);
      url.searchParams.set('metric', elMetric.value);
      url.searchParams.set('limit', elLimit.value);
      url.searchParams.set('period', elPeriod.value);
      fetch(url).then(r => r.json()).then(j => {
        if (j && Array.isArray(j.labels)) {
          last = { labels: j.labels, values: j.values || [] };
          reloadChart();
        }
      }).catch(() => {});
      loadSummary();
    }

    elMetric.addEventListener('change', loadData);
    elLimit.addEventListener('change', loadData);
    elType.addEventListener('change', reloadChart);
    elPeriod.addEventListener('change', loadData);
    document.addEventListener('DOMContentLoaded', () => { loadData(); });
  </script>
@endsection
