<?php
session_start();

// Connexion à la base de données
require '../config/db.php';

// ── Récupérer les données du formulaire ──
$prenom    = $_POST['prenom'];
$nom       = $_POST['nom'];
$email     = $_POST['email'];
$telephone = $_POST['telephone'];
$password  = $_POST['password'];
$role      = 'utilisateur'; // toujours utilisateur à l'inscription

// ── Vérifications simples ──
if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($telephone)) {
    die("Erreur : tous les champs sont obligatoires.");
}

if (strlen($password) < 8) {
    die("Erreur : le mot de passe doit contenir au moins 8 caractères.");
}

// ── Vérifier si l'email existe déjà ──
$check = mysqli_query($connect_db, "SELECT id FROM users WHERE email = '$email'");
if (mysqli_num_rows($check) > 0) {
    die("Erreur : cet email est déjà utilisé.");
}

// ── Hasher le mot de passe (sécurité) ──
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// ── Insérer l'utilisateur dans la base ──
$sql = "INSERT INTO users (prenom, nom, email, telephone, password, role)
        VALUES ('$prenom', '$nom', '$email', '$telephone', '$password_hash', '$role')";

if (mysqli_query($connect_db, $sql)) {
    // Inscription réussie → on enregistre la session
    $_SESSION['user_id']   = mysqli_insert_id($connect_db);
    $_SESSION['user_nom']  = $prenom . ' ' . $nom;
    $_SESSION['user_role'] = $role;

    // Redirection vers la page login
     header("Location: ../../projet jardin v2/projet jardin/auth.php");
    exit;
} else {
    die("Erreur lors de l'inscription : " . mysqli_error($connect_db));
}
?>