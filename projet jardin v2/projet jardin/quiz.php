<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz Jardinage — GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>
/* ─── HERO ───────────────────────────────────────────────── */
.quiz-hero {
  min-height: 40vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background: linear-gradient(135deg, #f0fff0, #e6f0e6);
  padding: 80px 20px 40px;
}
.quiz-hero h1 {
  font-size: clamp(2em, 5vw, 3em);
  color: #2e7d32;
  margin-bottom: 12px;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
}
.quiz-hero p {
  font-size: 1.15em;
  color: #4a7c59;
}

/* ─── CONTENEUR PRINCIPAL ────────────────────────────────── */
.quiz-wrap {
  max-width: 720px;
  margin: 40px auto 60px;
  padding: 0 20px;
  font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ─── ÉCRAN DE DÉMARRAGE ─────────────────────────────────── */
.start-screen {
  background: #ffffff;
  border: 1px solid #d8e8d8;
  border-radius: 24px;
  padding: 36px 32px;
  box-shadow: 0 8px 24px rgba(46,125,50,0.08);
}
.start-screen h2 {
  font-size: 1.4em;
  color: #2e7d32;
  margin: 0 0 6px;
  text-align: center;
}
.start-screen .subtitle {
  text-align: center;
  color: #6a8f6a;
  font-size: 0.9em;
  margin: 0 0 28px;
}
.section-label {
  font-size: 0.78em;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #2e7d32;
  margin-bottom: 10px;
}
.pill-group {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 24px;
}
.pill-btn {
  padding: 7px 18px;
  border: 1.5px solid #b8d8b8;
  border-radius: 50px;
  background: #f4fbf4;
  color: #3a6b3a;
  font-size: 0.88em;
  cursor: pointer;
  transition: all 0.18s;
}
.pill-btn:hover {
  border-color: #2e7d32;
  background: #e8f5e9;
}
.pill-btn.active {
  background: #2e7d32;
  border-color: #2e7d32;
  color: #ffffff;
  font-weight: 600;
}

/* ─── BOUTON PRINCIPAL ───────────────────────────────────── */
.btn-main {
  display: block;
  width: 100%;
  padding: 14px;
  background: #2e7d32;
  color: #fff;
  border: none;
  border-radius: 50px;
  font-size: 1em;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, transform 0.12s;
  margin-top: 8px;
}
.btn-main:hover { background: #1b5e20; }
.btn-main:active { transform: scale(0.98); }
.btn-main:disabled { background: #a5c8a5; cursor: not-allowed; }

/* ─── SQUELETTE DE CHARGEMENT ────────────────────────────── */
.skeleton-wrap { display: flex; flex-direction: column; gap: 16px; }
.skeleton-card {
  background: #fff;
  border: 1px solid #e0ece0;
  border-radius: 20px;
  padding: 24px;
}
.sk { background: #e8f0e8; border-radius: 8px; animation: pulse 1.5s ease infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.45} }
.sk-line { height: 12px; margin-bottom: 10px; }
.sk-title { height: 18px; width: 75%; margin-bottom: 18px; }
.sk-opt { height: 40px; margin-bottom: 8px; border-radius: 12px; }
.loading-hint {
  text-align: center;
  color: #7aab7a;
  font-size: 0.85em;
  margin-top: 12px;
}

/* ─── EN-TÊTE DU QUIZ ────────────────────────────────────── */
.quiz-meta {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 20px;
}
.meta-badge {
  font-size: 0.82em;
  padding: 5px 14px;
  border: 1px solid #c8ddc8;
  border-radius: 50px;
  color: #3a6b3a;
  background: #f0faf0;
}
.meta-badge b { color: #2e7d32; font-weight: 600; }

/* ─── CARTE DE QUESTION ──────────────────────────────────── */
.q-card {
  background: #ffffff;
  border: 1px solid #d8e8d8;
  border-radius: 20px;
  padding: 24px;
  margin-bottom: 16px;
  box-shadow: 0 4px 12px rgba(46,125,50,0.05);
  transition: border-color 0.2s;
}
.q-num { font-size: 0.75em; color: #8aaa8a; margin-bottom: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }
.q-text { font-size: 1.05em; font-weight: 600; color: #1a3a1a; margin: 0 0 18px; line-height: 1.4; }

/* ─── OPTIONS ────────────────────────────────────────────── */
.options { display: flex; flex-direction: column; gap: 9px; }
.opt {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 16px;
  border: 1.5px solid #d8e8d8;
  border-radius: 14px;
  cursor: pointer;
  font-size: 0.93em;
  color: #2a4a2a;
  background: #fafffe;
  transition: background 0.15s, border-color 0.15s, transform 0.1s;
  user-select: none;
}
.opt:hover { background: #edf7ed; border-color: #aacbaa; transform: translateX(3px); }
.opt-icon { font-size: 1.1em; flex-shrink: 0; width: 20px; text-align: center; }
.opt.correct { border-color: #2e7d32; background: #e8f5e9; color: #1b5e20; font-weight: 600; }
.opt.wrong   { border-color: #c62828; background: #ffebee; color: #b71c1c; }
.opt.dimmed  { opacity: 0.45; cursor: default; }

/* ─── EXPLICATION ────────────────────────────────────────── */
.q-expl {
  display: none;
  margin-top: 14px;
  padding: 10px 16px;
  background: #f1f8f1;
  border-left: 3px solid #2e7d32;
  border-radius: 0 10px 10px 0;
  font-size: 0.88em;
  color: #3a5a3a;
  line-height: 1.55;
}

/* ─── CARTE SCORE ────────────────────────────────────────── */
.score-card {
  background: #ffffff;
  border: 1px solid #c8ddc8;
  border-radius: 24px;
  padding: 36px;
  text-align: center;
  box-shadow: 0 8px 24px rgba(46,125,50,0.1);
  margin-top: 8px;
  display: none;
}
.score-big { font-size: 3.2em; font-weight: 700; color: #2e7d32; line-height: 1; }
.score-sub { font-size: 0.9em; color: #7aaa7a; margin: 4px 0 16px; }
.score-msg { font-size: 1.05em; color: #2a4a2a; margin-bottom: 24px; line-height: 1.4; }
.score-stars { font-size: 1.8em; margin-bottom: 10px; letter-spacing: 4px; }

.btn-secondary {
  display: inline-block;
  padding: 11px 30px;
  border: 1.5px solid #2e7d32;
  border-radius: 50px;
  color: #2e7d32;
  background: transparent;
  font-size: 0.95em;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.18s;
  margin: 0 6px;
}
.btn-secondary:hover { background: #e8f5e9; }

/* ─── MESSAGE D'ERREUR ───────────────────────────────────── */
.error-box {
  background: #fff8f8;
  border: 1px solid #f5c6c6;
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  color: #c62828;
}
.error-box p { margin: 0 0 16px; }

/* ─── BARRE DE PROGRESSION ───────────────────────────────── */
.progress-bar-wrap {
  height: 5px;
  background: #e8f0e8;
  border-radius: 10px;
  margin-bottom: 24px;
  overflow: hidden;
}
.progress-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #4caf50, #2e7d32);
  border-radius: 10px;
  transition: width 0.4s ease;
}

/* ─── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 540px) {
  .start-screen { padding: 24px 18px; }
  .q-card { padding: 18px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="quiz-hero">
  <h1>🌿 Quiz Jardinage</h1>
  <p>Questions générées par l'IA à chaque session</p>
</section>

<!-- QUIZ -->
<div class="quiz-wrap">

  <!-- ── ÉCRAN DE DÉMARRAGE ── -->
  <div class="start-screen" id="startScreen">
    <h2>Personnalisez votre quiz</h2>
    <p class="subtitle">Choisissez un thème et un niveau, l'IA fait le reste.</p>

    <div class="section-label">Thème</div>
    <div class="pill-group" id="themeGroup">
      <button class="pill-btn active" data-val="jardinage général">🌱 Général</button>
      <button class="pill-btn" data-val="plantes aromatiques et herbes">🌿 Aromatiques</button>
      <button class="pill-btn" data-val="fruits et légumes potager">🍅 Potager</button>
      <button class="pill-btn" data-val="arbres et arbustes">🌳 Arbres</button>
      <button class="pill-btn" data-val="soins et entretien des plantes">💧 Soins</button>
    </div>

    <div class="section-label">Difficulté</div>
    <div class="pill-group" id="diffGroup">
      <button class="pill-btn active" data-val="facile">🟢 Facile</button>
      <button class="pill-btn" data-val="intermédiaire">🟡 Intermédiaire</button>
      <button class="pill-btn" data-val="difficile">🔴 Difficile</button>
    </div>

    <button class="btn-main" id="startBtn">Générer le quiz →</button>
  </div>

  <!-- ── CHARGEMENT ── -->
  <div id="loadingScreen" style="display:none">
    <div class="skeleton-wrap">
      <?php for($i=0;$i<3;$i++): ?>
      <div class="skeleton-card">
        <div class="sk sk-line" style="width:35%"></div>
        <div class="sk sk-title"></div>
        <div class="sk sk-opt"></div>
        <div class="sk sk-opt"></div>
        <div class="sk sk-opt"></div>
      </div>
      <?php endfor; ?>
    </div>
    <p class="loading-hint">L'IA génère vos questions, un instant…</p>
  </div>

  <!-- ── QUIZ ACTIF ── -->
  <div id="quizScreen" style="display:none">
    <div class="quiz-meta">
      <span class="meta-badge" id="metaTheme"></span>
      <span class="meta-badge" id="metaDiff"></span>
      <span class="meta-badge" id="metaProgress"></span>
    </div>
    <div class="progress-bar-wrap"><div class="progress-bar-fill" id="progressFill" style="width:0%"></div></div>
    <div id="questionsContainer"></div>
    <button class="btn-main" id="submitBtn" style="display:none;margin-top:8px">Voir mes résultats →</button>
    <!-- Score -->
    <div class="score-card" id="scoreCard">
      <div class="score-stars" id="scoreStars"></div>
      <div class="score-big" id="scoreNum"></div>
      <div class="score-sub" id="scoreSub"></div>
      <div class="score-msg" id="scoreMsg"></div>
      <button class="btn-secondary" id="restartBtn">↩ Nouveau quiz</button>
      <button class="btn-main" style="display:inline-block;width:auto;padding:11px 30px;" id="retryBtn">Rejouer ce thème</button>
    </div>
  </div>

  <!-- ── ERREUR ── -->
  <div id="errorScreen" style="display:none">
    <div class="error-box">
      <p id="errorMsg">Une erreur est survenue lors de la génération du quiz.</p>
      <button class="btn-secondary" onclick="restart()">Réessayer</button>
    </div>
  </div>

</div><!-- /.quiz-wrap -->

<footer>© 2025 GreenVerse</footer>

<script>
// ── État global ──────────────────────────────────────────────
let selectedTheme = 'jardinage général';
let selectedDiff  = 'facile';
let quizData      = [];
let answered      = 0;
let totalQ        = 0;
let score         = 0;

// ── Sélecteurs de thème et difficulté ───────────────────────
document.querySelectorAll('#themeGroup .pill-btn').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('#themeGroup .pill-btn').forEach(x => x.classList.remove('active'));
    b.classList.add('active');
    selectedTheme = b.dataset.val;
  });
});

document.querySelectorAll('#diffGroup .pill-btn').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('#diffGroup .pill-btn').forEach(x => x.classList.remove('active'));
    b.classList.add('active');
    selectedDiff = b.dataset.val;
  });
});

document.getElementById('startBtn').addEventListener('click', generateQuiz);
document.getElementById('submitBtn').addEventListener('click', showResults);
document.getElementById('restartBtn').addEventListener('click', restart);
document.getElementById('retryBtn').addEventListener('click', generateQuiz);

// ── Génération du quiz via le proxy PHP ──────────────────────
async function generateQuiz() {
  show('loadingScreen');
  hide('startScreen');
  hide('quizScreen');
  hide('errorScreen');

  try {
    // Appel au proxy PHP — la clé API reste côté serveur
    const res = await fetch('quiz-proxy.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ theme: selectedTheme, difficulte: selectedDiff }),
    });

    if (!res.ok) throw new Error(`Erreur serveur : ${res.status}`);

    const data = await res.json();

    if (data.error) throw new Error(data.error);

    // Extraire le texte de la réponse Groq (format OpenAI)
    if (!data.choices || !data.choices[0]) throw new Error('Réponse invalide du serveur.');
    const text = data.choices[0].message.content;

    // Nettoyer les éventuelles balises markdown
    const clean = text.replace(/```json|```/gi, '').trim();
    quizData = JSON.parse(clean);

    if (!Array.isArray(quizData) || quizData.length === 0)
      throw new Error('Format de réponse invalide.');

    renderQuiz();

  } catch (err) {
    hide('loadingScreen');
    show('errorScreen');
    document.getElementById('errorMsg').textContent = err.message || 'Erreur inconnue.';
  }
}

// ── Rendu des questions ──────────────────────────────────────
function renderQuiz() {
  hide('loadingScreen');
  show('quizScreen');
  hide('errorScreen');

  totalQ   = quizData.length;
  answered = 0;
  score    = 0;

  // Badges d'info
  document.getElementById('metaTheme').innerHTML    = `🌿 <b>${cap(selectedTheme)}</b>`;
  document.getElementById('metaDiff').innerHTML     = `📊 <b>${cap(selectedDiff)}</b>`;
  document.getElementById('metaProgress').innerHTML = `<b>0</b> / ${totalQ} répondu`;
  document.getElementById('progressFill').style.width = '0%';
  document.getElementById('submitBtn').style.display = 'none';
  document.getElementById('scoreCard').style.display = 'none';

  const container = document.getElementById('questionsContainer');
  container.innerHTML = '';

  quizData.forEach((q, qi) => {
    const card = document.createElement('div');
    card.className = 'q-card';
    card.id = `card-${qi}`;
    card.innerHTML = `
      <div class="q-num">Question ${qi + 1} / ${totalQ}</div>
      <p class="q-text">${escHtml(q.question)}</p>
      <div class="options" id="opts-${qi}">
        ${q.options.map((opt, oi) => `
          <div class="opt" data-qi="${qi}" data-oi="${oi}" role="button" tabindex="0"
               aria-label="${escHtml(opt)}">
            <span class="opt-icon" id="icon-${qi}-${oi}">○</span>
            <span>${escHtml(opt)}</span>
          </div>
        `).join('')}
      </div>
      <div class="q-expl" id="expl-${qi}">${escHtml(q.explanation)}</div>
    `;
    container.appendChild(card);
  });

  // Événements
  container.querySelectorAll('.opt').forEach(opt => {
    opt.addEventListener('click', () => handleAnswer(opt));
    opt.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') handleAnswer(opt); });
  });
}

// ── Traitement d'une réponse ─────────────────────────────────
function handleAnswer(opt) {
  const qi = parseInt(opt.dataset.qi);
  const oi = parseInt(opt.dataset.oi);
  const opts = document.querySelectorAll(`[data-qi="${qi}"]`);

  // Déjà répondu ?
  if ([...opts].some(o => o.classList.contains('correct') || o.classList.contains('wrong'))) return;

  const isCorrect = oi === quizData[qi].correct;
  if (isCorrect) score++;

  opts.forEach((o, idx) => {
    o.style.cursor = 'default';
    o.setAttribute('tabindex', '-1');
    if (idx === quizData[qi].correct) {
      o.classList.add('correct');
      document.getElementById(`icon-${qi}-${idx}`).textContent = '✓';
    } else if (idx === oi && !isCorrect) {
      o.classList.add('wrong');
      document.getElementById(`icon-${qi}-${idx}`).textContent = '✗';
    } else {
      o.classList.add('dimmed');
    }
  });

  document.getElementById(`expl-${qi}`).style.display = 'block';

  answered++;
  const pct = Math.round((answered / totalQ) * 100);
  document.getElementById('progressFill').style.width = pct + '%';
  document.getElementById('metaProgress').innerHTML = `<b>${answered}</b> / ${totalQ} répondu`;

  if (answered === totalQ) {
    document.getElementById('submitBtn').style.display = 'block';
    document.getElementById('submitBtn').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

// ── Affichage du score ───────────────────────────────────────
function showResults() {
  document.getElementById('submitBtn').style.display = 'none';
  const card = document.getElementById('scoreCard');
  card.style.display = 'block';

  document.getElementById('scoreNum').textContent = `${score} / ${totalQ}`;
  document.getElementById('scoreSub').textContent = `bonne${score > 1 ? 's' : ''} réponse${score > 1 ? 's' : ''}`;

  const pct = score / totalQ;
  let stars, msg;
  if (pct === 1)      { stars = '⭐⭐⭐'; msg = 'Parfait ! Vous êtes un vrai expert du jardinage.'; }
  else if (pct >= .8) { stars = '⭐⭐';   msg = 'Excellent ! Vous maîtrisez très bien le sujet.'; }
  else if (pct >= .6) { stars = '⭐';     msg = 'Bien joué ! Encore un peu d\'entraînement et vous serez imbattable.'; }
  else if (pct >= .4) { stars = '🌱';    msg = 'Pas mal pour commencer ! Continuez à explorer le jardin.'; }
  else                 { stars = '💧';    msg = 'Le jardinage, ça s\'apprend ! Réessayez, vous progresserez.'; }

  document.getElementById('scoreStars').textContent = stars;
  document.getElementById('scoreMsg').textContent   = msg;

  card.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ── Utilitaires ──────────────────────────────────────────────
function restart() {
  hide('quizScreen');
  hide('errorScreen');
  show('startScreen');
  quizData = []; answered = 0; score = 0;
}

function show(id) { document.getElementById(id).style.display = 'block'; }
function hide(id) { document.getElementById(id).style.display = 'none';  }
function cap(s)   { return s.charAt(0).toUpperCase() + s.slice(1); }
function escHtml(s) {
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}
</script>
</body>
</html>