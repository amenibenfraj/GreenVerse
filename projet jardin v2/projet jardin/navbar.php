<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<style>
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 30px;
  background: #4caf50;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  flex-wrap: nowrap;
}
.navbar .logo {
  font-size: 1.5em;
  color: white;
  font-weight: bold;
  text-decoration: none;
  white-space: nowrap;
}
.nav-links {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
  gap: 5px;
}
.nav-links a {
  color: white;
  font-weight: 500;
  transition: 0.3s;
  white-space: nowrap;
  font-size: 0.88em;
  text-decoration: none;
}
.nav-links a:hover { color: #c8e6c9; }

.user-menu {
  position: relative;
  display: inline-flex;
  align-items: center;
}
.user-trigger {
  color: white;
  font-weight: 600;
  cursor: pointer;
  padding: 6px 12px;
  border-radius: 20px;
  background: rgba(255,255,255,0.15);
  transition: background 0.3s;
  user-select: none;
  white-space: nowrap;
  font-size: 0.88em;
}
.user-trigger:hover { background: rgba(255,255,255,0.25); }

.user-card {
  display: none;
  position: absolute;
  top: calc(100% + 12px);
  right: 0;
  background: white;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15);
  min-width: 220px;
  padding: 16px;
  z-index: 9999;
  animation: dropDown 0.25s ease;
}
.user-card.show { display: block; }

@keyframes dropDown {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.user-card-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}
.user-avatar {
  font-size: 2em;
  background: #d8f3dc;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.user-card-header strong { display: block; color: #1b4332; font-size: 0.95em; }
.user-card-header small  { color: #6b7280; font-size: 0.78em; }
.user-card hr { border: none; border-top: 1px solid #e5e7eb; margin: 10px 0; }
.user-card-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  border-radius: 10px;
  color: #2d6a4f;
  font-size: 0.9em;
  font-weight: 500;
  text-decoration: none;
  transition: background 0.2s;
}
.user-card-link:hover  { background: #d8f3dc; }
.user-card-link.logout { color: #b91c1c; }
.user-card-link.logout:hover { background: #fff0f0; }
</style>


<nav class="navbar">
  <a href="index.php" class="logo"><span>🌱</span>GreenVerse</a>
  <div class="nav-links">

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
      <!-- MENU ADMIN -->
      <a href="admindash.php">Dashboard</a>
      <a href="jardin.php">Jardin</a>
      <a href="ateliers.php">Ateliers</a>
      <a href="shop.php">Boutique</a>
      

    <?php elseif (isset($_SESSION['user_id'])): ?>
      <!-- MENU USER -->
      <a href="index.php">Accueil</a>
            <a href="ateliers.php">Ateliers</a>

      <a href="shop.php">Boutique</a>
      <a href="panier.php">Panier</a>
      <a href="blog.php">Blog</a>
      <a href="quiz.php">Quiz</a>
      <a href="faq.php">FAQ</a>
      <a href="astuce.php">Astuce du jour</a>
       <a href="about.php">À propos</a>

    <?php else: ?>
      <!-- MENU VISITEUR NON CONNECTÉ -->
      <a href="auth.php">Se connecter</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="user-menu" id="userMenu">
        <span class="user-trigger" onclick="toggleDropdown()">
          <?= $_SESSION['user_role'] === 'admin' ? '👑' : '👤' ?>
          <?= htmlspecialchars($_SESSION['user_nom']); ?> ▾
        </span>
        <div class="user-card" id="userCard">
          <div class="user-card-header">
            <div class="user-avatar">
              <?= $_SESSION['user_role'] === 'admin' ? '👑' : '👤' ?>
            </div>
            <div>
              <strong><?= htmlspecialchars($_SESSION['user_nom']); ?></strong>
              <small><?= $_SESSION['user_role'] === 'admin' ? 'Administrateur' : 'Membre GreenVerse' ?></small>
            </div>
          </div>
          <hr>
          <a href="profil.php" class="user-card-link">⚙️ Mon profil</a>
          <a href="../../backend/Auth/logout.php" class="user-card-link logout">🚪 Se déconnecter</a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</nav>

<script>
function toggleDropdown() {
  document.getElementById('userCard').classList.toggle('show');
}
document.addEventListener('click', function(e) {
  const menu = document.getElementById('userMenu');
  if (menu && !menu.contains(e.target)) {
    document.getElementById('userCard').classList.remove('show');
  }
});
</script>