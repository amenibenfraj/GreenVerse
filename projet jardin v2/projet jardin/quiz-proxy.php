<?php
// ============================================================
//  quiz-proxy.php — Proxy securise pour l'API Groq (gratuit)
// ============================================================

//define('GROQ_API_KEY', '');

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Methode non autorisee']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['theme'], $input['difficulte'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parametres manquants']);
    exit;
}

$theme = htmlspecialchars($input['theme']);
$diff  = htmlspecialchars($input['difficulte']);

$prompt = "Genere un quiz de jardinage. Theme : $theme. Difficulte : $diff.
Retourne UNIQUEMENT un tableau JSON valide de 5 objets, sans texte avant ou apres, sans balises markdown.
Format strict de chaque objet :
{\"question\": \"...\", \"options\": [\"A\", \"B\", \"C\"], \"correct\": 0, \"explanation\": \"...\"}
- correct est l'index (0, 1 ou 2) de la bonne reponse.
- Varie les positions de la bonne reponse entre les questions.
- Questions instructives et adaptees au niveau $diff sur le theme $theme.";

$payload = json_encode([
    'model'       => 'llama-3.3-70b-versatile',
    'messages'    => [
        [
            'role'    => 'system',
            'content' => 'Tu es un expert en jardinage. Tu reponds UNIQUEMENT avec du JSON valide, sans texte supplementaire, sans balises markdown.'
        ],
        [
            'role'    => 'user',
            'content' => $prompt
        ]
    ],
    'max_tokens'  => 1200,
    'temperature' => 0.7,
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY,
    ],
    CURLOPT_TIMEOUT        => 30,
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err  = curl_error($ch);
curl_close($ch);

if ($curl_err) {
    http_response_code(502);
    echo json_encode(['error' => $curl_err]);
    exit;
}

http_response_code($http_code);
echo $response;