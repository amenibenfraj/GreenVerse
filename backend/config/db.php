<?php
class Database {
    private $host     = "localhost";
    private $db_name  = "greenverse";
    private $username = "root";
    private $password = "";
    public  $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(["message" => "Erreur connexion : " . $e->getMessage()]);
        }
        return $this->conn;
    }
}
//******************************************************************************** 


//  VERSION MySQLi 

$host     = "localhost";
$user     = "root";
$password = "";
$port     = 3306;

// connexion serveur
$connect = mysqli_connect($host, $user, $password);
if (!$connect) {
    die("Erreur connexion serveur: " . mysqli_connect_error());
}

// création DB si elle n'existe pas
$dbname = "greenverse";
mysqli_query($connect, "CREATE DATABASE IF NOT EXISTS $dbname");

// connexion DB
$connect_db = mysqli_connect($host, $user, $password, $dbname);
if (!$connect_db) {
    die("Erreur connexion DB: " . mysqli_connect_error());
}



//  USERS
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    prenom     VARCHAR(100) NOT NULL,
    nom        VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    telephone  VARCHAR(20),
    password   VARCHAR(255) NOT NULL,
    role       ENUM('utilisateur', 'admin') DEFAULT 'utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($connect_db, $sql_users);


?>