<?php
session_start();
require '../config/db.php';

$prenom    = trim($_POST['prenom']    ?? '');
$nom       = trim($_POST['nom']       ?? '');
$email     = trim($_POST['email']     ?? '');
$password  = $_POST['password']       ?? '';
$telephone = trim($_POST['telephone'] ?? '');
$role      = 'utilisateur';

if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?tab=register&error=champs_vides");
    exit;
}

// Connexion PDO
$database = new Database();
$conn     = $database->getConnection();

// Vérifier si email existe déjà
$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->fetch()) {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?tab=register&error=email_existe");
    exit;
}

// Hasher le mot de passe
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insérer l'utilisateur
$stmt = $conn->prepare("INSERT INTO users (prenom, nom, email, telephone, password, role) VALUES (:prenom, :nom, :email, :telephone, :password, :role)");
$stmt->bindParam(':prenom',    $prenom);
$stmt->bindParam(':nom',       $nom);
$stmt->bindParam(':email',     $email);
$stmt->bindParam(':telephone', $telephone);
$stmt->bindParam(':password',  $password_hash);
$stmt->bindParam(':role',      $role);

if ($stmt->execute()) {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?success=compte_cree");
    exit;
} else {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?tab=register&error=erreur_inscription");
    exit;
}
?>