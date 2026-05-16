<?php
session_start();

$host = '127.0.0.1';
$db = 'greenverse';
$user = 'root';  
$pass = '';  
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]));
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS `ateliers` (
        `id`           INT          NOT NULL AUTO_INCREMENT,
        `titre`        VARCHAR(100) NOT NULL,
        `description`  TEXT         NOT NULL,
        `date_atelier` DATE         NOT NULL,
        `heure`        TIME         NOT NULL,
        `lieu`         VARCHAR(100) NOT NULL,
        `video`        VARCHAR(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    if ($action === 'create') {
        $stmt = $pdo->prepare("INSERT INTO ateliers (titre, description, date_atelier, heure, lieu, video)
                               VALUES (:titre, :description, :date_atelier, :heure, :lieu, :video)");
        $stmt->execute([
            ':titre'        => trim($_POST['titre']),
            ':description'  => trim($_POST['description']),
            ':date_atelier' => $_POST['date_atelier'],
            ':heure'        => $_POST['heure'],
            ':lieu'         => trim($_POST['lieu']),
            ':video'        => trim($_POST['video']),
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'Atelier créé avec succès !']);
    }

    elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE ateliers SET titre=:titre, description=:description,
                               date_atelier=:date_atelier, heure=:heure, lieu=:lieu, video=:video
                               WHERE id=:id");
        $stmt->execute([
            ':titre'        => trim($_POST['titre']),
            ':description'  => trim($_POST['description']),
            ':date_atelier' => $_POST['date_atelier'],
            ':heure'        => $_POST['heure'],
            ':lieu'         => trim($_POST['lieu']),
            ':video'        => trim($_POST['video']),
            ':id'           => (int)$_POST['id'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Atelier mis à jour !']);
    }

    elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM ateliers WHERE id = :id");
        $stmt->execute([':id' => (int)$_POST['id']]);
        echo json_encode(['success' => true, 'message' => 'Atelier supprimé !']);
    }

    elseif ($action === 'get') {
        $stmt = $pdo->prepare("SELECT * FROM ateliers WHERE id = :id");
        $stmt->execute([':id' => (int)$_POST['id']]);
        $row = $stmt->fetch();
        echo json_encode(['success' => true, 'data' => $row]);
    }

    exit;
}

$ateliers = $pdo->query("SELECT * FROM ateliers ORDER BY date_atelier ASC")->fetchAll();

$palettes = ['fleurs','potager','aromatiques','arbustes','arbres','aquatiques','grimpantes'];

$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin';
$isUser  = isset($_SESSION['user_id']) && $_SESSION['user_role'] !== 'admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ateliers - GreenVerse</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>
:root {
  --green-dark: #1b5e20;
  --green-mid: #2e7d32;
  --green-light: #a5d6a7;
  --cream: #fafaf5;
  --shadow: 0 8px 32px rgba(27,94,32,.13);
  --radius: 18px;
  --font-display: 'Playfair Display', serif;
  --font-body: 'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: var(--font-body); background: var(--cream); color: #222; }

.hero {
  min-height: 44vh;
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  text-align: center;
  padding: 100px 20px 40px;
  background: linear-gradient(160deg, #e8f5e9 0%, #f1f8e9 60%, #fffde7 100%);
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 70% 30%, rgba(165,214,167,.35) 0%, transparent 60%),
              radial-gradient(ellipse at 20% 80%, rgba(255,243,224,.6) 0%, transparent 50%);
}
.hero h1 {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 5vw, 3.8rem);
  color: var(--green-dark);
  position: relative; z-index: 1;
  letter-spacing: -.5px;
}
.hero p {
  font-size: 1.15rem;
  color: var(--green-mid);
  margin: 14px 0 32px;
  position: relative; z-index: 1;
}

.btn-add-atelier {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green-dark);
  color: #fff;
  padding: 13px 28px;
  border-radius: 50px;
  font-family: var(--font-body);
  font-size: .95rem; font-weight: 500;
  border: none; cursor: pointer;
  box-shadow: 0 4px 20px rgba(27,94,32,.3);
  transition: transform .2s, box-shadow .2s;
  position: relative; z-index: 1;
}
.btn-add-atelier:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(27,94,32,.4); }
.btn-add-atelier svg { width: 18px; height: 18px; }

.cards-container {
  display: flex; flex-wrap: wrap;
  justify-content: center;
  gap: 32px;
  padding: 60px 24px 80px;
}

.flip-container {
  perspective: 1000px;
  width: 300px; height: 430px;
}
.flip-card {
  width: 100%; height: 100%;
  position: relative;
  transition: transform .75s cubic-bezier(.4,0,.2,1);
  transform-style: preserve-3d;
}
.flip-container:hover .flip-card { transform: rotateY(180deg); }

.flip-front, .flip-back {
  position: absolute; inset: 0;
  border-radius: var(--radius);
  backface-visibility: hidden;
  box-shadow: var(--shadow);
  overflow: hidden;
}
.flip-front {
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  padding: 24px;
  text-align: center;
}
.flip-front .plant-emoji {
  font-size: 3.5rem; margin-bottom: 14px;
  filter: drop-shadow(0 4px 8px rgba(0,0,0,.1));
}
.flip-front h3 {
  font-family: var(--font-display);
  font-size: 1.25rem;
  margin-bottom: 6px;
}
.flip-front .date-badge {
  font-size: .78rem;
  background: rgba(255,255,255,.55);
  border-radius: 20px;
  padding: 4px 12px;
  margin-top: 8px;
  font-weight: 500;
}

.flip-back {
  transform: rotateY(180deg);
  padding: 20px;
  display: flex; flex-direction: column;
  justify-content: space-between;
}
.flip-back .video-wrap {
  width: 100%; height: 150px;
  border-radius: 12px;
  overflow: hidden;
  background: rgba(0,0,0,.08);
}
.flip-back .video-wrap iframe {
  width: 100%; height: 100%;
  border: none;
}
.flip-back p { font-size: .88rem; line-height: 1.55; }
.flip-back .meta { font-size: .78rem; opacity: .75; }

.flip-back .action-btns {
  display: flex; gap: 8px; justify-content: center; margin-top: 6px;
}
.btn-edit, .btn-delete {
  flex: 1;
  padding: 9px 0;
  border-radius: 50px;
  border: 2px solid currentColor;
  background: transparent;
  cursor: pointer;
  font-family: var(--font-body);
  font-size: .82rem; font-weight: 500;
  transition: background .2s, color .2s;
}
.btn-edit:hover { background: currentColor; color: #fff !important; }
.btn-delete { opacity: .75; }
.btn-delete:hover { background: currentColor; color: #fff !important; opacity: 1; }

.btn-inscrire {
  display: block;
  width: 100%;
  padding: 10px 0;
  border-radius: 50px;
  border: none;
  background: var(--green-dark);
  color: #fff;
  font-family: var(--font-body);
  font-size: .85rem;
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  margin-top: 8px;
  box-shadow: 0 4px 14px rgba(27,94,32,.3);
  transition: transform .2s, box-shadow .2s, background .2s;
  letter-spacing: .3px;
}
.btn-inscrire:hover {
  background: #2e7d32;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(27,94,32,.4);
}

.flip-front.fleurs { background:#FFDDEE; color:#880E4F; }
.flip-back.fleurs { background:#FFE6F0; color:#880E4F; }
.flip-front.potager { background:#D0F0C0; color:#2E7D32; }
.flip-back.potager { background:#E0F7D4; color:#2E7D32; }
.flip-front.aromatiques { background:#FFF8C6; color:#F57F17; }
.flip-back.aromatiques { background:#FFFCE0; color:#F57F17; }
.flip-front.arbustes { background:#FDD9FF; color:#6A1B9A; }
.flip-back.arbustes { background:#FEE6FF; color:#6A1B9A; }
.flip-front.arbres { background:#CDE7FF; color:#0D47A1; }
.flip-back.arbres { background:#E0F0FF; color:#0D47A1; }
.flip-front.aquatiques { background:#C6F0FF; color:#01579B; }
.flip-back.aquatiques { background:#E0F8FF; color:#01579B; }
.flip-front.grimpantes { background:#EAC6FF; color:#6A1B9A; }
.flip-back.grimpantes { background:#F4D9FF; color:#6A1B9A; }

.modal-overlay {
  display: none;
  position: fixed; inset: 0; z-index: 1000;
  background: rgba(0,0,0,.45);
  backdrop-filter: blur(4px);
  justify-content: center; align-items: center;
}
.modal-overlay.active { display: flex; }

.modal-box {
  background: #fff;
  border-radius: 24px;
  padding: 40px 36px 32px;
  width: min(540px, 95vw);
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 24px 80px rgba(0,0,0,.22);
  position: relative;
  animation: modalIn .35s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalIn {
  from { transform: scale(.88) translateY(30px); opacity: 0; }
  to { transform: scale(1) translateY(0); opacity: 1; }
}

.modal-title {
  font-family: var(--font-display);
  font-size: 1.6rem;
  color: var(--green-dark);
  margin-bottom: 24px;
}

.modal-close {
  position: absolute; top: 18px; right: 20px;
  background: #f0f0ec; border: none; cursor: pointer;
  width: 34px; height: 34px; border-radius: 50%;
  font-size: 1.1rem; display: flex; align-items: center; justify-content: center;
  transition: background .2s;
}
.modal-close:hover { background: #e0e0d8; }

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group.full { grid-column: 1 / -1; }

label { font-size: .82rem; font-weight: 500; color: #555; text-transform: uppercase; letter-spacing: .5px; }

input, textarea, select {
  padding: 11px 14px;
  border: 1.5px solid #e0e0d8;
  border-radius: 10px;
  font-family: var(--font-body);
  font-size: .92rem;
  transition: border-color .2s, box-shadow .2s;
  background: #fafaf5;
  outline: none;
}
input:focus, textarea:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(46,125,50,.12);
}
textarea { resize: vertical; min-height: 80px; }

.btn-submit {
  width: 100%;
  margin-top: 8px;
  padding: 14px;
  background: var(--green-dark);
  color: #fff;
  border: none;
  border-radius: 50px;
  font-family: var(--font-display);
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 4px 18px rgba(27,94,32,.3);
  transition: transform .2s, box-shadow .2s;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 26px rgba(27,94,32,.4); }

.toast {
  position: fixed; bottom: 28px; right: 28px;
  background: var(--green-dark); color: #fff;
  padding: 14px 24px;
  border-radius: 50px;
  font-size: .9rem; font-weight: 500;
  box-shadow: 0 8px 32px rgba(0,0,0,.25);
  z-index: 2000;
  opacity: 0; transform: translateY(20px);
  transition: opacity .3s, transform .3s;
  pointer-events: none;
}
.toast.show { opacity: 1; transform: translateY(0); }
.toast.error { background: #c62828; }

.confirm-overlay {
  display: none;
  position: fixed; inset: 0; z-index: 1100;
  background: rgba(0,0,0,.5);
  justify-content: center; align-items: center;
}
.confirm-overlay.active { display: flex; }
.confirm-box {
  background: #fff;
  border-radius: 20px;
  padding: 36px 32px;
  width: min(380px, 92vw);
  text-align: center;
  box-shadow: 0 16px 60px rgba(0,0,0,.2);
  animation: modalIn .3s cubic-bezier(.34,1.56,.64,1);
}
.confirm-box h3 { font-family: var(--font-display); font-size: 1.35rem; color: #b71c1c; margin-bottom: 10px; }
.confirm-box p { color: #555; font-size: .92rem; margin-bottom: 24px; }
.confirm-btns { display: flex; gap: 12px; justify-content: center; }
.btn-cancel {
  padding: 11px 28px; border-radius: 50px;
  border: 1.5px solid #ccc; background: transparent;
  font-family: var(--font-body); cursor: pointer;
  transition: background .2s;
}
.btn-cancel:hover { background: #f5f5f5; }
.btn-confirm-delete {
  padding: 11px 28px; border-radius: 50px;
  border: none; background: #c62828; color: #fff;
  font-family: var(--font-body); cursor: pointer;
  box-shadow: 0 4px 16px rgba(198,40,40,.35);
  transition: transform .2s;
}
.btn-confirm-delete:hover { transform: translateY(-1px); }

.empty-state {
  text-align: center; padding: 80px 20px;
  color: #888;
}
.empty-state .big-leaf { font-size: 4rem; margin-bottom: 16px; }
.empty-state p { font-size: 1.1rem; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero">
  <h1>🌿 Nos Ateliers</h1>
  <p>Rejoignez nos ateliers et apprenez à cultiver, créer et s'épanouir dans la nature.</p>

  <?php if ($isAdmin): ?>
  <button class="btn-add-atelier" onclick="openModal()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Ajouter un atelier
  </button>
  <?php endif; ?>
</section>

<section class="cards-container" id="cardsContainer">
<?php if (empty($ateliers)): ?>
  <div class="empty-state">
    <div class="big-leaf">🍃</div>
    <p>Aucun atelier pour l'instant.<br>Commencez par en créer un !</p>
  </div>
<?php else: ?>
  <?php foreach ($ateliers as $i => $a):
    $palette       = $palettes[$i % count($palettes)];
    $dateFormatted = date('d/m/Y', strtotime($a['date_atelier']));
    $heureFormatted = substr($a['heure'], 0, 5);
    $emojis = ['fleurs'=>'🌹','potager'=>'🥕','aromatiques'=>'🌿','arbustes'=>'🌸','arbres'=>'🌳','aquatiques'=>'💧','grimpantes'=>'🍃'];
    $emoji = $emojis[$palette] ?? '🌱';
  ?>
  <div class="flip-container" id="card-<?= $a['id'] ?>">
    <div class="flip-card">

      <!-- FRONT -->
      <div class="flip-front <?= $palette ?>">
        <div class="plant-emoji"><?= $emoji ?></div>
        <h3><?= htmlspecialchars($a['titre']) ?></h3>
        <span class="date-badge">📅 <?= $dateFormatted ?><?= $heureFormatted !== '00:00' ? ' · ' . $heureFormatted : '' ?></span>
        <?php if ($a['lieu']): ?>
          <span class="date-badge" style="margin-top:5px;">📍 <?= htmlspecialchars($a['lieu']) ?></span>
        <?php endif; ?>
      </div>

      <!-- BACK -->
      <div class="flip-back <?= $palette ?>">
        <?php if ($a['video']): ?>
        <div class="video-wrap">
          <iframe src="<?= htmlspecialchars($a['video']) ?>" allowfullscreen loading="lazy"></iframe>
        </div>
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($a['description'])) ?></p>

        <?php if ($isAdmin): ?>
          <div class="action-btns">
            <button class="btn-edit"
              onclick="event.stopPropagation(); openEditModal(<?= $a['id'] ?>)"
              style="color:inherit;">✏️ Modifier</button>
            <button class="btn-delete"
              onclick="event.stopPropagation(); confirmDelete(<?= $a['id'] ?>, '<?= addslashes($a['titre']) ?>')"
              style="color:inherit;">🗑 Supprimer</button>
          </div>
        <?php elseif ($isUser): ?>
          <a href="inscription.php" class="btn-inscrire" onclick="event.stopPropagation()">
            🌿 S'inscrire à cet atelier
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>
</section>

<!-- MODAL AJOUT / MODIFICATION (admin uniquement) -->
<?php if ($isAdmin): ?>
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <h2 class="modal-title" id="modalTitle">🌱 Nouvel Atelier</h2>

    <div class="form-grid">
      <input type="hidden" id="atelierIdField">

      <div class="form-group full">
        <label for="titreField">Titre *</label>
        <input type="text" id="titreField" placeholder="Ex: Création de Bouquet" required>
      </div>

      <div class="form-group full">
        <label for="descField">Description *</label>
        <textarea id="descField" placeholder="Décrivez l'atelier..."></textarea>
      </div>

      <div class="form-group">
        <label for="dateField">Date *</label>
        <input type="date" id="dateField" required>
      </div>

      <div class="form-group">
        <label for="heureField">Heure</label>
        <input type="time" id="heureField">
      </div>

      <div class="form-group full">
        <label for="lieuField">Lieu</label>
        <input type="text" id="lieuField" placeholder="Ex: Serre principale">
      </div>

      <div class="form-group full">
        <label for="videoField">Lien vidéo YouTube (embed)</label>
        <input type="text" id="videoField" placeholder="https://www.youtube.com/embed/...">
      </div>

      <div class="form-group full">
        <button class="btn-submit" id="submitBtn" onclick="saveAtelier()">✅ Enregistrer l'atelier</button>
      </div>
    </div>
  </div>
</div>

<!-- CONFIRM DELETE (admin uniquement) -->
<div class="confirm-overlay" id="confirmOverlay" onclick="closeConfirm(event)">
  <div class="confirm-box">
    <h3>⚠️ Supprimer l'atelier</h3>
    <p id="confirmText">Êtes-vous sûr de vouloir supprimer cet atelier ?<br>Cette action est irréversible.</p>
    <div class="confirm-btns">
      <button class="btn-cancel" onclick="closeConfirm()">Annuler</button>
      <button class="btn-confirm-delete" onclick="doDelete()">🗑 Supprimer</button>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="toast" id="toast"></div>

<script>
let deleteId = null;

function showToast(msg, isError = false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast show' + (isError ? ' error' : '');
  setTimeout(() => { t.className = 'toast'; }, 3200);
}

<?php if ($isAdmin): ?>
function openModal() {
  document.getElementById('atelierIdField').value = '';
  document.getElementById('titreField').value = '';
  document.getElementById('descField').value = '';
  document.getElementById('dateField').value = '';
  document.getElementById('heureField').value = '';
  document.getElementById('lieuField').value = '';
  document.getElementById('videoField').value = '';
  document.getElementById('modalTitle').textContent = '🌱 Nouvel Atelier';
  document.getElementById('submitBtn').textContent = "✅ Enregistrer l'atelier";
  document.getElementById('modalOverlay').classList.add('active');
}

function closeModal(e) {
  if (!e || e.target === document.getElementById('modalOverlay'))
    document.getElementById('modalOverlay').classList.remove('active');
}

async function openEditModal(id) {
  const fd = new FormData();
  fd.append('action', 'get');
  fd.append('id', id);

  const res  = await fetch('', { method: 'POST', body: fd });
  const json = await res.json();

  if (!json.success) { showToast('Erreur lors du chargement', true); return; }

  const d = json.data;
  document.getElementById('atelierIdField').value = d.id;
  document.getElementById('titreField').value     = d.titre;
  document.getElementById('descField').value      = d.description;
  document.getElementById('dateField').value      = d.date_atelier;
  document.getElementById('heureField').value     = d.heure ? d.heure.substring(0,5) : '';
  document.getElementById('lieuField').value      = d.lieu  || '';
  document.getElementById('videoField').value     = d.video || '';
  document.getElementById('modalTitle').textContent  = "✏️ Modifier l'Atelier";
  document.getElementById('submitBtn').textContent   = '💾 Mettre à jour';
  document.getElementById('modalOverlay').classList.add('active');
}

async function saveAtelier() {
  const id    = document.getElementById('atelierIdField').value;
  const titre = document.getElementById('titreField').value.trim();
  const desc  = document.getElementById('descField').value.trim();
  const date  = document.getElementById('dateField').value;
  const heure = document.getElementById('heureField').value || '00:00';
  const lieu  = document.getElementById('lieuField').value.trim();
  const video = document.getElementById('videoField').value.trim();

  if (!titre || !desc || !date) {
    showToast('Veuillez remplir les champs obligatoires (*)', true);
    return;
  }

  const fd = new FormData();
  fd.append('action', id ? 'update' : 'create');
  if (id) fd.append('id', id);
  fd.append('titre', titre);
  fd.append('description', desc);
  fd.append('date_atelier', date);
  fd.append('heure', heure);
  fd.append('lieu', lieu);
  fd.append('video', video);

  const res  = await fetch('', { method: 'POST', body: fd });
  const json = await res.json();

  if (json.success) {
    showToast(json.message);
    closeModal({});
    setTimeout(() => location.reload(), 900);
  } else {
    showToast(json.message || 'Erreur serveur', true);
  }
}

function confirmDelete(id, name) {
  deleteId = id;
  document.getElementById('confirmText').innerHTML =
    `Êtes-vous sûr de vouloir supprimer <strong>"${name}"</strong> ?<br>Cette action est irréversible.`;
  document.getElementById('confirmOverlay').classList.add('active');
}

function closeConfirm(e) {
  if (!e || e.target === document.getElementById('confirmOverlay'))
    document.getElementById('confirmOverlay').classList.remove('active');
}

async function doDelete() {
  if (!deleteId) return;
  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('id', deleteId);

  const res  = await fetch('', { method: 'POST', body: fd });
  const json = await res.json();

  closeConfirm({});
  if (json.success) {
    showToast(json.message);
    const card = document.getElementById('card-' + deleteId);
    if (card) {
      card.style.transition = 'opacity .4s, transform .4s';
      card.style.opacity    = '0';
      card.style.transform  = 'scale(.85)';
      setTimeout(() => { card.remove(); }, 420);
    }
  } else {
    showToast(json.message || 'Erreur lors de la suppression', true);
  }
  deleteId = null;
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    closeModal({});
    closeConfirm({});
  }
});
<?php endif; ?>
</script>

</body>
</html>