<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

// Filtre optionnel par catégorie : ?categorie=fleurs
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;

if ($categorie) {
    $query = "SELECT * FROM plantes WHERE categorie = :categorie ORDER BY nom ASC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':categorie', $categorie);
} else {
    $query = "SELECT * FROM plantes ORDER BY categorie, nom ASC";
    $stmt = $db->prepare($query);
}

$stmt->execute();
$plantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($plantes);