<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ateliers - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>
/* =================== FLIP CARDS 3D =================== */
.flip-container {
  perspective: 1000px;        /* crée un effet de profondeur 3D */
  width: 300px;               /* largeur de chaque carte */
  height: 400px;              /* hauteur de chaque carte */
  margin: 30px;               /* espace autour des cartes */
}

.flip-card {
  width: 100%;                /* prend toute la largeur du conteneur */
  height: 100%;               /* prend toute la hauteur du conteneur */
  position: relative;         /* permet de positionner les faces avant/arrière */
  transition: transform 0.8s; /* animation fluide lors de la rotation */
  transform-style: preserve-3d; /* conserve l’effet 3D */
}

.flip-container:hover .flip-card {
  transform: rotateY(180deg); /* retourne la carte au passage de la souris */
}

.flip-front, .flip-back {
  position: absolute;         /* superpose les deux faces */
  width: 100%;                /* même largeur */
  height: 100%;               /* même hauteur */
  border-radius: 20px;        /* coins arrondis */
  backface-visibility: hidden;/* cache la face non visible */
  box-shadow: 0 5px 20px rgba(0,0,0,0.1); /* ombre légère */
  overflow: hidden;           /* empêche le débordement du contenu */
}

.flip-front img {
  width: 80%;                 /* largeur de l’image */
  height: 200px;              /* hauteur fixe */
  object-fit: cover;          /* évite la déformation de l’image */
  border-radius: 15px;        /* coins arrondis de l’image */
  margin-bottom: 15px;        /* espace sous l’image */
}

.flip-front:hover img {
  transform: scale(1.05);     /* zoom léger sur l’image au survol */
}

.flip-front h3 {
  margin-bottom: 5px;         /* petit espace sous le titre */
}

/* =================== COULEURS PASTEL =================== */

.flip-front { 
  display: flex;              /* active Flexbox */
  flex-direction: column;     /* éléments en colonne */
  justify-content: center;    /* centre verticalement */
  align-items: center;        /* centre horizontalement */
}

.flip-back { 
  transform: rotateY(180deg); /* place correctement la face arrière */
  padding: 15px;              /* espace intérieur */
  display: flex;              /* active Flexbox */
  flex-direction: column;     /* contenu en colonne */
  justify-content: space-between; /* espace entre les éléments */
}

.flip-front.fleurs { 
  background: #FFDDEE;        /* couleur de fond fleurs */
  color: #880E4F;             /* couleur du texte */
}
.flip-back.fleurs { 
  background: #FFE6F0; 
  color: #880E4F; 
}

.flip-front.potager { 
  background: #D0F0C0; 
  color: #2E7D32; 
}
.flip-back.potager { 
  background: #E0F7D4; 
  color: #2E7D32; 
}

.flip-front.aromatiques { 
  background: #FFF8C6; 
  color: #F57F17; 
}
.flip-back.aromatiques { 
  background: #FFFCE0; 
  color: #F57F17; 
}

.flip-front.arbustes { 
  background: #FDD9FF; 
  color: #6A1B9A; 
}
.flip-back.arbustes { 
  background: #FEE6FF; 
  color: #6A1B9A; 
}

.flip-front.arbres { 
  background: #CDE7FF; 
  color: #0D47A1; 
}
.flip-back.arbres { 
  background: #E0F0FF; 
  color: #0D47A1; 
}

.flip-front.aquatiques { 
  background: #C6F0FF; 
  color: #01579B; 
}
.flip-back.aquatiques { 
  background: #E0F8FF; 
  color: #01579B; 
}

.flip-front.grimpantes { 
  background: #EAC6FF; 
  color: #6A1B9A; 
}
.flip-back.grimpantes { 
  background: #F4D9FF; 
  color: #6A1B9A; 
}


.flip-back .btn {
  align-self: center;         /* centre le bouton */
  margin-top: 5px;            /* espace au-dessus du bouton */
}

.cards-container {
  display: flex;              /* affiche les cartes en ligne */
  flex-wrap: wrap;            /* retour à la ligne automatique */
  justify-content: center;    /* centre les cartes */
  gap: 30px;                  /* espace entre les cartes */
  padding: 60px 20px;         /* espace intérieur */
}

/* HERO */
.hero { height: 50vh; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; position:relative; overflow:hidden; padding-top:70px;}
.hero h1 { font-size:3em; color:#1b5e20; margin-bottom:20px; font-family:'Poppins',sans-serif;}
.hero p { font-size:1.3em; margin-bottom:30px; color:#2e7d32;}
</style>
</head>
<body>

<!-- NAVBAR -->
<?php include 'navbar.php'; ?>

 <section class="hero">
  <h1>Ateliers GreenVerse</h1>
  <p>Des ateliers interactifs pour apprendre à cultiver et composer vos plantes 🌿</p>
</section>

 <section class="cards-container">

 <div class="flip-container">
  <div class="flip-card">

     <div class="flip-front fleurs">
       <img src="images/bouqets-removebg-preview.png" alt="Bouquet">
      <h3>Création de Bouquet</h3>
    </div>

     <div class="flip-back fleurs">
      <p>Apprenez à composer de magnifiques bouquets de fleurs 🌹</p>
      <p><strong>Date :</strong> 20 Décembre 2025</p>
<a 
        href="https://www.youtube.com/embed/-w0CbfWhk2A"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>
 

      <a href="inscription.html" class="btn">S'inscrire</a>
    </div>

  </div>
</div>

 



<div class="flip-container">
  <div class="flip-card">

    <div class="flip-front potager">
      <img src="images/atelierplante.jpg" alt="Potager">
      <h3>Plantes Potagères</h3>
    </div>

    <div class="flip-back potager">
      <p>Techniques pour cultiver tomates, carottes et laitues 🍅🥕🥬</p>
      <p><strong>Date :</strong> 27 Décembre 2025</p>

       <a 
        href="https://www.youtube.com/shorts/OZC5Jk3wEJw"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>

      <a href="inscription.html" class="btn">S'inscrire</a>
    </div>

  </div>
</div>


 


<div class="flip-container">
    <div class="flip-card">
      <div class="flip-front aromatiques">
        <img src="images/atelier-naturels.jpg" alt="Aromatiques">
        <h3>Plantes Aromatiques</h3>
      </div>
      <div class="flip-back aromatiques">
        <p>Découvrez l’utilisation de basilic, menthe et thym 🌿</p>
        <p><strong>Date :</strong> 3 Janvier 2026</p>
 <a 
        href="https://www.youtube.com/watch?v=p8fy_SM5G6M"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>
   
        <a href="inscription.html" class="btn">S'inscrire</a>
      </div>
    </div>
  </div>
 

 
 
 
  <div class="flip-container">
    <div class="flip-card">
      <div class="flip-front arbres">
        <img src="images/arbres.jpg" alt="Arbres">
        <h3>Arbres Fruitiers</h3>
      </div>
      <div class="flip-back arbres">
        <p>Techniques pour pommiers, cerisiers et érables 🍏🍒🍁</p>
        <p><strong>Date :</strong> 17 Janvier 2026</p>

 <a 
        href="https://www.youtube.com/shorts/JETRLuVTJNs"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>
         
        <a href="inscription.html" class="btn">S'inscrire</a>
      </div>
    </div>
  </div>

 
 
 
 
 
 
  <div class="flip-container">
    <div class="flip-card">
      <div class="flip-front aquatiques">
        <img src="images/aquatique.jpg" alt="Aquatiques">
        <h3>Plantes Aquatiques</h3>
      </div>
      <div class="flip-back aquatiques">
        <p>Apprenez à entretenir nénuphars et lotus dans votre bassin 💧</p>
        <p><strong>Date :</strong> 24 Janvier 2026</p>

 <a 
        href="https://www.youtube.com/watch?v=Vuqtg6CuzUM"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>
        <a href="inscription.html" class="btn">S'inscrire</a>
      </div>
    </div>
  </div>

 
 
 
 
 
 
  <div class="flip-container">
    <div class="flip-card">
      <div class="flip-front grimpantes">
        <img src="images/plantes.avif" alt="Grimpantes">
        <h3>Plantes Grimpantes</h3>
      </div>
      <div class="flip-back grimpantes">
        <p>Découvrez comment cultiver lierre, glycine et vigne 🌿</p>
        <p><strong>Date :</strong> 31 Janvier 2026</p>
       
        <a 
        href=" https://www.youtube.com/watch?v=g7VNwU3KuDc"
        target="_blank"
        class="btn">
        ▶ Voir la vidéo
      </a>
        <a href="inscription.html" class="btn">S'inscrire</a>
      </div>
    </div>
  </div>

</section>

<footer>© 2025 GreenVerse</footer>
</body>
</html>
