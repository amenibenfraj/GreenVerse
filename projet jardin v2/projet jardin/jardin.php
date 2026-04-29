<?php
session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>GreenVerse - Jardin</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* ===== OVERLAY ===== */
    .overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      justify-content: center;
      align-items: flex-start;
      overflow-y: auto;
      padding: 20px 0;
      box-sizing: border-box;
    }
    .overlay.active { display: flex; }

    /* ===== POPUP ===== */
    .popup {
      background: white;
      border-radius: 16px;
      padding: 30px;
      width: 90%;
      max-width: 480px;
      position: relative;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
      animation: popIn 0.25s ease;
      max-height: 90vh;
      overflow-y: auto;
      margin: auto;
    }
    @keyframes popIn {
      from { transform: scale(0.85); opacity: 0; }
      to   { transform: scale(1);    opacity: 1; }
    }

    /* ===== BOUTON FERMER ===== */
    .popup-close {
      position: absolute;
      top: 12px; right: 16px;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #888;
    }
    .popup-close:hover { color: #333; }

    /* ===== CONTENU POPUP ===== */
    .popup img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 16px;
    }
    .popup h2 {
      color: #2e7d32;
      margin-bottom: 6px;
      font-size: 1.6rem;
    }
    .popup .badge {
      display: inline-block;
      background: #e8f5e9;
      color: #2e7d32;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      margin-bottom: 12px;
    }
    .popup p {
      color: #555;
      font-size: 1rem;
      margin-bottom: 20px;
    }

    /* ===== BOUTONS ACTION ===== */
    .popup-actions {
      display: flex;
      gap: 12px;
    }
    .btn-modifier {
      flex: 1;
      padding: 11px;
      background: #2e7d32;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }
    .btn-modifier:hover { background: #1b5e20; }
    .btn-supprimer {
      flex: 1;
      padding: 11px;
      background: #c62828;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }
    .btn-supprimer:hover { background: #b71c1c; }

    /* ===== FORMULAIRE MODIFIER ===== */
    .form-modifier {
      display: none;
      margin-top: 16px;
      border-top: 1px solid #eee;
      padding-top: 16px;
    }
    .form-modifier.visible { display: block; }
    .form-modifier label {
      display: block;
      font-weight: bold;
      color: #444;
      margin-bottom: 4px;
      font-size: 0.9rem;
    }
    .form-modifier input,
    .form-modifier select {
      width: 100%;
      padding: 9px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 12px;
      font-size: 0.95rem;
      box-sizing: border-box;
    }
    .btn-save {
      width: 100%;
      padding: 11px;
      background: #388e3c;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }
    .btn-save:hover { background: #2e7d32; }

    /* ===== POPUP AJOUT ===== */
    .popup-ajout h2 {
      color: #2e7d32;
      margin-bottom: 20px;
      text-align: center;
      font-size: 1.4rem;
    }
    .popup-ajout label {
      display: block;
      font-weight: bold;
      color: #444;
      margin-bottom: 4px;
      font-size: 0.9rem;
    }
    .popup-ajout input,
    .popup-ajout select {
      width: 100%;
      padding: 9px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 12px;
      font-size: 0.95rem;
      box-sizing: border-box;
    }
    .btn-ajouter-submit {
      width: 100%;
      padding: 11px;
      background: #2e7d32;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 4px;
    }
    .btn-ajouter-submit:hover { background: #1b5e20; }

    /* ===== BOUTON FLOTTANT AJOUTER ===== */
    .btn-flottant {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #2e7d32;
      color: white;
      border: none;
      border-radius: 50px;
      padding: 14px 22px;
      font-size: 1.1rem;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(0,0,0,0.25);
      z-index: 999;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s, transform 0.2s;
    }
    .btn-flottant:hover {
      background: #1b5e20;
      transform: scale(1.05);
    }

    .msg-succes { color: green; text-align: center; margin-top: 8px; font-weight: bold; }
    .msg-erreur { color: red;   text-align: center; margin-top: 8px; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- CATEGORIES -->
<section class="categories-cards">
  <a href="#fleurs"      class="cat-card">🌸 Fleurs</a>
  <a href="#potager"     class="cat-card">🥕 Potager</a>
  <a href="#aromatiques" class="cat-card">🌿 Aromatiques</a>
  <a href="#arbustes"    class="cat-card">🌺 Arbustes</a>
  <a href="#arbres"      class="cat-card">🌳 Arbres</a>
  <a href="#aquatiques"  class="cat-card">🌊 Aquatiques</a>
  <a href="#grimpantes"  class="cat-card">🍃 Grimpantes</a>
</section>

<!-- ================= SECTIONS ================= -->
<section id="fleurs">
  <h2>🌸 Fleurs</h2>
  <div class="plants-section" id="container-fleurs"><p class="loading">Chargement...</p></div>
</section>
<section id="potager">
  <h2>🥕 Plantes potagères</h2>
  <div class="plants-section" id="container-potager"><p class="loading">Chargement...</p></div>
</section>
<section id="aromatiques">
  <h2>🌿 Plantes aromatiques</h2>
  <div class="plants-section" id="container-aromatiques"><p class="loading">Chargement...</p></div>
</section>
<section id="arbustes">
  <h2>🌺 Arbustes</h2>
  <div class="plants-section" id="container-arbustes"><p class="loading">Chargement...</p></div>
</section>
<section id="arbres">
  <h2>🌳 Arbres</h2>
  <div class="plants-section" id="container-arbres"><p class="loading">Chargement...</p></div>
</section>
<section id="aquatiques">
  <h2>🌊 Plantes aquatiques</h2>
  <div class="plants-section" id="container-aquatiques"><p class="loading">Chargement...</p></div>
</section>
<section id="grimpantes">
  <h2>🍃 Plantes grimpantes</h2>
  <div class="plants-section" id="container-grimpantes"><p class="loading">Chargement...</p></div>
</section>

<footer>© 2025 GreenVerse</footer>

<!-- ===== BOUTON FLOTTANT ===== -->
<button class="btn-flottant" onclick="ouvrirAjout()">
  ➕ Ajouter une plante
</button>

<!-- ================= POPUP DÉTAIL/MODIFIER ================= -->
<div class="overlay" id="overlay" onclick="fermerPopup(event)">
  <div class="popup" id="popup">
    <button class="popup-close" onclick="fermerTout()">✕</button>

    <img id="popup-img" src="" alt="">
    <h2 id="popup-nom"></h2>
    <span class="badge" id="popup-categorie"></span>
    <p id="popup-description"></p>

    <div class="popup-actions">
      <button class="btn-modifier" onclick="toggleModifier()">✏️ Modifier</button>
      <button class="btn-supprimer" onclick="supprimerPlante()">🗑️ Supprimer</button>
    </div>

    <div class="form-modifier" id="form-modifier">
      <label>Nom</label>
      <input type="text" id="edit-nom">
      <label>Catégorie</label>
      <select id="edit-categorie">
        <option value="fleurs">🌸 Fleurs</option>
        <option value="potager">🥕 Potager</option>
        <option value="aromatiques">🌿 Aromatiques</option>
        <option value="arbustes">🌺 Arbustes</option>
        <option value="arbres">🌳 Arbres</option>
        <option value="aquatiques">🌊 Aquatiques</option>
        <option value="grimpantes">🍃 Grimpantes</option>
      </select>
      <label>Description</label>
      <input type="text" id="edit-description">
      <label>Emoji</label>
      <input type="text" id="edit-emoji">
      <label>Image (chemin)</label>
      <input type="text" id="edit-image">
      <button class="btn-save" onclick="enregistrerModification()">💾 Enregistrer</button>
      <p id="msg-form"></p>
    </div>
  </div>
</div>

<!-- ================= POPUP AJOUT ================= -->
<div class="overlay" id="overlay-ajout" onclick="fermerAjout(event)">
  <div class="popup popup-ajout" id="popup-ajout">
    <button class="popup-close" onclick="fermerTout()">✕</button>

    <h2>🌱 Ajouter une plante</h2>

    <label>Nom *</label>
    <input type="text" id="add-nom" placeholder="Ex: Rose">

    <label>Catégorie *</label>
    <select id="add-categorie">
      <option value="fleurs">🌸 Fleurs</option>
      <option value="potager">🥕 Potager</option>
      <option value="aromatiques">🌿 Aromatiques</option>
      <option value="arbustes">🌺 Arbustes</option>
      <option value="arbres">🌳 Arbres</option>
      <option value="aquatiques">🌊 Aquatiques</option>
      <option value="grimpantes">🍃 Grimpantes</option>
    </select>

    <label>Description</label>
    <input type="text" id="add-description" placeholder="Ex: Fleur parfumée">

    <label>Emoji</label>
    <input type="text" id="add-emoji" placeholder="Ex: 🌹">

    <label>Image (chemin)</label>
    <input type="text" id="add-image" placeholder="Ex: images/rose.jpg">

    <button class="btn-ajouter-submit" onclick="ajouterPlante()">✅ Ajouter</button>
    <p id="msg-ajout"></p>
  </div>
</div>

<!-- ================= SCRIPT JS ================= -->
<script>
  const BASE = 'http://localhost/Green%20verse/GreenVerse';
  let currentId = null;

  const categories = [
    'fleurs','potager','aromatiques',
    'arbustes','arbres','aquatiques','grimpantes'
  ];

  // ===== CHARGER LES PLANTES =====
  function chargerPlantes() {
    categories.forEach(cat => {
      const container = document.getElementById(`container-${cat}`);
      container.innerHTML = '<p class="loading">Chargement...</p>';

      fetch(`${BASE}/backend/plantes/read.php?categorie=${cat}`)
        .then(res => {
          if (!res.ok) throw new Error(`Erreur HTTP : ${res.status}`);
          return res.json();
        })
        .then(plantes => {
          container.innerHTML = '';
          if (!plantes || plantes.length === 0) {
            container.innerHTML = `<p class="empty">Aucune plante disponible.</p>`;
            return;
          }
          plantes.forEach(plante => {
            container.innerHTML += `
              <div class="plant-card" onclick="ouvrirPopup(${plante.id})" style="cursor:pointer">
                <img 
                  src="${BASE}/${plante.image}"
                  alt="${plante.nom}"
                  onerror="this.src='${BASE}/images/default.jpg'"
                >
                <h3>${plante.nom}</h3>
                <p>${plante.description} ${plante.emoji}</p>
              </div>
            `;
          });
        })
        .catch(err => {
          container.innerHTML = `<p class="erreur">❌ Erreur : ${err.message}</p>`;
        });
    });
  }

  // Charger au démarrage
  chargerPlantes();

  // ===== OUVRIR POPUP DÉTAIL =====
  function ouvrirPopup(id) {
    currentId = id;
    document.getElementById('form-modifier').classList.remove('visible');
    document.getElementById('msg-form').textContent = '';

    fetch(`${BASE}/backend/plantes/read_one.php?id=${id}`)
      .then(res => res.json())
      .then(p => {
        document.getElementById('popup-img').src                  = `${BASE}/${p.image}`;
        document.getElementById('popup-nom').textContent          = `${p.emoji} ${p.nom}`;
        document.getElementById('popup-categorie').textContent    = `🌿 ${p.categorie}`;
        document.getElementById('popup-description').textContent  = p.description;
        document.getElementById('edit-nom').value                 = p.nom;
        document.getElementById('edit-categorie').value           = p.categorie;
        document.getElementById('edit-description').value         = p.description;
        document.getElementById('edit-emoji').value               = p.emoji;
        document.getElementById('edit-image').value               = p.image;
        document.getElementById('overlay').classList.add('active');
      })
      .catch(err => alert('❌ Erreur : ' + err.message));
  }

  // ===== OUVRIR POPUP AJOUT =====
  function ouvrirAjout() {
    // Vider le formulaire
    document.getElementById('add-nom').value         = '';
    document.getElementById('add-categorie').value   = 'fleurs';
    document.getElementById('add-description').value = '';
    document.getElementById('add-emoji').value       = '';
    document.getElementById('add-image').value       = '';
    document.getElementById('msg-ajout').textContent = '';
    document.getElementById('overlay-ajout').classList.add('active');
  }

  // ===== FERMER POPUPS =====
  function fermerTout() {
    document.getElementById('overlay').classList.remove('active');
    document.getElementById('overlay-ajout').classList.remove('active');
    currentId = null;
  }

  function fermerPopup(event) {
    if (event.target === document.getElementById('overlay')) fermerTout();
  }

  function fermerAjout(event) {
    if (event.target === document.getElementById('overlay-ajout')) fermerTout();
  }

  // ===== TOGGLE FORMULAIRE MODIFIER =====
  function toggleModifier() {
    document.getElementById('form-modifier').classList.toggle('visible');
  }

  // ===== ENREGISTRER MODIFICATION =====
  function enregistrerModification() {
    const data = {
      id:          currentId,
      nom:         document.getElementById('edit-nom').value,
      categorie:   document.getElementById('edit-categorie').value,
      description: document.getElementById('edit-description').value,
      emoji:       document.getElementById('edit-emoji').value,
      image:       document.getElementById('edit-image').value
    };

    fetch(`${BASE}/backend/plantes/update.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    .then(res => res.text())
    .then(texte => {
      const result = JSON.parse(texte);
      const msg = document.getElementById('msg-form');
      msg.className = 'msg-succes';
      msg.textContent = '✅ ' + result.message;
      setTimeout(() => {
        fermerTout();
        chargerPlantes();
      }, 1500);
    })
    .catch(err => {
      const msg = document.getElementById('msg-form');
      msg.className = 'msg-erreur';
      msg.textContent = '❌ Erreur : ' + err.message;
    });
  }

  // ===== AJOUTER PLANTE =====
  function ajouterPlante() {
    const nom = document.getElementById('add-nom').value.trim();
    const cat = document.getElementById('add-categorie').value;

    if (!nom) {
      document.getElementById('msg-ajout').className = 'msg-erreur';
      document.getElementById('msg-ajout').textContent = '❌ Le nom est obligatoire.';
      return;
    }

    const data = {
      nom:         nom,
      categorie:   cat,
      description: document.getElementById('add-description').value.trim(),
      emoji:       document.getElementById('add-emoji').value.trim(),
      image:       document.getElementById('add-image').value.trim()
    };

    fetch(`${BASE}/backend/plantes/create.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
      const msg = document.getElementById('msg-ajout');
      msg.className = 'msg-succes';
      msg.textContent = '✅ ' + result.message;
      setTimeout(() => {
        fermerTout();
        chargerPlantes();
      }, 1500);
    })
    .catch(err => {
      const msg = document.getElementById('msg-ajout');
      msg.className = 'msg-erreur';
      msg.textContent = '❌ Erreur : ' + err.message;
    });
  }

  // ===== SUPPRIMER PLANTE =====
  function supprimerPlante() {
    if (!confirm('⚠️ Voulez-vous vraiment supprimer cette plante ?')) return;

    fetch(`${BASE}/backend/plantes/delete.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: currentId })
    })
    .then(res => res.json())
    .then(result => {
      alert('✅ ' + result.message);
      fermerTout();
      chargerPlantes();
    })
    .catch(err => alert('❌ Erreur : ' + err.message));
  }
</script>

</body>
</html>