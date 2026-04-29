<?php
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

$success = false;
$error = '';

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Créer la table inscriptions si elle n'existe pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inscriptions (
            id               INT AUTO_INCREMENT PRIMARY KEY,
            nom              VARCHAR(150)  NOT NULL,
            email            VARCHAR(150)  NOT NULL,
            atelier          VARCHAR(255)  NOT NULL,
            newsletter       TINYINT(1)    DEFAULT 0,
            message          TEXT,
            date_inscription DATETIME      DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

} catch (\PDOException $e) {
    $error = 'Erreur de connexion : ' . $e->getMessage();
}

// Récupérer les ateliers depuis la base
$ateliers = [];
if (!$error) {
    $ateliers = $pdo->query("SELECT id, titre FROM ateliers ORDER BY date_atelier ASC")->fetchAll();
}

// ============================================================
// TRAITEMENT DU FORMULAIRE
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $nom        = trim($_POST['nom'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $atelier    = trim($_POST['atelier'] ?? '');
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    $message    = trim($_POST['message'] ?? '');

    if (!$nom || !$email || !$atelier) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO inscriptions (nom, email, atelier, newsletter, message, date_inscription)
                VALUES (:nom, :email, :atelier, :newsletter, :message, NOW())
            ");
            $stmt->execute([
                ':nom'        => $nom,
                ':email'      => $email,
                ':atelier'    => $atelier,
                ':newsletter' => $newsletter,
                ':message'    => $message,
            ]);
            $success = true;
        } catch (\PDOException $e) {
            $error = 'Erreur lors de l\'inscription : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>
:root {
  --green-dark: #1b5e20;
  --green-mid: #2e7d32;
  --cream: #fafaf5;
  --font-display: 'Playfair Display', serif;
  --font-body: 'DM Sans', sans-serif;
}

.inscription-section {
  max-width: 700px;
  margin: 60px auto 80px auto;
  padding: 40px;
  background: #FDF6F0;
  border-radius: 25px;
  box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.inscription-section h1 {
  text-align: center;
  color: var(--green-dark);
  font-size: 2rem;
  margin-bottom: 10px;
  font-family: 'Playfair Display', serif;
}

.inscription-section > p {
  text-align: center;
  font-size: 1.05em;
  color: #4CAF50;
  margin-bottom: 30px;
  font-style: italic;
}

.form-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  margin-bottom: 16px;
}

.form-row input,
.form-row select,
.form-row textarea {
  flex: 1;
  min-width: 200px;
  padding: 14px 18px;
  border-radius: 15px;
  border: 1.5px solid #ccc;
  font-size: 1em;
  font-family: var(--font-body);
  background: #fafaf5;
  transition: all 0.3s ease;
  outline: none;
}

.form-row input:focus,
.form-row select:focus,
.form-row textarea:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 12px rgba(46,125,50,0.2);
  transform: translateY(-2px);
}

.form-row label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: .95rem;
  color: #444;
  cursor: pointer;
}

.form-row textarea {
  width: 100%;
  min-height: 110px;
  resize: vertical;
}

.btn-submit {
  width: 100%;
  background: var(--green-dark);
  color: white;
  padding: 16px 32px;
  border-radius: 50px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  font-size: 1em;
  font-family: var(--font-body);
  margin-top: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(27,94,32,0.3);
}

.btn-submit:hover {
  background: #1f6f43;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(27,94,32,0.4);
}

.alert {
  padding: 16px 22px;
  border-radius: 14px;
  margin-bottom: 24px;
  font-size: .95rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
}
.alert-error {
  background: #ffebee;
  color: #b71c1c;
  border: 1.5px solid #ef9a9a;
}

.success-card {
  text-align: center;
  padding: 20px 0 10px;
}
.success-card .big-icon { font-size: 4rem; margin-bottom: 16px; }
.success-card h2 {
  font-family: 'Playfair Display', serif;
  color: var(--green-dark);
  font-size: 1.7rem;
  margin-bottom: 10px;
}
.success-card p { color: #555; font-size: 1rem; margin-bottom: 24px; }
.btn-retour {
  display: inline-block;
  padding: 12px 32px;
  background: var(--green-dark);
  color: #fff;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  font-family: var(--font-body);
  transition: transform .2s, box-shadow .2s;
  box-shadow: 0 4px 16px rgba(27,94,32,.3);
}
.btn-retour:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(27,94,32,.4); }

@media (max-width: 600px) {
  .form-row { flex-direction: column; }
  .inscription-section { padding: 24px 18px; margin: 40px 16px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="hero">
  <h1>Inscription aux Ateliers</h1>
  <p>Remplissez le formulaire pour participer à nos ateliers interactifs 🌿</p>
</section>

<!-- FORMULAIRE -->
<section class="inscription-section">
  <h1>Formulaire d'inscription</h1>
  <p>Choisissez votre atelier et inscrivez-vous facilement</p>

  <?php if ($success): ?>
    <div class="success-card">
      <div class="big-icon">🎉</div>
      <h2>Inscription confirmée !</h2>
      <p>Merci <strong><?= htmlspecialchars($_POST['nom']) ?></strong>, votre inscription a bien été enregistrée.<br>
         Nous vous contacterons à <strong><?= htmlspecialchars($_POST['email']) ?></strong> pour les détails.</p>
      <a href="ateliers.php" class="btn-retour">🌿 Retour aux ateliers</a>
    </div>

  <?php else: ?>

    <?php if ($error): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="inscription.php" method="POST">

      <div class="form-row">
        <input type="text" name="nom" placeholder="Nom complet *"
               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Email *"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>

      <div class="form-row">
        <select name="atelier" required>
          <option value="">-- Sélectionnez un atelier --</option>
          <?php foreach ($ateliers as $a):
            $selected = (($_POST['atelier'] ?? '') === $a['titre']) ? 'selected' : '';
          ?>
            <option value="<?= htmlspecialchars($a['titre']) ?>" <?= $selected ?>>
              <?= htmlspecialchars($a['titre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <label>
          <input type="checkbox" name="newsletter"
                 <?= isset($_POST['newsletter']) ? 'checked' : '' ?>>
          Je souhaite recevoir les infos sur les ateliers
        </label>
      </div>

      <div class="form-row">
        <textarea name="message" placeholder="Message (facultatif)"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn-submit">🌿 S'inscrire</button>
    </form>

  <?php endif; ?>
</section>

<footer>© 2025 GreenVerse</footer>
</body>
</html>