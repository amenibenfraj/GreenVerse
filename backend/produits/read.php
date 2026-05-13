<?php
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, OPTIONS");
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

// Si ?id=X → retourne un seul produit
// Sinon     → retourne tous les produits
if (!empty($_GET['id'])) {

    $id = $_GET['id'];
    $query = "SELECT * FROM produits WHERE id = :id LIMIT 1";
    $stmt  = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produit) {
            echo json_encode($produit);
        } else {
            echo json_encode(["message" => "Produit introuvable"]);
        }
    } catch (Exception $e) {
        echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
    }

} else {

    $query = "SELECT * FROM produits ORDER BY created_at DESC";
    $stmt  = $db->prepare($query);

    try {
        $stmt->execute();
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($produits);
    } catch (Exception $e) {
        echo json_encode(["message" => "Erreur : " . $e->getMessage()]);
    }
}
?>