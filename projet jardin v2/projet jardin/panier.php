<?php
session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panier - GreenVerse</title>
<link rel="stylesheet" href="style.css">
<style>

/* ===== HERO ===== */
.cart-hero {
    display:flex;
    justify-content:center;
    align-items:center;
    padding:50px;
    background: linear-gradient(120deg, #d8f3dc, #e9f7ef); /* vert clair */
    border-radius:25px;
    margin:20px;
    text-align:center;
}
.cart-hero h1 { font-size:2.8em; margin-bottom:20px; color:#2d6a4f; } /* vert foncé */
.cart-hero p { font-size:1.3em; color:#555; }

/* ===== PANIER ===== */
.cart-container {
    display:flex;
    justify-content:center;
    align-items:flex-start;
    gap:50px;
    flex-wrap:wrap;
    padding:20px;
    margin:20px;
}

/* ===== TABLEAU ===== */
.cart-table {
    width:800px;
    border-collapse: collapse;
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}
.cart-table th, .cart-table td {
    padding:15px;
    text-align:center;
    border-bottom:1px solid #f0f0f5;
}
.cart-table th {
    background:#2d6a4f; /* vert foncé */
    color:white;
    font-weight:600;
    letter-spacing:0.5px;
}
.cart-table td img { width:80px; border-radius:12px; }
.cart-table tr:hover { background:#e9f7ef; } /* vert très clair */

/* ===== INPUT NUMBER ===== */
.qty-input {
    width:60px;
    padding:5px;
    border-radius:8px;
    border:1px solid #ccc;
    text-align:center;
    font-size:1em;
}

/* ===== BOUTON SUPPRIMER ===== */
.remove-btn {
    background:#e63946; /* rouge doux */
    color:white;
    padding:7px 12px;
    border-radius:12px;
    cursor:pointer;
}
.remove-btn:hover { background:#d62839; }

/* ===== TOTAL ===== */
.cart-summary {
    width:400px;
    background: linear-gradient(135deg, #2d6a4f, #52b788); /* vert foncé -> vert clair */
    padding:25px 20px;
    border-radius:25px;
    color:white;
    text-align:center;
    
}
.cart-summary h3 { margin-bottom:20px; }
.cart-summary a {
    display:inline-block;
    padding:12px 25px;
    background:white;
    color:#2d6a4f;
    font-weight:bold;
    border-radius:30px;
    transition:0.3s;
}
.cart-summary a:hover { background:#d8f3dc; }

.cart-image {
    width: 400px;
    height: auto;
    border-radius: 25px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    display:block;
    margin: 0 auto 20px auto;  
}
 html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

body > .cart-container {
    flex: 1; 
}




</style>
</head>
<body>


<!-- NAVBAR -->
<?php include 'navbar.php'; ?>
<!-- HERO -->
<section class="cart-hero">
  <div>
    <h1>Votre Panier</h1>
    <p>Vérifiez vos articles avant de passer à la caisse 🌿</p>
  </div>
</section>

<!-- PANIER + IMAGE -->
<div class="cart-container">

  <!-- TABLEAU PANIER -->
  <table class="cart-table">
    <tr>
      <th>Produit</th>
      <th>Nom</th>
      <th>Prix</th>
      <th>Quantité</th>
      <th>Supprimer</th>
    </tr>
    <tr>
      <td><img src="images/garaines.jpg" alt="Graines"></td>
      <td>Graines de fleurs</td>
      <td>3€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/kit.jpg" alt="Kit Potager"></td>
      <td>Kit potager</td>
      <td>15€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/outils.jpg" alt="Outils"></td>
      <td>Outils de jardinage</td>
      <td>10€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/plantes interieur.jpg" alt="Plantes d'intérieur"></td>
      <td>Plantes d'intérieur</td>
      <td>8€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/kit2.avif" alt="Kits DIY"></td>
      <td>Kits DIY</td>
      <td>12€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/engrais bio.jpg" alt="Engrais bio"></td>
      <td>Engrais bio</td>
      <td>5€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/pots.jpg" alt="Pots de fleurs"></td>
      <td>Pots de fleurs</td>
      <td>4€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
    <tr>
      <td><img src="images/sao.jpg" alt="Système d'arrosage automatique"></td>
      <td>Système d'arrosage automatique</td>
      <td>20€</td>
      <td><input type="number" min="1" value="1" class="qty-input"></td>
      <td><button class="remove-btn">✖</button></td>
    </tr>
  </table>

  <!-- IMAGE + TOTAL -->
<div>
  <img src="images/panier.png" alt="Panier" class="cart-image">
  <div class="cart-summary">
    <h3>Total: 77€</h3>
    <a href="#">Passer à la caisse</a>
  </div>
</div>


</div>

<footer style="text-align:center; margin:50px 0; color:#555;">© 2025 GreenVerse</footer>

</body>
</html>
