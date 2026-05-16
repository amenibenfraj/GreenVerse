<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Astuce du jour - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Inter', sans-serif;
  background: #fff;
  color: #1e2d1e;
  min-height: 100vh;
}

/* ── Hero simple ── */
.astuce-hero {
  padding: 60px 24px 40px;
  text-align: center;
}

.astuce-hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  color: #1b4332;
  font-weight: 600;
  margin-bottom: 8px;
}

.astuce-hero p {
  color: #7a9e82;
  font-size: .9rem;
  font-weight: 300;
}

/* ── Refresh ── */
.refresh-bar {
  display: flex;
  justify-content: center;
  padding: 0 24px 36px;
}

.btn-refresh {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #fff;
  color: #2d6a4f;
  border: 1.5px solid #2d6a4f;
  border-radius: 8px;
  padding: 9px 22px;
  font-family: 'Inter', sans-serif;
  font-size: .83rem;
  font-weight: 500;
  cursor: pointer;
  transition: background .2s, color .2s;
}

.btn-refresh:hover { background: #2d6a4f; color: #fff; }
.btn-refresh svg { width: 14px; height: 14px; }
.btn-refresh.loading svg { animation: spin .8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Cartes côte à côte ── */
.astuce-row {
  display: flex;
  flex-wrap: nowrap;
  gap: 16px;
  overflow-x: auto;
  padding: 0 40px 60px;
  max-width: 1200px;
  margin: 0 auto;
  scrollbar-width: thin;
  scrollbar-color: #d8f0e0 transparent;
}

.astuce-row::-webkit-scrollbar { height: 4px; }
.astuce-row::-webkit-scrollbar-thumb { background: #b7e4c7; border-radius: 4px; }

/* ── Card ── */
.astuce-card {
  background: #f6fbf7;
  border: 1px solid #d8f0e0;
  border-radius: 16px;
  padding: 28px 24px;
  min-width: 230px;
  flex: 1 0 220px;
  opacity: 0;
  transform: translateY(14px);
  transition: opacity .4s ease, transform .4s ease, box-shadow .2s, background .2s;
}

.astuce-card.visible {
  opacity: 1;
  transform: translateY(0);
}

.astuce-card:nth-child(1) { transition-delay: .04s; }
.astuce-card:nth-child(2) { transition-delay: .11s; }
.astuce-card:nth-child(3) { transition-delay: .18s; }
.astuce-card:nth-child(4) { transition-delay: .25s; }

.astuce-card:hover {
  background: #edf7f0;
  box-shadow: 0 6px 20px rgba(45,106,79,.1);
}

.card-icon {
  font-size: 2rem;
  margin-bottom: 14px;
  display: block;
}

.card-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem;
  font-weight: 600;
  color: #1b4332;
  margin-bottom: 8px;
  line-height: 1.3;
}

.card-body {
  font-size: .84rem;
  font-weight: 300;
  color: #4a6551;
  line-height: 1.7;
}

/* ── Skeleton ── */
.sk-card {
  background: #f6fbf7;
  border: 1px solid #d8f0e0;
  border-radius: 16px;
  padding: 28px 24px;
  min-width: 230px;
  flex: 1 0 220px;
}

.sk-block {
  background: #ddf0e4;
  border-radius: 6px;
  margin-bottom: 10px;
  animation: pulse 1.5s ease-in-out infinite;
}

.sk-block.icon  { width: 36px; height: 36px; border-radius: 50%; margin-bottom: 16px; }
.sk-block.title { width: 55%; height: 14px; margin-bottom: 12px; }
.sk-block.l1    { width: 100%; height: 10px; }
.sk-block.l2    { width: 80%;  height: 10px; }
.sk-block.l3    { width: 60%;  height: 10px; animation-delay: .2s; }

@keyframes pulse {
  0%,100% { opacity: 1; }
  50%      { opacity: .35; }
}

/* ── Error ── */
.error-full {
  text-align: center;
  padding: 48px 24px;
  color: #c0392b;
  font-size: .88rem;
  width: 100%;
}

.error-full button {
  margin-top: 12px;
  display: inline-block;
  background: none;
  border: 1px solid #c0392b;
  color: #c0392b;
  border-radius: 8px;
  padding: 8px 20px;
  cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-size: .82rem;
}

/* ── Footer ── */
footer {
  text-align: center;
  padding: 20px;
  color: #a8c4ad;
  font-size: .78rem;
  border-top: 1px solid #e8f5ec;
}
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="astuce-hero">
  <h1>Astuces du jardin</h1>
  <p>Conseils générés par l'IA à chaque visite</p>
</section>

<!-- Bouton -->
<div class="refresh-bar">
  <button class="btn-refresh" id="btnRefresh" onclick="loadAstuces()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M23 4v6h-6M1 20v-6h6"/>
      <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
    </svg>
    Nouvelles astuces
  </button>
</div>

<!-- CARTES CÔTE À CÔTE -->
<div class="astuce-row" id="timeline"></div>

<footer>© 2025 GreenVerse</footer>

<script>
  const fallbackEmojis = ['🌿','💧','🌱','🪴','🌸','🍂','☀️','🌍'];

  function skeletonHTML() {
    return Array.from({length: 4}, () => `
      <div class="sk-card">
        <div class="sk-block icon"></div>
        <div class="sk-block title"></div>
        <div class="sk-block l1"></div>
        <div class="sk-block l2"></div>
        <div class="sk-block l3"></div>
      </div>`).join('');
  }

  function cardHTML(a, i) {
    const emoji = a.emoji || fallbackEmojis[i % fallbackEmojis.length];
    return `
      <div class="astuce-card">
        <span class="card-icon">${emoji}</span>
        <h2 class="card-title">${escHtml(a.titre)}</h2>
        <p class="card-body">${escHtml(a.astuce)}</p>
      </div>`;
  }

  function escHtml(str) {
    return String(str)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;')
      .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  async function loadAstuces() {
    const timeline = document.getElementById('timeline');
    const btn = document.getElementById('btnRefresh');
    btn.classList.add('loading');
    btn.disabled = true;
    timeline.innerHTML = skeletonHTML();

    try {
      const res = await fetch('generate_astuces.php');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const astuces = await res.json();
      if (!Array.isArray(astuces) || !astuces.length) throw new Error('invalide');

      timeline.innerHTML = astuces.map(cardHTML).join('');
      requestAnimationFrame(() => {
        document.querySelectorAll('.astuce-card').forEach(el => el.classList.add('visible'));
      });
    } catch (err) {
      timeline.innerHTML = `
        <div class="error-full">
          <p>😕 Impossible de charger les astuces.</p>
          <button onclick="loadAstuces()">Réessayer</button>
        </div>`;
      console.error(err);
    } finally {
      btn.classList.remove('loading');
      btn.disabled = false;
    }
  }

  loadAstuces();
</script>
</body>
</html>
