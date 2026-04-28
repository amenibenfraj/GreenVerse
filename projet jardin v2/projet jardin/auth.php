<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
<title>Connexion / Inscription - GreenVerse</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --green-deep:  #1b4332;
    --green-mid:   #2d6a4f;
    --green-light: #52b788;
    --green-pale:  #d8f3dc;
    --cream:       #fdf8f2;
    --text:        #1a1a1a;
    --muted:       #6b7280;
    --white:       #ffffff;
    --shadow:      0 20px 60px rgba(27,67,50,0.15);
    --radius:      20px;
  }
  html { height: 100%; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    min-height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 60px 20px 50px;
    position: relative;
    overflow-x: hidden;
  }
  body::before, body::after {
    content: '';
    position: fixed;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    pointer-events: none;
  }
  body::before {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(82,183,136,0.18), transparent 70%);
    top: -150px; left: -150px;
  }
  body::after {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(27,67,50,0.12), transparent 70%);
    bottom: -100px; right: -100px;
  }
  .auth-wrapper {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 520px;
  }
  .auth-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
  }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .tabs { display: flex; background: var(--green-pale); }
  .tab-btn {
    flex: 1; padding: 18px; border: none;
    background: transparent;
    font-family: 'DM Sans', sans-serif;
    font-size: 1em; font-weight: 600;
    color: var(--green-mid); cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.02em; position: relative;
  }
  .tab-btn.active {
    background: var(--white);
    color: var(--green-deep);
    border-radius: 20px 20px 0 0;
  }
  .tab-btn.active::after {
    content: ''; position: absolute;
    bottom: -2px; left: 0; right: 0;
    height: 2px; background: var(--green-light);
  }
  .form-panel {
    display: none;
    padding: 36px 40px 40px;
    animation: fadeIn 0.3s ease both;
  }
  .form-panel.active { display: block; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .panel-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.85em; color: var(--green-deep); margin-bottom: 6px;
  }
  .panel-subtitle { color: var(--muted); font-size: 0.9em; margin-bottom: 28px; line-height: 1.5; }
  .field-group { display: flex; flex-direction: column; gap: 15px; margin-bottom: 22px; }
  .field-row { display: flex; gap: 14px; }
  .field { display: flex; flex-direction: column; flex: 1; gap: 6px; }
  label { font-size: 0.78em; font-weight: 600; color: var(--green-deep); letter-spacing: 0.05em; text-transform: uppercase; }
  input[type="text"], input[type="email"], input[type="tel"], input[type="password"], select {
    padding: 12px 15px; border: 1.5px solid #e5e7eb;
    border-radius: 12px; font-family: 'DM Sans', sans-serif;
    font-size: 0.95em; color: var(--text); background: #fafafa;
    transition: all 0.25s ease; outline: none; width: 100%;
  }
  input:focus, select:focus {
    border-color: var(--green-light); background: var(--white);
    box-shadow: 0 0 0 4px rgba(82,183,136,0.12); transform: translateY(-1px);
  }
  input::placeholder { color: #b0b8c1; }
  .pass-wrap { position: relative; }
  .pass-wrap input { padding-right: 46px; }
  .toggle-pass {
    position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--muted); font-size: 1.1em; padding: 0; width: auto; transition: color 0.2s;
  }
  .toggle-pass:hover { color: var(--green-mid); }
  .strength-bar { height: 4px; border-radius: 4px; background: #e5e7eb; margin-top: 5px; overflow: hidden; }
  .strength-fill { height: 100%; border-radius: 4px; width: 0%; transition: width 0.3s ease, background 0.3s ease; }
  .check-row { display: flex; align-items: flex-start; gap: 10px; }
  .check-row input[type="checkbox"] { width: 17px; height: 17px; margin-top: 2px; accent-color: var(--green-mid); cursor: pointer; flex-shrink: 0; }
  .check-row span { font-size: 0.86em; color: var(--muted); line-height: 1.5; }
  .check-row a { color: var(--green-mid); text-decoration: underline; }
  .btn-submit {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, var(--green-mid), var(--green-deep));
    color: var(--white); border: none; border-radius: 14px;
    font-family: 'DM Sans', sans-serif; font-size: 1em; font-weight: 600;
    cursor: pointer; letter-spacing: 0.03em; transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(27,67,50,0.25); position: relative; overflow: hidden;
  }
  .btn-submit::before {
    content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s ease;
  }
  .btn-submit:hover::before { left: 100%; }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(27,67,50,0.35); }
  .alert {
    padding: 11px 15px; border-radius: 10px; font-size: 0.87em;
    margin-bottom: 18px; display: none; align-items: center; gap: 8px;
  }
  .alert.success { background: var(--green-pale); color: var(--green-deep); border: 1px solid var(--green-light); }
  .alert.error   { background: #fff0f0; color: #b91c1c; border: 1px solid #fca5a5; }
  .alert.show    { display: flex; animation: fadeIn 0.3s ease; }
  .forgot { text-align: right; margin-top: -8px; }
  .forgot a { font-size: 0.82em; color: var(--muted); text-decoration: none; transition: color 0.2s; }
  .forgot a:hover { color: var(--green-mid); }
  .switch-link { text-align: center; font-size: 0.88em; color: var(--muted); margin-top: 16px; }
  .switch-link a { color: var(--green-mid); font-weight: 600; text-decoration: none; cursor: pointer; }
  .switch-link a:hover { text-decoration: underline; }
  .leaf-footer { margin-top: 22px; text-align: center; font-size: 0.8em; color: var(--muted); z-index: 1; position: relative; }
  @media (max-width: 560px) {
    body { padding: 40px 14px 30px; }
    .form-panel { padding: 24px 18px 28px; }
    .field-row { flex-direction: column; }
  }
</style>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-card">
    <div class="tabs">
      <button class="tab-btn active" onclick="showTab('login')">🔑 Connexion</button>
      <button class="tab-btn" onclick="showTab('register')">🌿 Inscription</button>
    </div>

    <!-- LOGIN -->
    <div id="panel-login" class="form-panel active">
      <h2 class="panel-title">Bon retour 👋</h2>
      <p class="panel-subtitle">Connectez-vous pour accéder à votre espace GreenVerse.</p>
      <div id="alert-login" class="alert"></div>
      <form action="../../backend/Auth/login.php" method="POST" onsubmit="return validateLogin()">
        <div class="field-group">
          <div class="field">
            <label>Adresse e-mail</label>
            <input type="email" name="email" id="login-email" placeholder="votre@email.com" required autocomplete="email">
          </div>
          <div class="field">
            <label>Mot de passe</label>
            <div class="pass-wrap">
              <input type="password" name="password" id="login-pass" placeholder="••••••••" required autocomplete="current-password">
              <button type="button" class="toggle-pass" onclick="togglePass('login-pass', this)">👁</button>
            </div>
          </div>
          <div class="forgot"><a href="#">Mot de passe oublié ?</a></div>
        </div>
        <button type="submit" class="btn-submit">Se connecter →</button>
      </form>
      <p class="switch-link">Pas encore de compte ? <a onclick="showTab('register')">Créer un compte</a></p>
    </div>

    <!-- REGISTER -->
    <div id="panel-register" class="form-panel">
      <h2 class="panel-title">Rejoignez-nous 🌱</h2>
      <p class="panel-subtitle">Créez votre compte et participez à la communauté verte.</p>
      <div id="alert-register" class="alert"></div>
      <form action="../../backend/Auth/inscrire.php" method="POST" onsubmit="return validateRegister()">
        <div class="field-group">
          <div class="field-row">
            <div class="field">
              <label>Prénom</label>
              <input type="text" name="prenom" id="reg-prenom" placeholder="Yasmine" required>
            </div>
            <div class="field">
              <label>Nom</label>
              <input type="text" name="nom" id="reg-nom" placeholder="Ben Ali" required>
            </div>
          </div>
          <div class="field">
            <label>Adresse e-mail</label>
            <input type="email" name="email" id="reg-email" placeholder="votre@email.com" required autocomplete="email">
          </div>
          <div class="field">
            <label>Mot de passe</label>
            <div class="pass-wrap">
              <input type="password" name="password" id="reg-pass" placeholder="Min. 8 caractères" required oninput="checkStrength(this.value)" autocomplete="new-password">
              <button type="button" class="toggle-pass" onclick="togglePass('reg-pass', this)">👁</button>
            </div>
            <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
          </div>
          <div class="field">
            <label>Numéro de téléphone</label>
            <input type="tel" name="telephone" id="reg-tel" placeholder="+216 XX XXX XXX" required autocomplete="tel">
          </div>
          <input type="hidden" name="role" value="utilisateur">
          <div class="check-row">
            <input type="checkbox" name="cgu" id="reg-cgu" required>
            <span>J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a></span>
          </div>
        </div>
        <button type="submit" class="btn-submit">Créer mon compte 🌱</button>
      </form>
      <p class="switch-link">Déjà un compte ? <a onclick="showTab('login')">Se connecter</a></p>
    </div>
  </div>
  <p class="leaf-footer">🌿 GreenVerse — Votre jardin numérique &copy; 2025</p>
</div>

<script>
  // Bloquer retour par flèche
  window.history.pushState(null, '', window.location.href);
  window.addEventListener('popstate', function() {
    window.history.pushState(null, '', window.location.href);
  });

  // Afficher erreurs/succès venant du serveur
  const urlParams = new URLSearchParams(window.location.search);
  const error     = urlParams.get('error');
  const success   = urlParams.get('success');

  if (error === 'identifiants_incorrects') {
    showAlert('alert-login', 'error', '❌ Email ou mot de passe incorrect.');
    showTab('login');
  } else if (error === 'champs_vides') {
    showAlert('alert-login', 'error', '⚠️ Veuillez remplir tous les champs.');
    showTab('login');
  } else if (error === 'email_existe') {
    showAlert('alert-register', 'error', '📧 Cet email est déjà utilisé.');
    showTab('register');
  } else if (error === 'erreur_inscription') {
    showAlert('alert-register', 'error', '❌ Erreur lors de l\'inscription.');
    showTab('register');
  } else if (success === 'compte_cree') {
    showAlert('alert-login', 'success', '✅ Compte créé ! Vous pouvez vous connecter.');
    showTab('login');
  }

  if (new URLSearchParams(location.search).get('tab') === 'register') showTab('register');

  function showTab(tab) {
    document.querySelectorAll('.tab-btn').forEach((b, i) => {
      b.classList.toggle('active', (i === 0 && tab === 'login') || (i === 1 && tab === 'register'));
    });
    document.getElementById('panel-login').classList.toggle('active', tab === 'login');
    document.getElementById('panel-register').classList.toggle('active', tab === 'register');
  }

  function togglePass(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  }

  function checkStrength(val) {
    const fill = document.getElementById('strength-fill');
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    const widths  = ['25%','50%','75%','100%'];
    fill.style.width      = score > 0 ? widths[score-1] : '0%';
    fill.style.background = score > 0 ? colors[score-1] : 'transparent';
  }

  function validateLogin() {
    const email = document.getElementById('login-email').value.trim();
    const pass  = document.getElementById('login-pass').value;
    if (!email || !pass) {
      showAlert('alert-login', 'error', '⚠️ Veuillez remplir tous les champs.');
      return false;
    }
    return true;
  }

  function validateRegister() {
    const prenom = document.getElementById('reg-prenom').value.trim();
    const nom    = document.getElementById('reg-nom').value.trim();
    const email  = document.getElementById('reg-email').value.trim();
    const pass   = document.getElementById('reg-pass').value;
    const tel    = document.getElementById('reg-tel').value.trim();
    const cgu    = document.getElementById('reg-cgu').checked;
    if (!prenom || !nom || !email || !pass || !tel) {
      showAlert('alert-register', 'error', '⚠️ Veuillez remplir tous les champs obligatoires.');
      return false;
    }
    if (pass.length < 8) {
      showAlert('alert-register', 'error', '🔒 Le mot de passe doit comporter au moins 8 caractères.');
      return false;
    }
    if (!/^[\+0-9\s\-]{7,15}$/.test(tel)) {
      showAlert('alert-register', 'error', '📞 Numéro de téléphone invalide.');
      return false;
    }
    if (!cgu) {
      showAlert('alert-register', 'error', "📋 Veuillez accepter les conditions d'utilisation.");
      return false;
    }
    return true;
  }

  function showAlert(id, type, msg) {
    const el = document.getElementById(id);
    el.className = `alert ${type} show`;
    el.textContent = msg;
    setTimeout(() => el.classList.remove('show'), 4000);
  }
</script>
</body>
</html>