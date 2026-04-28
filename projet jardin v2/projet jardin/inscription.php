<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>
/* =================== PAGE INSCRIPTION =================== */
.inscription-section {
  max-width: 700px;
  margin: 100px auto 50px auto;
  padding: 40px;
  background: #FDF6F0;
  border-radius: 25px;
  box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.inscription-section h1 {
  text-align: center;
  color: #2e7d32;
  font-size: 2.8em;
  margin-bottom: 20px;
  font-family: 'Poppins', sans-serif;
}

.inscription-section p {
  text-align: center;
  font-size: 1.2em;
  color: #4CAF50;
  margin-bottom: 30px;
  font-style: italic;
}

form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

input, select, textarea, button {
  width: 100%;
  padding: 14px 18px;
  border-radius: 15px;
  border: 1px solid #ccc;
  font-size: 1em;
  transition: all 0.3s ease;
  outline: none;
}

input:focus, select:focus, textarea:focus {
  border-color: #2e7d32;
  box-shadow: 0 0 12px rgba(46,139,87,0.25);
  transform: translateY(-2px);
}

button {
  background: #2e7d32;
  color: white;
  padding: 16px 32px;
  border-radius: 35px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  font-size: 1em;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(46,139,87,0.3);
}

button:hover {
  background: #1f6f43;
  transform: scale(1.08) translateY(-2px);
  box-shadow: 0 8px 20px rgba(46,139,87,0.4);
}

/* Responsive */
@media (max-width: 600px) {
  .form-row { flex-direction: column; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="hero">
  <h1>Inscription aux Ateliers</h1>
  <p>Remplissez le formulaire pour participer à nos ateliers interactifs 🌿</p>
</section>

<!-- FORMULAIRE D'INSCRIPTION -->
<section class="inscription-section">
  <h1>Formulaire d'inscription</h1>
  <p>Choisissez votre atelier et inscrivez-vous facilement</p>
  
  <form action="#" method="post">
    <div class="form-row">
      <input type="text" name="nom" placeholder="Nom complet" required>
      <input type="email" name="email" placeholder="Email" required>
    </div>

    <div class="form-row">
      <select name="atelier" required>
        <option value="">Sélectionnez un atelier</option>
        <option value="fleurs">Création de bouquet</option>
        <option value="potager">Plantes potagères</option>
        <option value="aromatiques">Plantes aromatiques</option>
         <option value="arbres">Arbres fruitiers</option>
        <option value="aquatiques">Plantes aquatiques</option>
        <option value="grimpantes">Plantes grimpantes</option>
      </select>
    </div>

    <div class="form-row">
      <label><input type="checkbox" name="newsletter"> Je souhaite recevoir les infos sur les ateliers</label>
    </div>

    <div class="form-row">
      <textarea name="message" placeholder="Message (facultatif)"></textarea>
    </div>

    <button type="submit">S'inscrire</button>
  </form>
</section>

<footer>© 2025 GreenVerse</footer>
</body>
</html>
