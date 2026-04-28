
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contact - GreenVerse</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Contact - GreenVerse</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<!-- NAVBAR -->
<?php include 'navbar.php'; ?>
<section class="contact-section">
  <h1>Bienvenue au Jardin de GreenVerse 🌱</h1>
  <p class="intro">
    Explorez notre univers végétal et laissez-vous inspirer. Que ce soit pour une question, une visite ou un partenariat, remplissez ce formulaire et nous vous répondrons avec passion.
  </p>

  <form>
    
    <!-- Nom et Prénom sur la même ligne -->
<div class="form-row">
  <input type="text" name="first_name" placeholder="Prénom" required>
  <input type="text" name="last_name" placeholder="Nom" required>
</div>

<!-- Numéro et Email sur la même ligne -->
<div class="form-row">
  <input type="tel" name="phone" placeholder="Numéro de téléphone">
  <input type="email" name="email" placeholder="Email" required>
</div>

    <!-- Genre -->
    <div class="form-group">
      <label>Genre :</label>
      <div class="radio-group">
        <label><input type="radio" name="gender" value="homme"> Homme</label>
        <label><input type="radio" name="gender" value="femme"> Femme</label>
      </div>
    </div>

    <!-- Catégorie préférée -->
    <div class="form-group">
      <label>Votre catégorie préférée :</label>
      <select name="category">
        <option value="">Sélectionnez</option>
        <option value="fleurs">Fleurs</option>
        <option value="potager">Plantes potagères</option>
        <option value="aromatiques">Aromatiques</option>
        <option value="arbustes">Arbustes</option>
        <option value="arbres">Arbres</option>
        <option value="aquatiques">Plantes aquatiques</option>
        <option value="grimpantes">Plantes grimpantes</option>
      </select>
    </div>

    <!-- Raison du contact -->
    <div class="form-group">
      <label>Raison du contact :</label>
      <div class="checkbox-group">
        <label><input type="checkbox" name="reason" value="infos"> Informations</label>
        <label><input type="checkbox" name="reason" value="partenariat"> Partenariat</label>
        <label><input type="checkbox" name="reason" value="visite"> Visite</label>      </div>
    </div>

    <!-- Date et Heure de visite -->
    <div class="form-row">
      <div class="form-group">
        <label>Date de visite souhaitée :</label>
        <input type="date" name="visit_date">
      </div>
      <div class="form-group">
        <label>Heure de visite :</label>
        <input type="time" name="visit_time">
      </div>
    </div>

    <!-- Nombre de visiteurs -->
    <div class="form-group">
      <label>Nombre de visiteurs :</label>
      <input type="number" name="guests" min="1" placeholder="Ex : 2">
    </div>

    <!-- Type de visite -->
    <div class="form-group">
      <label>Type de visite :</label>
      <select name="visit_type">
        <option value="">Sélectionnez</option>
        <option value="guidée">Guidée</option>
        <option value="libre">Libre</option>
      </select>
    </div>

    <!-- Ville ou région -->
    <div class="form-group">
      <label for="city">Ville ou région :</label>
      <input type="text" id="city" name="city" placeholder="Votre ville ou région">
    </div>

    <!-- Comment avez-vous connu GreenVerse ? -->
    <div class="form-group">
      <label for="source">Comment avez-vous connu GreenVerse ?</label>
      <select id="source" name="source">
        <option value="">Sélectionnez</option>
        <option value="reseaux">Réseaux sociaux</option>
        <option value="ami">Un ami</option>
        <option value="site">Site web</option>
        <option value="publicite">Publicité</option>
        <option value="autre">Autre</option>
      </select>
    </div>

    <!-- Message -->
    <div class="form-group">
      <label>Votre message :</label>
      <textarea name="message" placeholder="Écrivez ici..." required></textarea>
    </div>

    <!-- Bouton -->
    <div class="form-group">
      <button type="submit" class="btn">Envoyer</button>
    </div>
  </form>
</section>

</body>
</html>
