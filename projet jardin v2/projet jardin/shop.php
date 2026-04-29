<?php
session_start();
// ================================================
//  CONFIG BASE DE DONNÉES
// ================================================
$host   = "localhost";
$dbname = "greenverse";  // ← ton nom de BDD
$user   = "root";        // ← ton utilisateur
$pass   = "";            // ← ton mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les produits
    $produits = $pdo->query("SELECT * FROM produits ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer toutes les plantes
    $plantes = $pdo->query("SELECT * FROM plantes ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Sous-catégories uniques des plantes
    $subcats = array_unique(array_column($plantes, 'categorie'));

} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Labels sous-catégories plantes
$cat_labels = [
    'fleurs'      => '🌸 Fleurs',
    'potager'     => '🥕 Potager',
    'aromatiques' => '🌿 Aromatiques',
    'arbustes'    => '🌳 Arbustes',
    'arbres'      => '🌲 Arbres',
    'aquatiques'  => '💧 Aquatiques',
    'grimpantes'  => '🌀 Grimpantes',
];
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
.product-card:hover img {
  transform: scale(1.05);
}

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
}
.btn:hover { background: #1f6f43; transform: scale(1.05); }
.btn-info {
  background: white;
  color: #2e7d32;
  border: 2px solid #2e7d32;
}
.btn-info:hover { background: #2e7d32; color: white; }
.btn.out  { background: #ccc; cursor: not-allowed; transform: none; }

/* ===== ERREUR ===== */
.error-msg {
  text-align: center;
  padding: 60px 20px;
  color: #c62828;
  font-family: 'Poppins', sans-serif;
  font-size: 1.1em;
  width: 100%;
}
.empty-msg {
  text-align: center;
  padding: 60px 20px;
  color: #888;
  font-family: 'Poppins', sans-serif;
  font-size: 1.1em;
  width: 100%;
}

@media (max-width: 600px) {
  .products-container { flex-direction: column; align-items: center; }
  .shop-hero h1 { font-size: 2em; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="shop-hero">
  <h1>Boutique GreenVerse</h1>
  <p>Découvrez nos graines, plantes et kits DIY pour votre jardin 🌿</p>
</section>

<!-- FILTRES -->
<div class="filter-bar">
  <button class="filter-btn active" data-filter="all">🌍 Tout voir</button>
  <button class="filter-btn" data-filter="produit">🛒 Produits</button>
  <button class="filter-btn" data-filter="plante">🌿 Plantes</button>
  <?php if (!isset($error) && !empty($subcats)): ?>
    <?php foreach ($subcats as $cat): ?>
      <button class="filter-btn filter-sub" data-filter="cat_<?= htmlspecialchars($cat) ?>">
        <?= htmlspecialchars($cat_labels[$cat] ?? ucfirst($cat)) ?>
      </button>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<p class="results-count" id="results-count"></p>

<!-- GRILLE -->
<section class="products-container" id="products-container">

  <?php if (isset($error)): ?>
    <div class="error-msg">❌ Erreur de connexion : <?= htmlspecialchars($error) ?></div>

  <?php else: ?>

    <!-- ===== PRODUITS ===== -->
    <?php foreach ($produits as $i => $p):
      $stock = (int)$p['stock'];
      $prix  = number_format((float)$p['prix'], 2, ',', '');
      $img   = htmlspecialchars($p['image'] ?: 'images/placeholder.jpg');
      $nom   = htmlspecialchars($p['nom']);
      $desc  = htmlspecialchars($p['description'] ?? '');

      if ($stock === 0)    { $sc = 'stock-out'; $st = 'Rupture de stock'; }
      elseif ($stock < 5)  { $sc = 'stock-low'; $st = "Reste $stock"; }
      else                 { $sc = 'stock-ok';  $st = "En stock ($stock)"; }
    ?>
    <div class="product-card" data-type="produit" style="animation-delay:<?= $i * 60 ?>ms">
      <span class="badge badge-produit">🛒 Produit</span>
      <span class="stock-badge <?= $sc ?>"><?= $st ?></span>
      <img src="<?= $img ?>" alt="<?= $nom ?>"
           onerror="this.src='images/placeholder.jpg'">
      <h3><?= $nom ?></h3>
      <p class="desc"><?= $desc ?></p>
      <p class="prix">À partir de <?= $prix ?> DT</p>
      <?php if ($stock > 0): ?>
        <a href="panier.php?action=ajouter&id=<?= $p['id'] ?>&type=produit" class="btn">Ajouter au panier</a>
      <?php else: ?>
        <button class="btn out" disabled>Indisponible</button>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- ===== PLANTES ===== -->
    <?php foreach ($plantes as $i => $pl):
      $img   = htmlspecialchars($pl['image'] ?: 'images/placeholder.jpg');
      $nom   = htmlspecialchars($pl['nom']);
      $desc  = htmlspecialchars($pl['description'] ?? '');
      $emoji = htmlspecialchars($pl['emoji'] ?? '');
      $cat   = htmlspecialchars($pl['categorie']);
      $delay = (count($produits) + $i) * 60;
    ?>
    <div class="product-card" data-type="plante" data-cat="<?= $cat ?>"
         style="animation-delay:<?= $delay ?>ms">
      <span class="badge badge-plante">🌿 Plante</span>
      <img src="<?= $img ?>" alt="<?= $nom ?>"
           onerror="this.src='images/placeholder.jpg'">
      <h3><?= $emoji ?> <?= $nom ?></h3>
      <p class="desc"><?= $desc ?></p>
      <span class="subcat-tag"><?= htmlspecialchars($cat_labels[$pl['categorie']] ?? ucfirst($cat)) ?></span><br>
      <a href="panier.php?action=ajouter&id=<?= $pl['id'] ?>&type=plante" class="btn">Ajouter au panier</a>
    </div>
    <?php endforeach; ?>

    <!-- Si les deux tables sont vides -->
    <?php if (empty($produits) && empty($plantes)): ?>
      <div class="empty-msg">Aucun article disponible pour le moment.</div>
    <?php endif; ?>

  <?php endif; ?>

</section>

<footer>© 2025 GreenVerse</footer>

<script>
// =============================================
//  FILTRES (côté client, cartes déjà rendues)
// =============================================
const cards   = document.querySelectorAll('.product-card');
const counter = document.getElementById('results-count');

function updateCount() {
  const visible = [...cards].filter(c => c.style.display !== 'none').length;
  counter.textContent = `${visible} article${visible > 1 ? 's' : ''} trouvé${visible > 1 ? 's' : ''}`;
}

function applyFilter(btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const filter = btn.dataset.filter;

  cards.forEach(card => {
    const type = card.dataset.type;
    const cat  = card.dataset.cat || '';

    let show = false;
    if (filter === 'all')                  show = true;
    else if (filter === 'produit')         show = type === 'produit';
    else if (filter === 'plante')          show = type === 'plante';
    else if (filter.startsWith('cat_'))    show = cat === filter.replace('cat_', '');

    card.style.display = show ? 'block' : 'none';
  });

  updateCount();
}

document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => applyFilter(btn));
});

// Compteur initial
updateCount();
</script>

</body>
</html>