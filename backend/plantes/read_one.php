<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(["message" => "ID invalide"]);
    exit;
}

$query = "SELECT * FROM plantes WHERE id = :id LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();

$plante = $stmt->fetch(PDO::FETCH_ASSOC);

if ($plante) {
    echo json_encode($plante);
} else {
    echo json_encode(["message" => "Plante non trouvée"]);
}