<!-- Splash Screen: Readify -->
<style>
  .rf-splash {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: grid;
    place-items: center;
    background: radial-gradient(1200px 600px at 50% 20%, rgba(255,255,255,0.08), transparent),
                linear-gradient(135deg, #000000 0%, #0f0f10 50%, #000000 100%);
    color: #fff;
    overflow: hidden;
  }
  .rf-splash.hidden { display: none; }
  .rf-orb {
    width: 96px; height: 96px;
    border-radius: 9999px;
    display: grid; place-items: center;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.9), rgba(255,255,255,0.5) 40%, rgba(255,255,255,0.2) 65%, rgba(255,255,255,0.08) 100%);
    box-shadow: 0 0 40px rgba(255,255,255,0.25), inset 0 0 30px rgba(0,0,0,0.35);
    transform: scale(0.9);
    animation: rf-pop 900ms ease-out forwards;
  }
  .rf-orb span { font-weight: 800; font-size: 38px; color: #111; letter-spacing: 1px; }
  .rf-title { margin-top: 18px; font-size: 22px; font-weight: 800; letter-spacing: 0.12em; opacity: 0; animation: rf-fade 800ms ease-out 300ms forwards; }
  .rf-beam {
    position: absolute; inset: 0; pointer-events: none; mix-blend-mode: screen;
    background: conic-gradient(from 180deg at 50% 50%, rgba(255,255,255,0.18), transparent 35%, rgba(255,255,255,0.12) 55%, transparent 75%, rgba(255,255,255,0.18));
    filter: blur(18px); opacity: 0.0; animation: rf-sweep 1200ms ease-out forwards;
  }
  @keyframes rf-pop { 0% { transform: scale(0.8); opacity: 0; } 60% { transform: scale(1.06); opacity: 1; } 100% { transform: scale(1.0); opacity: 1; } }
  @keyframes rf-fade { to { opacity: 0.9; } }
  @keyframes rf-sweep { 0% { transform: rotate(-20deg); opacity: 0; } 100% { transform: rotate(0deg); opacity: 0.35; } }
  @media (prefers-reduced-motion: reduce) {
    .rf-orb { animation: none; }
    .rf-title, .rf-beam { animation: none; opacity: 1; }
  }
</style>

<div id="rfSplash" class="rf-splash hidden" aria-hidden="true">
  <div class="rf-beam"></div>
  <div style="display:flex; flex-direction:column; align-items:center;">
    <div class="rf-orb"><span>R</span></div>
    <div class="rf-title">READIFY</div>
  </div>
  <svg width="0" height="0" style="position:absolute">
    <defs>
      <filter id="rf-noise">
        <feTurbulence type="fractalNoise" baseFrequency="0.8" numOctaves="2" stitchTiles="stitch"/>
        <feColorMatrix type="saturate" values="0"/>
        <feComponentTransfer>
          <feFuncA type="table" tableValues="0 0 0 0 0.12 0.2 0.12 0 0"/>
        </feComponentTransfer>
      </filter>
    </defs>
  </svg>
</div>

<script>
  (function(){
    try {
      var onceKey = 'rf_splash_seen';
      var splash = document.getElementById('rfSplash');
      if (!splash) return;
      var seen = sessionStorage.getItem(onceKey);
      if (seen === '1') return; // sudah ditampilkan di sesi ini
      splash.classList.remove('hidden');
      splash.removeAttribute('aria-hidden');
      setTimeout(function(){
        splash.style.transition = 'opacity 420ms ease';
        splash.style.opacity = '0';
        setTimeout(function(){ splash.classList.add('hidden'); splash.style.opacity = ''; }, 440);
        sessionStorage.setItem(onceKey, '1');
      }, 2000);
    } catch (e) { /* no-op */ }
  })();
</script>
