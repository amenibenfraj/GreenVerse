<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Gérer preflight CORS
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

// Validation
if (empty($data->nom) || empty($data->categorie)) {
    echo json_encode(["message" => "Nom et catégorie sont obligatoires"]);
    exit;
}

// ✅ Variables simples pour bindParam
$nom         = $data->nom;
$categorie   = $data->categorie;
$description = $data->description ?? '';
$emoji       = $data->emoji       ?? '';
$image       = $data->image       ?? '';

$query = "INSERT INTO plantes (nom, categorie, description, emoji, image)
          VALUES (:nom, :categorie, :description, :emoji, :image)";

$stmt = $db->prepare($query);

$stmt->bindParam(':nom',         $nom);
$stmt->bindParam(':categorie',   $categorie);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':emoji',       $emoji);
$stmt->bindParam(':image',       $image);

try {
    if ($stmt->execute()) {
        echo json_encode([
            "message" => "Plante ajoutée avec succès",
            "id"      => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(["message" => "Erreur lors de l'ajout"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}