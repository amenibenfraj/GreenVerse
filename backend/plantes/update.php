<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    http_response_code(200);
    exit();
}

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

ob_end_clean();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    echo json_encode(["message" => "ID manquant"]);
    exit;
}

// ✅ Stocker dans des variables simples avant bindParam
$id          = intval($data->id);
$nom         = $data->nom         ?? '';
$categorie   = $data->categorie   ?? '';
$description = $data->description ?? '';
$emoji       = $data->emoji       ?? '';
$image       = $data->image       ?? '';

$query = "UPDATE plantes
          SET nom         = :nom,
              categorie   = :categorie,
              description = :description,
              emoji       = :emoji,
              image       = :image
          WHERE id = :id";

$stmt = $db->prepare($query);

$stmt->bindParam(':id',          $id);
$stmt->bindParam(':nom',         $nom);
$stmt->bindParam(':categorie',   $categorie);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':emoji',       $emoji);
$stmt->bindParam(':image',       $image);

try {
    if ($stmt->execute()) {
        echo json_encode(["message" => "Plante mise à jour avec succès"]);
    } else {
        echo json_encode(["message" => "Erreur lors de la mise à jour"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}