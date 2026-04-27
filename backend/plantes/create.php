<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

// Validation des champs obligatoires
if (empty($data->nom) || empty($data->categorie)) {
    echo json_encode(["message" => "Nom et catégorie sont obligatoires"]);
    exit;
}

$query = "INSERT INTO plantes (nom, categorie, description, emoji, image)
          VALUES (:nom, :categorie, :description, :emoji, :image)";

$stmt = $db->prepare($query);

$stmt->bindParam(':nom',         $data->nom);
$stmt->bindParam(':categorie',   $data->categorie);
$stmt->bindParam(':description', $data->description ?? '');
$stmt->bindParam(':emoji',       $data->emoji ?? '');
$stmt->bindParam(':image',       $data->image ?? '');

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Plante créée avec succès",
        "id"      => $db->lastInsertId()
    ]);
} else {
    echo json_encode(["message" => "Erreur lors de la création"]);
}