<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Boutique - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* =================== BOUTIQUE =================== */
.shop-hero {
  height: 50vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background: linear-gradient(120deg, #f0fff0, #d4f5c4);
  padding-top: 70px;
}
.shop-hero h1 {
  font-size: 3em;
  color: #2e7d32;
  margin-bottom: 20px;
  font-family: 'Poppins', sans-serif;
}
.shop-hero p {
  font-size: 1.3em;
  color: #1b5e20;
}

/* ===== FILTRES ===== */
.filter-bar {
  display: flex;
  justify-content: center;
  gap: 10px;
  padding: 30px 20px 10px;
  flex-wrap: wrap;
}
.filter-btn {
  background: white;
  border: 2px solid #2e7d32;
  color: #2e7d32;
  padding: 8px 20px;
  border-radius: 25px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
  font-size: 0.9em;
}
.filter-btn:hover,
.filter-btn.active {
  background: #2e7d32;
  color: white;
}
.results-count {
  text-align: center;
  font-family: 'Poppins', sans-serif;
  color: #777;
  margin: 8px 0 0;
  font-size: 0.9em;
}

/* ===== BOUTON ADMIN ===== */
.admin-toolbar {
  display: flex;
  justify-content: flex-end;
  padding: 20px 40px 0;
}
.btn-add-product {
  background: #2e7d32;
  color: white;
  padding: 12px 28px;
  border-radius: 30px;
  font-weight: bold;
  font-family: 'Poppins', sans-serif;
  font-size: 1em;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 15px rgba(46,125,50,0.3);
  transition: 0.3s;
}
.btn-add-product:hover { background: #1b5e20; transform: translateY(-2px); }

/* ===== GRILLE ===== */
.products-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  padding: 40px 20px 60px;
}

/* ===== CARTES ===== */
.product-card {
  width: 280px;
  background: #fffaf0;
  border-radius: 20px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  text-align: center;
  padding-bottom: 20px;
  position: relative;
  animation: fadeIn 0.5s ease forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to   { opacity: 1; transform: translateY(0); }
}
.product-card:hover {
  transform: scale(1.04);
  box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
.product-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-bottom: 2px solid #d4f5c4;
  transition: transform 0.3s;
}
.product-card:hover img { transform: scale(1.05); }

/* Badge type */
.badge {
  position: absolute;
  top: 12px;
  left: 12px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.72em;
  font-weight: 700;
  font-family: 'Poppins', sans-serif;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  z-index: 2;
}
.badge-produit { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
.badge-plante  { background: #e8f5e9; color: #1b5e20; border: 1px solid #a5d6a7; }

/* Badge stock */
.stock-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.72em;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  z-index: 2;
}
.stock-ok  { background: #c8e6c9; color: #1b5e20; }
.stock-low { background: #fff9c4; color: #f57f17; }
.stock-out { background: #ffcdd2; color: #c62828; }

.product-card h3 {
  color: #2e7d32;
  margin: 15px 12px 5px;
  font-family: 'Poppins', sans-serif;
  font-size: 1.05em;
}
.product-card .desc {
  color: #666;
  font-size: 0.85em;
  margin: 0 14px 8px;
  line-height: 1.4;
  min-height: 38px;
}
.product-card .prix {
  color: #2e7d32;
  font-size: 1.2em;
  font-weight: 700;
  margin-bottom: 14px;
  font-family: 'Poppins', sans-serif;
}
.subcat-tag {
  display: inline-block;
  background: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #a5d6a7;
  border-radius: 20px;
  padding: 3px 12px;
  font-size: 0.78em;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  text-transform: capitalize;
  margin-bottom: 12px;
}

/* Boutons */
.btn {
  display: inline-block;
  background: #2e7d32;
  color: white;
  padding: 10px 20px;
  border-radius: 25px;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
  font-family: 'Poppins', sans-serif;
  font-size: 0.9em;
  border: none;
  cursor: pointer;
  margin: 2px 4px;
}
.btn:hover { background: #1f6f43; transform: scale(1.05); }
.btn-info {
  background: white;
  color: #2e7d32;
  border: 2px solid #2e7d32;
}
.btn-info:hover { background: #2e7d32; color: white; }
.btn.out { background: #ccc; cursor: not-allowed; transform: none; }

/* Boutons admin */
.admin-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
  padding: 0 15px;
}
.btn-edit, .btn-delete {
  flex: 1;
  padding: 9px 14px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 0.88em;
  border: none;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}
.btn-edit   { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #4caf50; }
.btn-delete { background: #fff0f0; color: #c62828; border: 1.5px solid #ef9a9a; }
.btn-edit:hover   { background: #4caf50; color: white; }
.btn-delete:hover { background: #c62828; color: white; }

/* ===== MODALS ===== */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 2000;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.2s ease;
}
.modal-overlay.show { display: flex; }

.modal-box {
  background: white;
  border-radius: 20px;
  padding: 35px;
  width: 100%;
  max-width: 460px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
  animation: slideUp 0.3s cubic-bezier(0.22,1,0.36,1);
  position: relative;
}
@keyframes slideUp {
  from { transform: translateY(30px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}
.modal-box h2 {
  color: #2e7d32;
  font-family: 'Poppins', sans-serif;
  margin-bottom: 20px;
  font-size: 1.4em;
}
.modal-box .close-btn {
  position: absolute;
  top: 15px; right: 18px;
  background: none; border: none;
  font-size: 1.4em; cursor: pointer;
  color: #999; transition: color 0.2s;
}
.modal-box .close-btn:hover { color: #333; }

.modal-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 14px;
}
.modal-field label {
  font-size: 0.78em;
  font-weight: 600;
  color: #2e7d32;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}
.modal-field input, .modal-field textarea {
  padding: 11px 14px;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 0.92em;
  outline: none;
  transition: border-color 0.2s;
}
.modal-field input:focus, .modal-field textarea:focus { border-color: #4caf50; }

.modal-actions { display: flex; gap: 10px; margin-top: 20px; }
.modal-btn-save {
  flex: 1; padding: 12px;
  background: #2e7d32; color: white;
  border: none; border-radius: 12px;
  font-weight: bold; font-size: 1em;
  cursor: pointer; transition: 0.3s;
}
.modal-btn-save:hover { background: #1b5e20; }
.modal-btn-cancel {
  flex: 1; padding: 12px;
  background: #f5f5f5; color: #555;
  border: 1.5px solid #ddd; border-radius: 12px;
  font-weight: bold; font-size: 1em;
  cursor: pointer; transition: 0.3s;
}
.modal-btn-cancel:hover { background: #e0e0e0; }

.confirm-box { max-width: 380px; text-align: center; }
.confirm-box .warn-icon { font-size: 3em; margin-bottom: 10px; }
.confirm-box p { color: #555; margin-bottom: 6px; font-size: 0.95em; }
.confirm-box strong { color: #c62828; }
.btn-confirm-delete {
  flex: 1; padding: 12px;
  background: #c62828; color: white;
  border: none; border-radius: 12px;
  font-weight: bold; font-size: 1em;
  cursor: pointer; transition: 0.3s;
}
.btn-confirm-delete:hover { background: #8b0000; }

/* ===== MESSAGES ===== */
.error-msg {
  text-align: center; padding: 60px 20px;
  color: #c62828; font-family: 'Poppins', sans-serif;
  font-size: 1.1em; width: 100%;
}
.empty-msg {
  text-align: center; padding: 60px 20px;
  color: #888; font-family: 'Poppins', sans-serif;
  font-size: 1.1em; width: 100%;
}

@media (max-width: 600px) {
  .products-container { flex-direction: column; align-items: center; }
  .shop-hero h1 { font-size: 2em; }
  .admin-toolbar { padding: 16px 20px 0; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="shop-hero">
  <h1>Boutique GreenVerse 🌿</h1>
  <p>Découvrez nos graines, plantes et kits DIY pour votre jardin</p>
</section>

<!-- BOUTON AJOUTER (admin uniquement) -->
<?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
<div class="admin-toolbar">
  <button class="btn-add-product" onclick="openAddModal()">
    ➕ Ajouter un produit
  </button>
</div>
<?php endif; ?>

<!-- FILTRES -->
<div class="filter-bar" id="filter-bar">
  <button class="filter-btn active" data-filter="all">🌍 Tout voir</button>
  <button class="filter-btn" data-filter="produit">🛒 Produits</button>
  <button class="filter-btn" data-filter="plante">🌿 Plantes</button>
  <!-- Les sous-catégories seront injectées par JS après chargement -->
</div>
<p class="results-count" id="results-count"></p>

<!-- GRILLE -->
<section class="products-container" id="products-container">
  <p style="color:#4caf50; font-family:'Poppins',sans-serif;">Chargement... 🌿</p>
</section>

<!-- ========== MODAL MODIFIER ========== -->
<div class="modal-overlay" id="modal-edit">
  <div class="modal-box">
    <button class="close-btn" onclick="closeModal('modal-edit')">✕</button>
    <h2>✏️ Modifier le produit</h2>
    <div class="modal-field">
      <label>Nom du produit</label>
      <input type="text" id="edit-nom" placeholder="Nom du produit">
    </div>
    <div class="modal-field">
      <label>Prix (DT)</label>
      <input type="text" id="edit-prix" placeholder="Ex: 12.00">
    </div>
    <div class="modal-field">
      <label>Image (chemin ou URL)</label>
      <input type="text" id="edit-image" placeholder="images/monproduit.jpg">
    </div>
    <div class="modal-actions">
      <button class="modal-btn-cancel" onclick="closeModal('modal-edit')">Annuler</button>
      <button class="modal-btn-save"   onclick="saveEdit()">✅ Enregistrer</button>
    </div>
  </div>
</div>

<!-- ========== MODAL AJOUTER ========== -->
<div class="modal-overlay" id="modal-add">
  <div class="modal-box">
    <button class="close-btn" onclick="closeModal('modal-add')">✕</button>
    <h2>🌱 Nouveau produit</h2>
    <div class="modal-field">
      <label>Nom du produit</label>
      <input type="text" id="add-nom" placeholder="Nom du produit">
    </div>
    <div class="modal-field">
      <label>Prix (DT)</label>
      <input type="text" id="add-prix" placeholder="Ex: 12.00">
    </div>
    <div class="modal-field">
      <label>Image (chemin ou URL)</label>
      <input type="text" id="add-image" placeholder="images/monproduit.jpg">
    </div>
    <div class="modal-actions">
      <button class="modal-btn-cancel" onclick="closeModal('modal-add')">Annuler</button>
      <button class="modal-btn-save"   onclick="saveAdd()">✅ Ajouter</button>
    </div>
  </div>
</div>

<!-- ========== MODAL CONFIRMATION SUPPRESSION ========== -->
<div class="modal-overlay" id="modal-delete">
  <div class="modal-box confirm-box">
    <button class="close-btn" onclick="closeModal('modal-delete')">✕</button>
    <div class="warn-icon">⚠️</div>
    <h2>Confirmer la suppression</h2>
    <p>Voulez-vous vraiment supprimer</p>
    <p><strong id="delete-nom-label"></strong> ?</p>
    <p style="font-size:0.82em; color:#999; margin-top:8px;">Cette action est irréversible.</p>
    <div class="modal-actions" style="margin-top:22px;">
      <button class="modal-btn-cancel"    onclick="closeModal('modal-delete')">Annuler</button>
      <button class="btn-confirm-delete"  onclick="confirmDelete()">🗑️ Supprimer</button>
    </div>
  </div>
</div>

<footer>© 2025 GreenVerse</footer>

<script>
const BASE_URL  = '/Green Verse/GreenVerse/backend/produits';
const IS_ADMIN  = <?= (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') ? 'true' : 'false' ?>;

const CAT_LABELS = {
  fleurs:      '🌸 Fleurs',
  potager:     '🥕 Potager',
  aromatiques: '🌿 Aromatiques',
  arbustes:    '🌳 Arbustes',
  arbres:      '🌲 Arbres',
  aquatiques:  '💧 Aquatiques',
  grimpantes:  '🌀 Grimpantes',
};

let currentEditId   = null;
let currentDeleteId = null;
let allCards        = [];

// ── CHARGEMENT ──────────────────────────────────────────
async function chargerProduits() {
  const container = document.getElementById('products-container');
  try {
    container.innerHTML = '<p style="color:#4caf50;font-family:\'Poppins\',sans-serif;">Chargement... 🌿</p>';

    const resProduits = await fetch(BASE_URL + '/read.php');
    const produits    = await resProduits.json();
    let items = produits.map(p => ({ ...p, _type: 'produit' }));

    if (!IS_ADMIN) {
      const resPlantes = await fetch(BASE_URL.replace('produits','plantes') + '/read.php');
      const plantes    = await resPlantes.json();
      items = [...items, ...plantes.map(p => ({ ...p, _type: 'plante' }))];
    }

    container.innerHTML = '';

    if (items.length === 0) {
      container.innerHTML = '<div class="empty-msg">Aucun article disponible pour le moment.</div>';
      return;
    }

    // Injecter les boutons sous-catégories dans la filter-bar
    const cats = [...new Set(items.filter(p => p._type === 'plante' && p.categorie).map(p => p.categorie))];
    const bar  = document.getElementById('filter-bar');
    cats.forEach(cat => {
      const btn = document.createElement('button');
      btn.className   = 'filter-btn filter-sub';
      btn.dataset.filter = 'cat_' + cat;
      btn.textContent = CAT_LABELS[cat] || cat.charAt(0).toUpperCase() + cat.slice(1);
      bar.appendChild(btn);
      btn.addEventListener('click', () => applyFilter(btn));
    });

    items.forEach((p, i) => { container.innerHTML += creerCarteHTML(p, i); });
    allCards = document.querySelectorAll('.product-card');
    updateCount();

  } catch (e) {
    container.innerHTML = '<div class="error-msg">❌ Erreur de chargement : ' + e.message + '</div>';
  }
}

// ── CRÉATION D'UNE CARTE ────────────────────────────────
function creerCarteHTML(p, idx) {
  const isPlante = p._type === 'plante';
  const cat      = p.categorie || '';
  const nom      = (p.nom || '').replace(/'/g, "\\'");
  const image    = (p.image || 'images/placeholder.jpg').replace(/'/g, "\\'");
  const prix     = parseFloat(p.prix || 0).toFixed(2).replace('.', ',');
  const desc     = p.description || '';
  const emoji    = p.emoji || '';
  const delay    = idx * 60;

  // Badge type
  const badgeType = isPlante
    ? `<span class="badge badge-plante">🌿 Plante</span>`
    : `<span class="badge badge-produit">🛒 Produit</span>`;

  // Badge stock (produits uniquement)
  let stockBadge = '';
  if (!isPlante) {
    const stock = parseInt(p.stock) || 0;
    let sc, st;
    if (stock === 0)   { sc = 'stock-out'; st = 'Rupture de stock'; }
    else if (stock < 5){ sc = 'stock-low'; st = 'Reste ' + stock; }
    else               { sc = 'stock-ok';  st = 'En stock (' + stock + ')'; }
    stockBadge = `<span class="stock-badge ${sc}">${st}</span>`;
  }

  // Tag sous-catégorie
  const catTag = isPlante && cat
    ? `<span class="subcat-tag">${CAT_LABELS[cat] || cat}</span><br>`
    : '';

  // Boutons
  let boutons = '';
  if (IS_ADMIN) {
    boutons = `<div class="admin-actions">
      <button class="btn-edit"   onclick="openEditModal(${p.id},'${nom}','${prix}','${image}')">✏️ Modifier</button>
      <button class="btn-delete" onclick="openDeleteConfirm(${p.id},'${nom}')">🗑️ Supprimer</button>
    </div>`;
  } else {
    const stock = parseInt(p.stock) || 0;
    if (!isPlante && stock === 0) {
      boutons = `<button class="btn out" disabled>Indisponible</button>`;
    } else {
      boutons = `<a href="panier.php?action=ajouter&id=${p.id}&type=${p._type}" class="btn">Ajouter au panier</a>`;
      
    }
  }

  return `
    <div class="product-card" id="card-${p.id}" data-type="${p._type}" data-cat="${cat}"
         style="animation-delay:${delay}ms">
      ${badgeType}
      ${stockBadge}
      <img src="${p.image || 'images/placeholder.jpg'}" alt="${p.nom}"
           onerror="this.src='images/placeholder.jpg'">
      <h3>${emoji} ${p.nom}</h3>
      <p class="desc">${desc}</p>
      <p class="prix">À partir de ${prix} DT</p>
      ${catTag}
      ${boutons}
    </div>`;
}

// ── FILTRES ─────────────────────────────────────────────
function applyFilter(btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const f = btn.dataset.filter;
  allCards.forEach(card => {
    const t = card.dataset.type;
    const c = card.dataset.cat || '';
    const show = f === 'all'
      || f === t
      || (f.startsWith('cat_') && c === f.replace('cat_', ''));
    card.style.display = show ? 'block' : 'none';
  });
  updateCount();
}

function updateCount() {
  const v = [...allCards].filter(c => c.style.display !== 'none').length;
  document.getElementById('results-count').textContent =
    `${v} article${v > 1 ? 's' : ''} trouvé${v > 1 ? 's' : ''}`;
}

document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => applyFilter(btn));
});

// ── MODALS ───────────────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('show');
  });
});

// Modifier
function openEditModal(id, nom, prix, image) {
  currentEditId = id;
  document.getElementById('edit-nom').value   = nom;
  document.getElementById('edit-prix').value  = prix;
  document.getElementById('edit-image').value = image;
  openModal('modal-edit');
}

async function saveEdit() {
  const nom   = document.getElementById('edit-nom').value.trim();
  const prix  = document.getElementById('edit-prix').value.trim();
  const image = document.getElementById('edit-image').value.trim();
  if (!nom || !prix) { alert('Veuillez remplir au moins le nom et le prix.'); return; }

  const res  = await fetch(BASE_URL + '/update.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: currentEditId, nom, prix: parseFloat(prix), image })
  });
  const data = await res.json();

  if (data.message === 'Produit modifié avec succès') {
    const card = document.getElementById('card-' + currentEditId);
    card.querySelector('h3').textContent  = nom;
    card.querySelector('.prix').textContent = 'À partir de ' + parseFloat(prix).toFixed(2).replace('.', ',') + ' DT';
    if (image) card.querySelector('img').src = image;
    closeModal('modal-edit');
  } else {
    alert('Erreur : ' + data.message);
  }
}

// Ajouter
function openAddModal() {
  document.getElementById('add-nom').value   = '';
  document.getElementById('add-prix').value  = '';
  document.getElementById('add-image').value = '';
  openModal('modal-add');
}

async function saveAdd() {
  const nom   = document.getElementById('add-nom').value.trim();
  const prix  = document.getElementById('add-prix').value.trim();
  const image = document.getElementById('add-image').value.trim() || 'images/placeholder.jpg';
  if (!nom || !prix) { alert('Veuillez remplir au moins le nom et le prix.'); return; }

  const res  = await fetch(BASE_URL + '/create.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nom, prix: parseFloat(prix), image })
  });
  const data = await res.json();

  if (data.id) {
    const container = document.getElementById('products-container');
    const newCard   = { id: data.id, nom, prix, image, stock: 10, _type: 'produit', description: '' };
    container.innerHTML += creerCarteHTML(newCard, document.querySelectorAll('.product-card').length);
    allCards = document.querySelectorAll('.product-card');
    updateCount();
    closeModal('modal-add');
  } else {
    alert('Erreur : ' + data.message);
  }
}

// Supprimer
function openDeleteConfirm(id, nom) {
  currentDeleteId = id;
  document.getElementById('delete-nom-label').textContent = nom;
  openModal('modal-delete');
}

async function confirmDelete() {
  const res  = await fetch(BASE_URL + '/delete.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: currentDeleteId })
  });
  const data = await res.json();

  if (data.message === 'Produit supprimé avec succès') {
    const card = document.getElementById('card-' + currentDeleteId);
    if (card) card.remove();
    allCards = document.querySelectorAll('.product-card');
    updateCount();
    closeModal('modal-delete');
  } else {
    alert('Erreur : ' + data.message);
  }
}

// ── INIT ─────────────────────────────────────────────────
chargerProduits();
</script>

</body>
</html>