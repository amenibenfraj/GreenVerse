<?php
session_start();

$host = "localhost";
$dbname = "greenverse";
$user = "root";
$pass = "";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// ================================================
//  CRÉATION AUTOMATIQUE DES TABLES SI ELLES N'EXISTENT PAS
// ================================================
$pdo->exec("
    CREATE TABLE IF NOT EXISTS commandes (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        session_id    VARCHAR(100) NOT NULL,
        nom           VARCHAR(100) NOT NULL,
        prenom        VARCHAR(100) NOT NULL,
        email         VARCHAR(150) NOT NULL,
        telephone     VARCHAR(20),
        adresse       VARCHAR(255) NOT NULL,
        ville         VARCHAR(100) NOT NULL,
        code_postal   VARCHAR(10)  NOT NULL,
        pays          VARCHAR(100) NOT NULL DEFAULT 'France',
        mode_paiement ENUM('carte','virement','livraison') NOT NULL,
        total_ht      DECIMAL(10,2) NOT NULL,
        livraison     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        total_ttc     DECIMAL(10,2) NOT NULL,
        statut        ENUM('en_attente','confirmee','expediee','livree','annulee') NOT NULL DEFAULT 'en_attente',
        created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS commande_items (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        commande_id  INT NOT NULL,
        item_id      INT NOT NULL,
        item_type    ENUM('produit','plante') NOT NULL,
        nom          VARCHAR(100) NOT NULL,
        prix         DECIMAL(10,2) NOT NULL,
        quantite     INT NOT NULL,
        sous_total   DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
    )
");

// Si pas de commande → retour boutique
if (empty($_SESSION['commande_id'])) {
    header("Location: shop.php");
    exit;
}

$commande_id = $_SESSION['commande_id'];
unset($_SESSION['commande_id']);

// Récupérer commande
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch();

// Récupérer articles
$stmt = $pdo->prepare("SELECT * FROM commande_items WHERE commande_id = ?");
$stmt->execute([$commande_id]);
$articles = $stmt->fetchAll();

$modes = [
    'carte'     => '💳 Carte bancaire',
    'virement'  => '🏦 Virement bancaire',
    'livraison' => '🚚 Paiement à la livraison',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
.hero {
  min-height: 28vh;
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  text-align: center;
  background: linear-gradient(120deg, #f0fff0, #d4f5c4);
  padding-top: 80px;
}
.hero .icone { font-size: 4em; animation: pop 0.5s ease; }
@keyframes pop {
  from { transform: scale(0); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}
.hero h1 { font-size: 2.2em; color: #2e7d32; font-family: 'Poppins', sans-serif; margin: 8px 0 4px; }
.hero p  { color: #1b5e20; font-size: 1em; }

/* Étapes */
.etapes {
  display: flex; justify-content: center; align-items: center;
  gap: 0; padding: 20px 20px 0;
  font-family: 'Poppins', sans-serif;
}
.etape { display: flex; align-items: center; gap: 6px; font-size: 0.82em; color: #81c784; font-weight: 700; }
.etape-num { width: 26px; height: 26px; border-radius: 50%; background: #2e7d32; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.85em; font-weight: 700; }
.etape-line { width: 50px; height: 2px; background: #2e7d32; margin: 0 6px; }

.wrapper {
  max-width: 700px;
  margin: 30px auto 80px;
  padding: 0 20px;
  display: flex; flex-direction: column; gap: 20px;
}

.bloc {
  background: #fffaf0;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 22px;
}
.bloc h2 {
  color: #2e7d32; font-family: 'Poppins', sans-serif;
  font-size: 1em; margin: 0 0 14px;
  padding-bottom: 10px; border-bottom: 2px solid #d4f5c4;
}

/* Numéro commande */
.num-commande {
  text-align: center; padding: 6px 0;
  font-family: 'Poppins', sans-serif;
}
.num-commande .num { font-size: 2.2em; font-weight: 700; color: #2e7d32; letter-spacing: 2px; }
.num-commande p { color: #888; font-size: 0.82em; margin-top: 4px; }

/* Infos client */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 20px; font-family: 'Poppins', sans-serif; }
.info-grid label { display: block; color: #888; font-size: 0.75em; margin-bottom: 2px; }
.info-grid span  { color: #333; font-weight: 600; font-size: 0.88em; }
@media (max-width: 480px) { .info-grid { grid-template-columns: 1fr; } }

/* Virement */
.virement {
  background: #e8f5e9; border-radius: 10px;
  padding: 14px 16px; margin-top: 12px;
  font-family: 'Poppins', sans-serif; font-size: 0.84em;
  color: #1b5e20; line-height: 1.8;
}
.virement strong { display: block; margin-bottom: 4px; }

/* Articles */
.article {
  display: flex; justify-content: space-between; align-items: center;
  font-family: 'Poppins', sans-serif; font-size: 0.86em;
  padding: 8px 0; border-bottom: 1px solid #e8f5e9;
}
.article:last-of-type { border-bottom: none; }
.article .nom  { flex: 1; color: #444; }
.article .qty  { color: #aaa; margin: 0 12px; }
.article .prix { font-weight: 700; color: #2e7d32; }

.ligne { display: flex; justify-content: space-between; font-family: 'Poppins', sans-serif; font-size: 0.88em; color: #666; margin-bottom: 8px; }
.ligne.total { border-top: 2px solid #d4f5c4; padding-top: 12px; margin-top: 6px; font-weight: 700; font-size: 1em; color: #2e7d32; }

/* Boutons */
.btns { display: flex; gap: 12px; flex-wrap: wrap; }
.btn-vert {
  flex: 1; display: block; background: #2e7d32; color: white;
  padding: 13px; border-radius: 25px; text-align: center;
  font-family: 'Poppins', sans-serif; font-weight: 700;
  text-decoration: none; transition: 0.3s;
}
.btn-vert:hover { background: #1f6f43; }
.btn-blanc {
  flex: 1; display: block; background: white; color: #2e7d32;
  border: 2px solid #2e7d32; padding: 13px; border-radius: 25px;
  text-align: center; font-family: 'Poppins', sans-serif; font-weight: 700;
  text-decoration: none; transition: 0.3s;
}
.btn-blanc:hover { background: #e8f5e9; }
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>
<section class="hero">
  <div class="icone">✅</div>
  <h1>Commande confirmée !</h1>
  <p>Merci <?= htmlspecialchars($commande['prenom']) ?>, votre commande a bien été enregistrée.</p>
</section>

<!-- Étapes -->
<div class="etapes">
  <div class="etape"><div class="etape-num">✓</div><span>Panier</span></div>
  <div class="etape-line"></div>
  <div class="etape"><div class="etape-num">✓</div><span>Paiement</span></div>
  <div class="etape-line"></div>
  <div class="etape"><div class="etape-num">✓</div><span>Confirmation</span></div>
</div>

<div class="wrapper">

  <!-- Numéro commande -->
  <div class="bloc">
    <h2>📋 Numéro de commande</h2>
    <div class="num-commande">
      <div class="num">#<?= str_pad($commande_id, 5, '0', STR_PAD_LEFT) ?></div>
      <p>Passée le <?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?></p>
    </div>
  </div>

  <!-- Infos client -->
  <div class="bloc">
    <h2>👤 Informations de livraison</h2>
    <div class="info-grid">
      <div>
        <label>Nom complet</label>
        <span><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></span>
      </div>
      <div>
        <label>Email</label>
        <span><?= htmlspecialchars($commande['email']) ?></span>
      </div>
      <div>
        <label>Téléphone</label>
        <span><?= htmlspecialchars($commande['telephone'] ?: '—') ?></span>
      </div>
      <div>
        <label>Mode de paiement</label>
        <span><?= $modes[$commande['mode_paiement']] ?? $commande['mode_paiement'] ?></span>
      </div>
      <div style="grid-column:1/-1">
        <label>Adresse</label>
        <span>
          <?= htmlspecialchars($commande['adresse']) ?>,
          <?= htmlspecialchars($commande['code_postal']) ?>
          <?= htmlspecialchars($commande['ville']) ?>,
          <?= htmlspecialchars($commande['pays']) ?>
        </span>
      </div>
    </div>

    <!-- Infos virement -->
    <?php if ($commande['mode_paiement'] === 'virement'): ?>
    <div class="virement">
      <strong>🏦 Coordonnées bancaires pour le virement :</strong>
      IBAN : FR76 0000 0000 0000 0000 0000 000<br>
      BIC : GREENVERSEFR<br>
      Référence : <strong>#<?= str_pad($commande_id, 5, '0', STR_PAD_LEFT) ?></strong><br>
      Montant : <strong><?= number_format($commande['total_ttc'], 2, ',', '') ?> DT</strong>
    </div>
    <?php endif; ?>
  </div>

  <!-- Articles commandés -->
  <div class="bloc">
    <h2>🛒 Articles commandés</h2>

    <?php foreach ($articles as $a): ?>
    <div class="article">
      <span class="nom"><?= htmlspecialchars($a['nom']) ?></span>
      <span class="qty">x<?= $a['quantite'] ?></span>
      <span class="prix"><?= number_format($a['sous_total'], 2, ',', '') ?> DT</span>
    </div>
    <?php endforeach; ?>

    <div style="margin-top:14px">
      <div class="ligne">
        <span>Sous-total</span>
        <span><?= number_format($commande['total_ht'], 2, ',', '') ?> DT</span>
      </div>
      <div class="ligne">
        <span>Livraison</span>
        <?php if ($commande['livraison'] == 0): ?>
          <span style="color:#2e7d32">Gratuite 🎉</span>
        <?php else: ?>
          <span><?= number_format($commande['livraison'], 2, ',', '') ?> DT</span>
        <?php endif; ?>
      </div>
      <div class="ligne total">
        <span>Total TTC</span>
        <span><?= number_format($commande['total_ttc'], 2, ',', '') ?> DT</span>
      </div>
    </div>
  </div>

  <!-- Boutons -->
  <div class="btns">
    <a href="shop.php"   class="btn-vert">🌿 Continuer mes achats</a>
    <a href="index.php" class="btn-blanc">🏠 Retour à l'accueil</a>
  </div>

</div>

<footer>© 2025 GreenVerse</footer>

</body>
</html>