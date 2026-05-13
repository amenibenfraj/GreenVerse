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

// Créer la table produits si elle n'existe pas
$db->exec("CREATE TABLE IF NOT EXISTS produits (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    prix        DECIMAL(10,2) DEFAULT 0,
    image       VARCHAR(255) DEFAULT '',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

ob_end_clean();

$data = json_decode(file_get_contents("php://input"));

// Validation
if (empty($data->nom)) {
    echo json_encode(["message" => "Le nom du produit est obligatoire"]);
    exit;
}

$nom         = $data->nom;
$description = $data->description ?? '';
$prix        = $data->prix        ?? 0;
$image       = $data->image       ?? '';

$query = "INSERT INTO produits (nom, description, prix, image)
          VALUES (:nom, :description, :prix, :image)";

$stmt = $db->prepare($query);

$stmt->bindParam(':nom',         $nom);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':prix',        $prix);
$stmt->bindParam(':image',       $image);

try {
    if ($stmt->execute()) {
        echo json_encode([
            "message" => "Produit ajouté avec succès",
            "id"      => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(["message" => "Erreur lors de l'ajout"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
}
?>