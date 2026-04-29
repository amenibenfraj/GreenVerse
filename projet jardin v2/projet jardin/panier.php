<?php
session_start();

/*
-- ================================================
--  SQL : CRÉATION DE LA TABLE `panier`
-- ================================================

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id`         int            NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100)   NOT NULL,
  `item_id`    int            NOT NULL,
  `item_type`  enum('produit','plante') NOT NULL,
  `nom`        varchar(100)   NOT NULL,
  `image`      varchar(200)   DEFAULT NULL,
  `prix`       decimal(10,2)  NOT NULL DEFAULT '0.00',
  `quantite`   int            NOT NULL DEFAULT '1',
  `created_at` timestamp      NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp      NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de test
INSERT INTO `panier`
  (`id`, `session_id`, `item_id`, `item_type`, `nom`, `image`, `prix`, `quantite`, `created_at`, `updated_at`)
VALUES
  (10, '0tmcv7vbt8seugug1lrin9rq6u', 3, 'produit', 'Outils de jardinage', 'images/outils.jpg', '10.00', 4,
   '2026-04-28 14:44:53', '2026-04-28 14:44:59');
*/

// ================================================
//  CONFIG BASE DE DONNÉES
// ================================================
$host   = "localhost";
$dbname = "greenverse";
$user   = "root";
$pass   = "";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// ID unique du visiteur
if (empty($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = session_id();
}
$cart_id = $_SESSION['cart_id'];

// ================================================
//  ACTIONS
// ================================================

// -- AJOUTER --
if (isset($_GET['action']) && $_GET['action'] == 'ajouter') {
    $id   = (int)$_GET['id'];
    $type = $_GET['type']; // produit ou plante

    if ($type == 'produit') {
        $stmt = $pdo->prepare("SELECT nom, image, prix FROM produits WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT nom, image, 0 AS prix FROM plantes WHERE id = ?");
    }
    $stmt->execute([$id]);
    $article = $stmt->fetch();

    if ($article) {
        // Déjà dans le panier ?
        $check = $pdo->prepare("SELECT id, quantite FROM panier WHERE session_id = ? AND item_id = ? AND item_type = ?");
        $check->execute([$cart_id, $id, $type]);
        $existe = $check->fetch();

        if ($existe) {
            $pdo->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id = ?")
                ->execute([$existe['id']]);
        } else {
            $pdo->prepare("INSERT INTO panier (session_id, item_id, item_type, nom, image, prix) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([$cart_id, $id, $type, $article['nom'], $article['image'], $article['prix']]);
        }
    }
    header("Location: panier.php");
    exit;
}

// -- MODIFIER QUANTITÉ --
if (isset($_POST['action']) && $_POST['action'] == 'modifier') {
    $id  = (int)$_POST['id'];
    $qty = max(1, min(99, (int)$_POST['quantite']));
    $pdo->prepare("UPDATE panier SET quantite = ? WHERE id = ? AND session_id = ?")
        ->execute([$qty, $id, $cart_id]);
    header("Location: panier.php");
    exit;
}

// -- SUPPRIMER --
if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
    $id = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM panier WHERE id = ? AND session_id = ?")
        ->execute([$id, $cart_id]);
    header("Location: panier.php");
    exit;
}

// -- VIDER --
if (isset($_GET['action']) && $_GET['action'] == 'vider') {
    $pdo->prepare("DELETE FROM panier WHERE session_id = ?")
        ->execute([$cart_id]);
    header("Location: panier.php");
    exit;
}

// ================================================
//  CHARGER LE PANIER
// ================================================
$stmt = $pdo->prepare("SELECT * FROM panier WHERE session_id = ? ORDER BY created_at ASC");
$stmt->execute([$cart_id]);
$panier = $stmt->fetchAll();

// Calcul total et nombre d'articles
$total       = 0;
$nb_articles = 0;
foreach ($panier as $item) {
    $total       += $item['prix'] * $item['quantite'];
    $nb_articles += $item['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panier - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* ===== HERO ===== */
.panier-hero {
  min-height: 28vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background: linear-gradient(120deg, #f0fff0, #d4f5c4);
  padding-top: 80px;
}
.panier-hero h1 { font-size: 2.5em; color: #2e7d32; font-family: 'Poppins', sans-serif; }
.panier-hero p  { color: #1b5e20; font-size: 1em; }

/* ===== LAYOUT ===== */
.panier-wrapper {
  max-width: 900px;
  margin: 40px auto 80px;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 30px;
  align-items: start;
}
@media (max-width: 700px) {
  .panier-wrapper { grid-template-columns: 1fr; }
}

/* ===== ARTICLE ===== */
.panier-item {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #fffaf0;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 14px;
  margin-bottom: 16px;
}
.panier-item img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
  border: 2px solid #d4f5c4;
}
.item-info { flex: 1; }
.item-info h3 { color: #2e7d32; font-family: 'Poppins', sans-serif; margin: 0 0 4px; font-size: 0.95em; }
.item-info p  { color: #888; font-size: 0.82em; margin: 2px 0; }
.item-info .sous-total { color: #2e7d32; font-weight: 700; font-size: 0.95em; }

/* Quantité */
.qty-form { display: flex; align-items: center; gap: 6px; margin-top: 8px; }
.qty-btn {
  width: 28px; height: 28px;
  border-radius: 50%;
  border: 2px solid #2e7d32;
  background: white; color: #2e7d32;
  font-size: 1em; font-weight: 700;
  cursor: pointer; transition: 0.2s;
}
.qty-btn:hover { background: #2e7d32; color: white; }
.qty-input {
  width: 40px; text-align: center;
  border: 2px solid #d4f5c4;
  border-radius: 8px; padding: 3px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600; color: #2e7d32;
}
.btn-suppr {
  display: inline-block;
  margin-top: 6px;
  color: #e53935;
  font-size: 0.8em;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  text-decoration: none;
}
.btn-suppr:hover { text-decoration: underline; }

/* ===== RÉSUMÉ ===== */
.resume {
  background: #fffaf0;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 24px;
  position: sticky;
  top: 90px;
}
.resume h2 { color: #2e7d32; font-family: 'Poppins', sans-serif; margin: 0 0 16px; }
.resume-ligne {
  display: flex;
  justify-content: space-between;
  font-family: 'Poppins', sans-serif;
  font-size: 0.9em;
  color: #555;
  margin-bottom: 10px;
}
.resume-ligne.total {
  border-top: 2px solid #d4f5c4;
  padding-top: 12px;
  font-weight: 700;
  font-size: 1.05em;
  color: #2e7d32;
}
.btn-commander {
  display: block;
  background: #2e7d32;
  color: white;
  border: none;
  padding: 13px;
  border-radius: 25px;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  text-align: center;
  text-decoration: none;
  margin-top: 16px;
  transition: 0.3s;
}
.btn-commander:hover { background: #1f6f43; }
.btn-vider {
  display: block;
  background: white;
  color: #e53935;
  border: 2px solid #e53935;
  padding: 9px;
  border-radius: 25px;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
  font-size: 0.88em;
  text-align: center;
  text-decoration: none;
  margin-top: 8px;
  transition: 0.3s;
}
.btn-vider:hover { background: #ffebee; }
.btn-continuer {
  display: block;
  text-align: center;
  margin-top: 10px;
  color: #2e7d32;
  font-family: 'Poppins', sans-serif;
  font-size: 0.82em;
  text-decoration: none;
}
.btn-continuer:hover { text-decoration: underline; }

/* ===== PANIER VIDE ===== */
.panier-vide {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 20px;
  font-family: 'Poppins', sans-serif;
}
.panier-vide .emoji { font-size: 4em; }
.panier-vide h2 { color: #2e7d32; margin: 12px 0 6px; }
.panier-vide p  { color: #888; margin-bottom: 20px; }
.btn-shop {
  display: inline-block;
  background: #2e7d32;
  color: white;
  padding: 12px 28px;
  border-radius: 25px;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  text-decoration: none;
}
.btn-shop:hover { background: #1f6f43; }
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="panier-hero">
  <h1>🛒 Mon Panier</h1>
  <p>
    <?php if ($nb_articles > 0): ?>
      <?= $nb_articles ?> article<?= $nb_articles > 1 ? 's' : '' ?> dans votre panier
    <?php else: ?>
      Votre panier est vide
    <?php endif; ?>
  </p>
</section>

<!-- CONTENU -->
<div class="panier-wrapper">

  <?php if (empty($panier)): ?>

    <!-- PANIER VIDE -->
    <div class="panier-vide">
      <div class="emoji">🌱</div>
      <h2>Votre panier est vide</h2>
      <p>Ajoutez des articles depuis la boutique.</p>
      <a href="shop.php" class="btn-shop">Voir la boutique</a>
    </div>

  <?php else: ?>

    <!-- LISTE DES ARTICLES -->
    <div>
      <?php foreach ($panier as $item): ?>
      <div class="panier-item">

        <!-- Image -->
        <img src="<?= htmlspecialchars($item['image'] ?: 'images/placeholder.jpg') ?>"
             alt="<?= htmlspecialchars($item['nom']) ?>"
             onerror="this.src='images/placeholder.jpg'">

        <!-- Infos -->
        <div class="item-info">
          <h3><?= htmlspecialchars($item['nom']) ?></h3>
          <p>Prix unitaire : <?= number_format($item['prix'], 2, ',', '') ?> DT</p>
          <p class="sous-total">
            Sous-total : <?= number_format($item['prix'] * $item['quantite'], 2, ',', '') ?> DT
          </p>

          <!-- Quantité -->
          <form method="POST" action="panier.php" class="qty-form">
            <input type="hidden" name="action"   value="modifier">
            <input type="hidden" name="id"       value="<?= $item['id'] ?>">
            <button type="button" class="qty-btn" onclick="changeQty(this, -1)">−</button>
            <input  type="number" name="quantite" value="<?= $item['quantite'] ?>"
                    min="1" max="99" class="qty-input"
                    onchange="this.form.submit()">
            <button type="button" class="qty-btn" onclick="changeQty(this, +1)">+</button>
          </form>

          <!-- Supprimer -->
          <a href="panier.php?action=supprimer&id=<?= $item['id'] ?>"
             class="btn-suppr"
             onclick="return confirm('Supprimer cet article ?')">
            🗑 Supprimer
          </a>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <!-- RÉSUMÉ -->
    <aside class="resume">
      <h2>Résumé</h2>

      <div class="resume-ligne">
        <span>Articles (<?= $nb_articles ?>)</span>
        <span><?= number_format($total, 2, ',', '') ?> DT</span>
      </div>

      <div class="resume-ligne">
        <span>Livraison</span>
        <?php if ($total >= 30): ?>
          <span style="color:#2e7d32">Gratuite 🎉</span>
        <?php else: ?>
          <span>4,90 DT</span>
        <?php endif; ?>
      </div>

      <?php if ($total < 30): ?>
      <div class="resume-ligne" style="font-size:0.78em;color:#aaa;">
        <span>Plus que <?= number_format(30 - $total, 2, ',', '') ?> DT pour la livraison gratuite</span>
      </div>
      <?php endif; ?>

      <div class="resume-ligne total">
        <span>Total</span>
        <span>
          <?= $total >= 30
            ? number_format($total, 2, ',', '')
            : number_format($total + 4.90, 2, ',', '') ?> DT
        </span>
      </div>

      <a href="paiement.php" class="btn-commander">✅ Commander</a>
      <a href="panier.php?action=vider" class="btn-vider"
         onclick="return confirm('Vider tout le panier ?')">🗑 Vider le panier</a>
      <a href="shop.php" class="btn-continuer">← Continuer mes achats</a>
    </aside>

  <?php endif; ?>

</div>

<footer>© 2025 GreenVerse</footer>

<script>
// + et - pour changer la quantité
function changeQty(btn, delta) {
  const input = btn.closest('form').querySelector('.qty-input');
  let val = parseInt(input.value) + delta;
  if (val < 1)  val = 1;
  if (val > 99) val = 99;
  input.value = val;
  input.form.submit();
}
</script>

</body>
</html>