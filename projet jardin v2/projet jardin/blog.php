<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>
/* =================== BLOG / CONSEILS =================== */
.blog-hero {
  height: 50vh;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  text-align:center;
  position:relative;
  background: linear-gradient(120deg, #f0fff0, #e6f2ff);
  padding-top:70px;
}

.blog-hero h1 {
  font-size:3em;
  color:#2e7d32;
  margin-bottom:20px;
  font-family:'Poppins',sans-serif;
}

.blog-hero p {
  font-size:1.3em;
  color:#1b5e20;
}

/* CARTES ARTICLES */
.articles-container {
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:30px;
  padding:60px 20px;
}

.article-card {
  width: 300px;
  background: #fffaf0;
  border-radius:20px;
  box-shadow:0 5px 20px rgba(0,0,0,0.1);
  overflow:hidden;
  transition: transform 0.5s, box-shadow 0.5s, background 0.5s;
  text-align:center;
}

.article-card:hover {
  transform: scale(1.05);
  box-shadow:0 15px 35px rgba(0,0,0,0.3);
  background: #f0ffe0;
}

.article-card img {
  width:100%;
  height:180px;
  object-fit: cover;
  border-bottom: 2px solid #d4f5c4;
  transition: transform 0.3s;
}

.article-card:hover img {
  transform: scale(1.05);
}

.article-card h3 {
  color:#2e7d32;
  margin:15px 0 5px 0;
  font-family:'Poppins',sans-serif;
}

.article-card p {
  color:#4CAF50;
  font-size:0.95em;
  margin-bottom:10px;
  padding: 0 10px;
}

.article-card .btn {
  background:#2e7d32;
  color:white;
  padding:10px 20px;
  border-radius:25px;
  font-weight:bold;
  text-decoration:none;
  margin-bottom:15px;
  display:inline-block;
  transition:0.3s;
}

.article-card .btn:hover {
  background:#1f6f43;
  transform: scale(1.05);
}

/* Responsive */
@media (max-width: 600px) {
  .articles-container { flex-direction: column; align-items:center; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

<!-- HERO -->
<section class="blog-hero">
  <h1>Blog GreenVerse</h1>
  <p>Articles et conseils pour jardiner facilement et durablement 🌿</p>
</section>

<!-- ARTICLES -->
<section class="articles-container">

  <!-- Article 1 -->
  <div class="article-card">
    <img src="images/tomate.jpg" alt="Tomates">
    <h3>Conseils pour vos tomates 🍅</h3>
    <p>Découvrez comment arroser, nourrir et protéger vos tomates contre les maladies.</p>
    <a href="https://www.gammvert.fr/conseils-idees/10-astuces-pour-de-belles-tomates" class="btn">Lire l'article</a>
  </div>

  <!-- Article 2 -->
  <div class="article-card">
    <img src="images/bouquet.jpg" alt="Bouquet">
    <h3>Créer un bouquet parfait 🌹</h3>
    <p>Apprenez à composer un bouquet de fleurs fraîches pour toutes les occasions.</p>
    <a href="https://www.agitateur-floral.com/blog/comment-creer-un-bouquet-de-fleurs-epoustouflant-en-dix-etapes-simples-n37" class="btn">Lire l'article</a>
  </div>

  <!-- Article 3 -->
  <div class="article-card">
    <img src="images/aromatique.avif" alt="Aromatiques">
    <h3>Plantes aromatiques 🌿</h3>
    <p>Comment cultiver et utiliser le basilic, la menthe et le thym dans votre cuisine.</p>
    <a href="https://plantes-avenue.fr/blog/guide-complet-les-plantes-aromatiques-n265?srsltid=AfmBOoq6q7qLQETvoqxfYN9ZAaN7tKH3SH7Jes9nbLA4yAaJaj9gcBJh" class="btn">Lire l'article</a>
  </div>

  <!-- Article 4 -->
  <div class="article-card">
    <img src="images/fruitiers.jpg" alt="Arbres">
    <h3>Arbres fruitiers 🍏</h3>
    <p>Techniques pour planter, entretenir et récolter vos pommiers, cerisiers et plus.</p>
    <a href="https://www.gammvert.fr/conseils-idees/planter-les-arbres-fruitiers" class="btn">Lire l'article</a>
  </div>

  
</section>

<footer>© 2025 GreenVerse</footer>
</body>
</html>
