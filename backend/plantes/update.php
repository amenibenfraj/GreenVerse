<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    echo json_encode(["message" => "ID manquant"]);
    exit;
}

$query = "UPDATE plantes
          SET nom         = :nom,
              categorie   = :categorie,
              description = :description,
              emoji       = :emoji,
              image       = :image
          WHERE id = :id";

$stmt = $db->prepare($query);

$stmt->bindParam(':id',          $data->id);
$stmt->bindParam(':nom',         $data->nom);
$stmt->bindParam(':categorie',   $data->categorie);
$stmt->bindParam(':description', $data->description ?? '');
$stmt->bindParam(':emoji',       $data->emoji ?? '');
$stmt->bindParam(':image',       $data->image ?? '');

if ($stmt->execute()) {
    echo json_encode(["message" => "Plante mise à jour avec succès"]);
} else {
    echo json_encode(["message" => "Erreur lors de la mise à jour"]);
}