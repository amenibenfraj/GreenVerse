<?php
session_start();
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// ─── Vérification sécurité ───────────────────────────────────────────────────
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès refusé']);
    exit;
}

// ─── Connexion BDD ───────────────────────────────────────────────────────────
$host   = 'localhost';
$dbname = 'greenverse';
$user   = 'root';
$pass   = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connexion BDD échouée: ' . $e->getMessage()]);
    exit;
}

// ─── Lecture de l'action ─────────────────────────────────────────────────────
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$rawBody = file_get_contents('php://input');

if ($action === '' && !empty($rawBody)) {
    $bodyData = json_decode($rawBody, true);
    if (isset($bodyData['action'])) $action = $bodyData['action'];
}

// ─── ROUTER ──────────────────────────────────────────────────────────────────
switch ($action) {

    // ── STATS GLOBALES ────────────────────────────────────────────────────────
    case 'stats':
        $stats = [];
        $stats['total_commandes']   = (int)$pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
        $stats['revenus_total']     = (float)$pdo->query("SELECT COALESCE(SUM(total_ttc),0) FROM commandes WHERE statut != 'annulee'")->fetchColumn();
        $stats['total_users']       = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['total_plantes']     = (int)$pdo->query("SELECT COUNT(*) FROM plantes")->fetchColumn();
        $stats['total_produits']    = (int)$pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
        $stats['commandes_attente'] = (int)$pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='en_attente' OR statut='pending'")->fetchColumn();
        $stats['stock_faible']      = (int)$pdo->query("SELECT COUNT(*) FROM produits WHERE stock > 0 AND stock < 10")->fetchColumn();
        $stats['stock_rupture']     = (int)$pdo->query("SELECT COUNT(*) FROM produits WHERE stock = 0")->fetchColumn();
        $stats['nouveaux_users']    = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
        $stats['paniers_actifs']    = (int)$pdo->query("SELECT COUNT(DISTINCT session_id) FROM panier")->fetchColumn();

        $stmt = $pdo->query("
            SELECT DATE_FORMAT(created_at,'%b %Y') as mois,
                   DATE_FORMAT(created_at,'%Y-%m') as mois_sort,
                   COUNT(*) as nb,
                   COALESCE(SUM(total_ttc),0) as revenus
            FROM commandes
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mois_sort, mois
            ORDER BY mois_sort ASC
        ");
        $stats['commandes_par_mois'] = $stmt->fetchAll();

        $stmt = $pdo->query("SELECT statut, COUNT(*) as nb FROM commandes GROUP BY statut");
        $stats['statuts'] = $stmt->fetchAll();

        $stmt = $pdo->query("
            SELECT ci.nom, SUM(ci.quantite) as total_vendu
            FROM commande_items ci
            WHERE ci.item_type='plante'
            GROUP BY ci.nom
            ORDER BY total_vendu DESC
            LIMIT 5
        ");
        $stats['top_plantes'] = $stmt->fetchAll();

        $stmt = $pdo->query("
            SELECT p.categorie, COUNT(*) as nb_plantes
            FROM plantes p
            GROUP BY p.categorie
            ORDER BY nb_plantes DESC
        ");
        $stats['stock_categories'] = $stmt->fetchAll();

        echo json_encode($stats);
        break;

    // ── COMMANDES LIST ────────────────────────────────────────────────────────
    case 'commandes':
        $search = '%' . ($_GET['search'] ?? '') . '%';
        $statut = $_GET['statut'] ?? '';

        $sql = "SELECT c.*,
                    (SELECT COUNT(*) FROM commande_items ci WHERE ci.commande_id = c.id) as nb_items
                FROM commandes c
                WHERE (c.nom LIKE :search OR c.email LIKE :search OR CAST(c.id AS CHAR) LIKE :search)";
        $params = [':search' => $search];

        if ($statut) {
            $sql .= " AND c.statut = :statut";
            $params[':statut'] = $statut;
        }
        $sql .= " ORDER BY c.created_at DESC LIMIT 100";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $commandes = $stmt->fetchAll();

        foreach ($commandes as &$cmd) {
            $s = $pdo->prepare("SELECT * FROM commande_items WHERE commande_id = ?");
            $s->execute([$cmd['id']]);
            $cmd['items'] = $s->fetchAll();
        }
        echo json_encode($commandes);
        break;

    // ── COMMANDE UPDATE ───────────────────────────────────────────────────────
    case 'update_commande':
        $data = json_decode($rawBody, true) ?? [];
        $id   = (int)($data['id'] ?? 0);
        if (!$id) { echo json_encode(['error' => 'ID requis']); break; }

        $stmt = $pdo->prepare("
            UPDATE commandes
            SET statut=:statut, nom=:nom, prenom=:prenom, email=:email,
                telephone=:telephone, adresse=:adresse, ville=:ville,
                code_postal=:code_postal, mode_paiement=:mode_paiement
            WHERE id=:id
        ");
        $stmt->execute([
            ':statut'        => $data['statut']        ?? 'en_attente',
            ':nom'           => $data['nom']            ?? '',
            ':prenom'        => $data['prenom']         ?? '',
            ':email'         => $data['email']          ?? '',
            ':telephone'     => $data['telephone']      ?? '',
            ':adresse'       => $data['adresse']        ?? '',
            ':ville'         => $data['ville']          ?? '',
            ':code_postal'   => $data['code_postal']    ?? '',
            ':mode_paiement' => $data['mode_paiement']  ?? '',
            ':id'            => $id,
        ]);
        echo json_encode(['success' => true]);
        break;

    // ── COMMANDE DELETE ───────────────────────────────────────────────────────
    case 'delete_commande':
        $data = json_decode($rawBody, true) ?? [];
        $id   = (int)($data['id'] ?? 0);
        if (!$id) { echo json_encode(['error' => 'ID requis']); break; }

        $pdo->prepare("DELETE FROM commande_items WHERE commande_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM commandes WHERE id=?")->execute([$id]);
        echo json_encode(['success' => true]);
        break;

    // ── COMMANDE ADD ──────────────────────────────────────────────────────────
    case 'add_commande':
        $data      = json_decode($rawBody, true) ?? [];
        $livraison = 5.90;
        $total_ht  = (float)($data['total_ttc'] ?? 0) / 1.2;

        $stmt = $pdo->prepare("
            INSERT INTO commandes (session_id, nom, prenom, email, telephone, adresse, ville,
                                   code_postal, pays, mode_paiement, total_ht, livraison, total_ttc, statut, created_at)
            VALUES (:session_id,:nom,:prenom,:email,:telephone,:adresse,:ville,
                    :code_postal,:pays,:mode_paiement,:total_ht,:livraison,:total_ttc,:statut, NOW())
        ");
        $stmt->execute([
            ':session_id'    => 'admin-' . uniqid(),
            ':nom'           => $data['nom']            ?? '',
            ':prenom'        => $data['prenom']         ?? '',
            ':email'         => $data['email']          ?? '',
            ':telephone'     => $data['telephone']      ?? '',
            ':adresse'       => $data['adresse']        ?? '',
            ':ville'         => $data['ville']          ?? '',
            ':code_postal'   => $data['code_postal']    ?? '',
            ':pays'          => $data['pays']           ?? 'France',
            ':mode_paiement' => $data['mode_paiement']  ?? 'carte',
            ':total_ht'      => round($total_ht, 2),
            ':livraison'     => $livraison,
            ':total_ttc'     => (float)($data['total_ttc'] ?? 0),
            ':statut'        => $data['statut']         ?? 'en_attente',
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        break;

    // ── PANIERS ───────────────────────────────────────────────────────────────
    case 'paniers':
        $stmt = $pdo->query("
            SELECT p.session_id,
                   COUNT(*) as nb_articles,
                   SUM(p.prix * p.quantite) as total,
                   MAX(p.updated_at) as derniere_activite,
                   GROUP_CONCAT(p.nom ORDER BY p.created_at SEPARATOR ', ') as articles
            FROM panier p
            GROUP BY p.session_id
            ORDER BY derniere_activite DESC
        ");
        echo json_encode($stmt->fetchAll());
        break;

    // ── USERS ─────────────────────────────────────────────────────────────────
    case 'users':
        $search = '%' . ($_GET['search'] ?? '') . '%';
        $stmt = $pdo->prepare("
            SELECT u.*,
                (SELECT COUNT(*) FROM commandes c WHERE c.email = u.email) as nb_commandes,
                (SELECT COALESCE(SUM(c2.total_ttc),0) FROM commandes c2 WHERE c2.email = u.email AND c2.statut != 'annulee') as total_depense
            FROM users u
            WHERE u.nom LIKE :s OR u.prenom LIKE :s OR u.email LIKE :s
            ORDER BY u.created_at DESC
            LIMIT 200
        ");
        $stmt->execute([':s' => $search]);
        $users = $stmt->fetchAll();
        foreach ($users as &$u) unset($u['password']);
        echo json_encode($users);
        break;

    // ── PLANTES ───────────────────────────────────────────────────────────────
    case 'plantes':
        $stmt = $pdo->query("SELECT * FROM plantes ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;

    // ── PRODUITS ──────────────────────────────────────────────────────────────
    case 'produits':
        $stmt = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
        echo json_encode($stmt->fetchAll());
        break;

    // ── INSCRIPTIONS ──────────────────────────────────────────────────────────
    case 'inscriptions':
        $stmt = $pdo->query("
            SELECT i.*, a.titre as atelier_titre
            FROM inscriptions i
            LEFT JOIN ateliers a ON a.id = i.atelier OR a.titre = i.atelier
            ORDER BY i.date_inscription DESC
            LIMIT 100
        ");
        echo json_encode($stmt->fetchAll());
        break;

    // ── ATELIERS ──────────────────────────────────────────────────────────────
    case 'ateliers':
        $stmt = $pdo->query("SELECT * FROM ateliers ORDER BY date_atelier DESC");
        echo json_encode($stmt->fetchAll());
        break;

    // ── ACTION INCONNUE ───────────────────────────────────────────────────────
    default:
        http_response_code(400);
        echo json_encode([
            'error'           => 'Action inconnue: ' . $action,
            'actions_valides' => ['stats','commandes','update_commande','delete_commande','add_commande','paniers','users','plantes','produits','inscriptions','ateliers']
        ]);
}