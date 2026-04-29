<?php
session_start();
require '../config/db.php';

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?error=champs_vides");
    exit;
}

// Connexion PDO
$database = new Database();
$conn     = $database->getConnection();

// Requête PDO avec prepare
$stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_nom']  = $user['prenom'] . ' ' . $user['nom'];
    $_SESSION['user_role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: ../../projet jardin v2/projet jardin/admindash.php");
    } else {
        header("Location: ../../projet jardin v2/projet jardin/index.php");
    }
    exit;

} else {
    header("Location: ../../projet jardin v2/projet jardin/auth.php?error=identifiants_incorrects");
    exit;
}
?>