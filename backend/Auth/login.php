<?php
session_start();
require '../config/db.php';
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    die("Veuillez remplir tous les champs");
}

// Requête sécurisée (IMPORTANT)
$stmt = $connect_db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['prenom'] . ' ' . $user['nom'];
    $_SESSION['user_role'] = $user['role'];

    // Redirection selon le rôle
    if ($user['role'] === 'admin') {
        header("Location: ../../projet jardin v2/projet jardin/admindash.php");
    } else {
        header("Location: ../../projet jardin v2/projet jardin/index.php");
    }
    exit;

} else {
    echo "Email ou mot de passe incorrect";
}
?>