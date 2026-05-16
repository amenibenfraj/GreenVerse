<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

// Si NON connecté → login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

// Si admin → dashboard
if ($_SESSION['user_role'] === 'admin') {
    header("Location: admindash.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<title>GreenVerse - Accueil</title>
<style>
body {
  margin:0;
  font-family:'Poppins',sans-serif;
  color:#2e7d32;
  background:#f9fef9;
}
a {text-decoration:none;}

.hero {
  height:90vh;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  text-align:center;
  background: linear-gradient(135deg,#d0f0f0,#e0ffe0);
  position:relative;
  overflow:hidden;
  padding-top:70px;
}
.hero h1 {
  font-size:3.5em;
  margin-bottom:20px;
  color:#2e7d32;
  text-shadow:0 0 15px rgba(46,125,50,0.5);
  animation: fadeInUp 1.5s ease forwards;
}
.hero p {
  font-size:1.5em;
  margin-bottom:30px;
  color:#388e3c;
  animation: fadeInUp 2s ease forwards;
  animation-delay:0.5s;
}
.btn {
  background:#4caf50;
  color:white;
  padding:15px 35px;
  border-radius:30px;
  font-weight:bold;
  margin:5px;
  transition:0.4s;
  box-shadow:0 5px 15px rgba(0,0,0,0.2);
}
.btn:hover {
  background:#2e7d32;
  transform: scale(1.05);
  box-shadow:0 8px 25px rgba(0,0,0,0.3);
}

.petal, .leaf {
  position:absolute;
  font-size:25px;
  pointer-events:none;
  animation: fall linear infinite;
}
.leaf { color:#81c784; }
.petal { color:#ffb6c1; }
@keyframes fall {
  0% { transform: translateY(-10%) rotate(0deg); opacity:1; }
  100% { transform: translateY(110vh) rotate(360deg); opacity:0; }
}

section {
  padding:60px 20px;
  opacity:0;
  animation: fadeIn 1.5s forwards;
}
section:nth-of-type(1){animation-delay:0.3s;}
section:nth-of-type(2){animation-delay:0.6s;}
section:nth-of-type(3){animation-delay:0.9s;}
section:nth-of-type(4){animation-delay:1.2s;}
@keyframes fadeIn { from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }

.services {
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:30px;
}
.service-card {
  background:#f0fff0;
  border-radius:20px;
  width:280px;
  padding:25px;
  text-align:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
  transition: transform 0.4s, box-shadow 0.4s;
}
.service-card:hover {
  transform: rotateX(5deg) scale(1.05);
  box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

.stats {
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:40px;
}
.stat-card {
  background:#f0fff0;
  border-radius:20px;
  padding:30px;
  width:220px;
  text-align:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
}
.stat-card h3 {
  font-size:2em;
  margin-bottom:10px;
  color:#4caf50;
}
.stat-card .bar {
  background:#c8e6c9;
  border-radius:20px;
  height:20px;
  margin-top:10px;
  overflow:hidden;
}
.stat-card .fill {
  height:100%;
  width:0;
  background:#4caf50;
  border-radius:20px;
  animation: fillBar 2s forwards;
}
@keyframes fillBar {
  from { width:0; }
  to { width: var(--percent); }
}

.map-container {
  max-width:900px;
  margin:auto;
  border-radius:20px;
  overflow:hidden;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
}
.map-container iframe { width:100%; height:400px; border:0; }

.review-section {
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:30px;
}
.review-card {
  background:#f0fff0;
  border-radius:20px;
  width:280px;
  padding:25px;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
  font-style:italic;
  position:relative;
  transition: transform 0.3s, box-shadow 0.3s;
}
.review-card:hover {
  transform: scale(1.03);
  box-shadow:0 15px 35px rgba(0,0,0,0.2);
}
.review-card::before {
  content:"\201C";
  font-size:3em;
  position:absolute;
  top:10px;
  left:15px;
  color:#4caf50;
}
.review-card::after {
  content:"\201D";
  font-size:3em;
  position:absolute;
  bottom:10px;
  right:15px;
  color:#4caf50;
}

footer {
  background:#2e7d32;
  color:white;
  text-align:center;
  padding:30px 20px;
}
footer form input {
  padding:10px;
  border-radius:30px;
  border:none;
  margin-right:10px;
}
footer form button {
  padding:10px 20px;
  border-radius:30px;
  border:none;
  background:#4caf50;
  color:white;
  cursor:pointer;
  transition:0.3s;
}
footer form button:hover { background:#2e7d32; }

@media(max-width:900px){
  .services,.review-section,.stats{flex-direction:column;align-items:center;}
}
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="hero">
  <h1>Bienvenue dans GreenVerse</h1>
  <p>Explorez votre jardin de manière interactive et ludique</p>
  <!-- <a href="jardin.php" class="btn">Explorer le Jardin</a> -->
  
  <div class="petal" style="left:10%; animation-duration:5s;">🌸</div>
  <div class="petal" style="left:25%; animation-duration:6s;">🌸</div>
  <div class="leaf"  style="left:50%; animation-duration:7s;">🍃</div>
  <div class="leaf"  style="left:70%; animation-duration:8s;">🍃</div>
  <div class="petal" style="left:85%; animation-duration:6.5s;">🌸</div>
</section>

<section>
  <h2>Nos services</h2>
  <div class="services">
    <div class="service-card"><h3>Ateliers</h3><p>Participez à nos ateliers interactifs pour découvrir la culture des plantes et fleurs.</p></div>
    <div class="service-card"><h3>Boutique</h3><p>Découvrez nos produits pour le jardinage et les plantes adaptées à votre espace.</p></div>
    <div class="service-card"><h3>Blog</h3><p>Conseils et astuces de jardinage pour entretenir vos plantes facilement.</p></div>
  </div>
</section>

<section>
  <h2>Statistiques du jardin</h2>
  <div class="stats">
    <div class="stat-card">
      <h3>75%</h3><p>Plantes fleuries</p>
      <div class="bar"><div class="fill" style="--percent:75%;"></div></div>
    </div>
    <div class="stat-card">
      <h3>60%</h3><p>Plantes arrosées</p>
      <div class="bar"><div class="fill" style="--percent:60%;"></div></div>
    </div>
    <div class="stat-card">
      <h3>1200</h3><p>Visiteurs ce mois</p>
      <div class="bar"><div class="fill" style="--percent:80%;"></div></div>
    </div>
  </div>
</section>

<section>
  <h2>Localisation du jardin</h2>
  <div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.019129431867!2d-122.41941548468153!3d37.774929779759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085814c7d0cbf43%3A0x4b32e0ee7a7a046f!2sSan%20Francisco!5e0!3m2!1sfr!2s!4v1699999999999!5m2!1sfr!2s" allowfullscreen="" loading="lazy"></iframe>
  </div>
</section>

<section>
  <h2>Avis des visiteurs</h2>
  <div class="review-section">
    <div class="review-card"><p>Super expérience ! J'ai appris beaucoup sur mes plantes.</p><strong>- Alice</strong></div>
    <div class="review-card"><p>GreenVerse est très intuitif et les astuces sont excellentes.</p><strong>- Karim</strong></div>
    <div class="review-card"><p>Mon jardin n'a jamais été aussi beau !</p><strong>- Leila</strong></div>
  </div>
</section>

<footer>
  <p>© 2025 GreenVerse</p>
  
</footer>

<script>
  // Bloquer retour par flèche après logout
  window.history.pushState(null, '', window.location.href);
  window.addEventListener('popstate', function() {
    window.history.pushState(null, '', window.location.href);
  });
</script>
</body>
</html>