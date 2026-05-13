<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, OPTIONS");
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

// Validation
if (empty($data->id)) {
    echo json_encode(["message" => "L'id du produit est obligatoire"]);
    exit;
}
if (empty($data->nom)) {
    echo json_encode(["message" => "Le nom du produit est obligatoire"]);
    exit;
}

$id          = $data->id;
$nom         = $data->nom;
$description = $data->description ?? '';
$prix        = $data->prix        ?? 0;
$image       = $data->image       ?? '';

$query = "UPDATE produits
          SET nom = :nom, description = :description, prix = :prix, image = :image
          WHERE id = :id";

$stmt = $db->prepare($query);

$stmt->bindParam(':id',          $id);
$stmt->bindParam(':nom',         $nom);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':prix',        $prix);
$stmt->bindParam(':image',       $image);

try {
    if ($stmt->execute()) {
        echo json_encode(["message" => "Produit modifié avec succès"]);
    } else {
        echo json_encode(["message" => "Erreur lors de la modification"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}
?>