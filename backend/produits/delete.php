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

$id = $data->id;

$query = "DELETE FROM produits WHERE id = :id";
$stmt  = $db->prepare($query);
$stmt->bindParam(':id', $id);

try {
    if ($stmt->execute()) {
        echo json_encode(["message" => "Produit supprimé avec succès"]);
    } else {
        echo json_encode(["message" => "Erreur lors de la suppression"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}
?>