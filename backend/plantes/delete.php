<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    echo json_encode(["message" => "ID manquant"]);
    exit;
}

$id = intval($data->id);

$query = "DELETE FROM plantes WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Plante supprimée avec succès"]);
} else {
    echo json_encode(["message" => "Erreur lors de la suppression"]);
}