<?php
session_start();

$host = "localhost";
$dbname = "greenverse";
$user = "root";
$pass = "";
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// Création automatique des tables si elles n'existent pas
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

if (empty($_SESSION['cart_id'])) $_SESSION['cart_id'] = session_id();
$cart_id = $_SESSION['cart_id'];

// Charger le panier
$stmt = $pdo->prepare("SELECT * FROM panier WHERE session_id = ?");
$stmt->execute([$cart_id]);
$panier = $stmt->fetchAll();

if (empty($panier)) { header("Location: shop.php"); exit; }

// Calcul total
$total = 0;
foreach ($panier as $item) $total += $item['prix'] * $item['quantite'];
$livraison = $total >= 30 ? 0 : 4.90;
$total_ttc = $total + $livraison;

// Traitement formulaire
$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prenom        = trim($_POST['prenom'] ?? '');
    $nom           = trim($_POST['nom'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $telephone     = trim($_POST['telephone'] ?? '');
    $adresse       = trim($_POST['adresse'] ?? '');
    $ville         = trim($_POST['ville'] ?? '');
    $code_postal   = trim($_POST['code_postal'] ?? '');
    $pays          = trim($_POST['pays'] ?? 'France');
    $mode_paiement = trim($_POST['mode_paiement'] ?? '');

    if (!$prenom)    $erreurs[] = "Prénom obligatoire.";
    if (!$nom)       $erreurs[] = "Nom obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "Email invalide.";
    if (!$adresse)   $erreurs[] = "Adresse obligatoire.";
    if (!$ville)     $erreurs[] = "Ville obligatoire.";
    if (!$code_postal) $erreurs[] = "Code postal obligatoire.";
    if (!in_array($mode_paiement, ['carte', 'virement', 'livraison'])) $erreurs[] = "Mode de paiement obligatoire.";

    if (empty($erreurs)) {
        // Sauvegarder commande
        $stmt = $pdo->prepare("INSERT INTO commandes (session_id, nom, prenom, email, telephone, adresse, ville, code_postal, pays, mode_paiement, total_ht, livraison, total_ttc) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$cart_id, $nom, $prenom, $email, $telephone, $adresse, $ville, $code_postal, $pays, $mode_paiement, $total, $livraison, $total_ttc]);
        $commande_id = $pdo->lastInsertId();

        // Sauvegarder articles
        $ins = $pdo->prepare("INSERT INTO commande_items (commande_id, item_id, item_type, nom, prix, quantite, sous_total) VALUES (?,?,?,?,?,?,?)");
        foreach ($panier as $item) {
            $ins->execute([$commande_id, $item['item_id'], $item['item_type'], $item['nom'], $item['prix'], $item['quantite'], $item['prix'] * $item['quantite']]);
        }

        // Vider panier
        $pdo->prepare("DELETE FROM panier WHERE session_id = ?")->execute([$cart_id]);

        $_SESSION['commande_id'] = $commande_id;
        header("Location: confirmation.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paiement - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
.hero {
  min-height: 25vh;
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  text-align: center;
  background: linear-gradient(120deg, #f0fff0, #d4f5c4);
  padding-top: 80px;
}
.hero h1 { font-size: 2.4em; color: #2e7d32; font-family: 'Poppins', sans-serif; }
.hero p  { color: #1b5e20; }

.wrapper {
  max-width: 950px;
  margin: 36px auto 80px;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 24px;
  align-items: start;
}
@media (max-width: 700px) { .wrapper { grid-template-columns: 1fr; } }

.bloc {
  background: #fffaf0;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 24px;
  margin-bottom: 20px;
}
.bloc h2 {
  color: #2e7d32; font-family: 'Poppins', sans-serif;
  font-size: 1.05em; margin: 0 0 16px;
  padding-bottom: 10px; border-bottom: 2px solid #d4f5c4;
}
.row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 480px) { .row { grid-template-columns: 1fr; } }

.groupe { display: flex; flex-direction: column; gap: 4px; margin-bottom: 12px; }
.groupe label { font-family: 'Poppins', sans-serif; font-size: 0.82em; font-weight: 600; color: #555; }
.groupe input, .groupe select {
  padding: 10px 12px;
  border: 2px solid #d4f5c4; border-radius: 10px;
  font-family: 'Poppins', sans-serif; font-size: 0.9em;
  background: white; color: #333;
}
.groupe input:focus, .groupe select:focus { outline: none; border-color: #2e7d32; }

/* Modes paiement */
.mode {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 14px;
  border: 2px solid #d4f5c4; border-radius: 12px;
  cursor: pointer; margin-bottom: 10px;
  transition: 0.2s; font-family: 'Poppins', sans-serif;
}
.mode:hover    { border-color: #2e7d32; background: #f1faf1; }
.mode.actif    { border-color: #2e7d32; background: #e8f5e9; }
.mode input    { display: none; }
.mode .icone   { font-size: 1.5em; }
.mode strong   { display: block; font-size: 0.9em; color: #2e7d32; }
.mode span     { font-size: 0.78em; color: #888; }

/* Champs carte */
.carte-fields { display: none; background: #f9f9f9; border: 2px solid #d4f5c4; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
.carte-fields.visible { display: block; }

/* Erreurs */
.erreurs { background: #ffebee; border: 2px solid #e53935; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; }
.erreurs p { color: #c62828; font-family: 'Poppins', sans-serif; font-size: 0.85em; margin: 2px 0; }

/* Bouton payer */
.btn-payer {
  display: block; width: 100%;
  background: #2e7d32; color: white; border: none;
  padding: 14px; border-radius: 25px;
  font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1em;
  cursor: pointer; transition: 0.3s; margin-top: 6px;
}
.btn-payer:hover { background: #1f6f43; }

/* Récap */
.recap { background: #fffaf0; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 24px; position: sticky; top: 90px; }
.recap h2 { color: #2e7d32; font-family: 'Poppins', sans-serif; font-size: 1.05em; margin: 0 0 14px; padding-bottom: 10px; border-bottom: 2px solid #d4f5c4; }
.recap-item { display: flex; justify-content: space-between; font-family: 'Poppins', sans-serif; font-size: 0.85em; color: #555; margin-bottom: 8px; }
.recap-item .nom { flex: 1; }
.recap-item .qty { color: #aaa; margin: 0 8px; }
.recap-item .prix { font-weight: 700; color: #2e7d32; }
.ligne { display: flex; justify-content: space-between; font-family: 'Poppins', sans-serif; font-size: 0.88em; color: #666; margin-bottom: 8px; }
.ligne.total { border-top: 2px solid #d4f5c4; padding-top: 12px; margin-top: 6px; font-weight: 700; font-size: 1em; color: #2e7d32; }
.securite { text-align: center; font-family: 'Poppins', sans-serif; font-size: 0.75em; color: #aaa; margin-top: 14px; }
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<section class="hero">
  <h1>💳 Paiement</h1>
  <p>Finalisez votre commande en toute sécurité 🔒</p>
</section>

<div class="wrapper">

  <!-- FORMULAIRE -->
  <div>
    <form method="POST" action="paiement.php">

      <?php if (!empty($erreurs)): ?>
      <div class="erreurs">
        <?php foreach ($erreurs as $e): ?>
          <p>⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Infos personnelles -->
      <div class="bloc">
        <h2>👤 Informations personnelles</h2>
        <div class="row">
          <div class="groupe">
            <label>Prénom *</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" placeholder="Jean">
          </div>
          <div class="groupe">
            <label>Nom *</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" placeholder="Dupont">
          </div>
        </div>
        <div class="row">
          <div class="groupe">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="jean@email.com">
          </div>
          <div class="groupe">
            <label>Téléphone</label>
            <input type="tel" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" placeholder="06 00 00 00 00">
          </div>
        </div>
      </div>

      <!-- Adresse -->
      <div class="bloc">
        <h2>📦 Adresse de livraison</h2>
        <div class="groupe">
          <label>Adresse *</label>
          <input type="text" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>" placeholder="12 rue des Fleurs">
        </div>
        <div class="row">
          <div class="groupe">
            <label>Code postal *</label>
            <input type="text" name="code_postal" value="<?= htmlspecialchars($_POST['code_postal'] ?? '') ?>" placeholder="75000">
          </div>
          <div class="groupe">
            <label>Ville *</label>
            <input type="text" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>" placeholder="Paris">
          </div>
        </div>
        <div class="groupe">
          <label>Pays</label>
          <select name="pays">
            <?php foreach (['France','Belgique','Suisse','Luxembourg','Tunisie','Maroc','Algérie'] as $p): ?>
              <option <?= (($_POST['pays'] ?? 'France') === $p) ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Mode de paiement -->
      <div class="bloc">
        <h2>💳 Mode de paiement</h2>
        <input type="hidden" name="mode_paiement" id="mode_paiement" value="<?= htmlspecialchars($_POST['mode_paiement'] ?? '') ?>">

        <div class="mode <?= (($_POST['mode_paiement'] ?? '') === 'carte') ? 'actif' : '' ?>" onclick="choisirMode('carte', this)">
          <span class="icone">💳</span>
          <div>
            <strong>Carte bancaire</strong>
            <span>Visa, Mastercard, CB</span>
          </div>
        </div>

        <div class="carte-fields <?= (($_POST['mode_paiement'] ?? '') === 'carte') ? 'visible' : '' ?>" id="carte-fields">
          <div class="groupe">
            <label>Nom sur la carte</label>
            <input type="text" name="carte_nom" placeholder="JEAN DUPONT" value="<?= htmlspecialchars($_POST['carte_nom'] ?? '') ?>">
          </div>
          <div class="groupe">
            <label>Numéro de carte</label>
            <input type="text" name="carte_num" maxlength="19" placeholder="0000 0000 0000 0000" oninput="formatCarte(this)" value="<?= htmlspecialchars($_POST['carte_num'] ?? '') ?>">
          </div>
          <div class="row">
            <div class="groupe">
              <label>Expiration (MM/AA)</label>
              <input type="text" name="carte_exp" maxlength="5" placeholder="MM/AA" oninput="formatExp(this)" value="<?= htmlspecialchars($_POST['carte_exp'] ?? '') ?>">
            </div>
            <div class="groupe">
              <label>CVV</label>
              <input type="password" name="carte_cvv" maxlength="4" placeholder="•••" value="<?= htmlspecialchars($_POST['carte_cvv'] ?? '') ?>">
            </div>
          </div>
        </div>

        <div class="mode <?= (($_POST['mode_paiement'] ?? '') === 'virement') ? 'actif' : '' ?>" onclick="choisirMode('virement', this)">
          <span class="icone">🏦</span>
          <div>
            <strong>Virement bancaire</strong>
            <span>Traitement sous 2-3 jours</span>
          </div>
        </div>

        <div class="mode <?= (($_POST['mode_paiement'] ?? '') === 'livraison') ? 'actif' : '' ?>" onclick="choisirMode('livraison', this)">
          <span class="icone">🚚</span>
          <div>
            <strong>Paiement à la livraison</strong>
            <span>Payez en espèces à la réception</span>
          </div>
        </div>

        <button type="submit" class="btn-payer">
          🔒 Confirmer — <?= number_format($total_ttc, 2, ',', '') ?> DT
        </button>
      </div>

    </form>
  </div>

  <!-- RÉCAPITULATIF -->
  <aside class="recap">
    <h2>🛒 Récapitulatif</h2>

    <?php foreach ($panier as $item): ?>
    <div class="recap-item">
      <span class="nom"><?= htmlspecialchars($item['nom']) ?></span>
      <span class="qty">x<?= $item['quantite'] ?></span>
      <span class="prix"><?= number_format($item['prix'] * $item['quantite'], 2, ',', '') ?> DT</span>
    </div>
    <?php endforeach; ?>

    <hr style="border:none;border-top:1px solid #e8f5e9;margin:12px 0">

    <div class="ligne">
      <span>Sous-total</span>
      <span><?= number_format($total, 2, ',', '') ?> DT</span>
    </div>
    <div class="ligne">
      <span>Livraison</span>
      <?php if ($livraison == 0): ?>
        <span style="color:#2e7d32">Gratuite 🎉</span>
      <?php else: ?>
        <span><?= number_format($livraison, 2, ',', '') ?> DT</span>
      <?php endif; ?>
    </div>
    <div class="ligne total">
      <span>Total</span>
      <span><?= number_format($total_ttc, 2, ',', '') ?> DT</span>
    </div>

    <div class="securite">🔒 Paiement sécurisé</div>
    <a href="panier.php" style="display:block;text-align:center;margin-top:10px;color:#2e7d32;font-family:'Poppins',sans-serif;font-size:0.82em;text-decoration:none;">← Modifier le panier</a>
  </aside>

</div>

<footer>© 2025 GreenVerse</footer>

<script>
// Choisir mode de paiement
function choisirMode(mode, el) {
  document.querySelectorAll('.mode').forEach(m => m.classList.remove('actif'));
  el.classList.add('actif');
  document.getElementById('mode_paiement').value = mode;
  document.getElementById('carte-fields').classList.toggle('visible', mode === 'carte');
}

// Format numéro carte : 0000 0000 0000 0000
function formatCarte(input) {
  let val = input.value.replace(/\D/g, '').substring(0, 16);
  input.value = val.replace(/(.{4})/g, '$1 ').trim();
}

// Format expiration : MM/AA
function formatExp(input) {
  let val = input.value.replace(/\D/g, '').substring(0, 4);
  if (val.length >= 3) val = val.substring(0, 2) + '/' + val.substring(2);
  input.value = val;
}
</script>

</body>
</html>