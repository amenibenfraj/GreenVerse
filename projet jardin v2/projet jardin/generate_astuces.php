<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apiKey = getenv('GROQ_API_KEY');   ////////////////baaad tetnaha 
$prompt = <<<PROMPT
Tu es un expert en jardinage et en écologie. Génère exactement 4 astuces de jardinage originales, pratiques et variées.
Réponds UNIQUEMENT en JSON valide, sans markdown, sans backticks, sans texte avant ou après.
Format exact :
[
  {"titre": "...", "astuce": "...", "emoji": "🌿"},
  {"titre": "...", "astuce": "...", "emoji": "💧"},
  {"titre": "...", "astuce": "...", "emoji": "🌸"},
  {"titre": "...", "astuce": "...", "emoji": "🪴"}
]
Chaque astuce : titre court (2-4 mots), description utile (1-2 phrases max), emoji jardinage pertinent.
Varie les thèmes : arrosage, taille, compost, sols, semis, nuisibles, saisons, outils.
PROMPT;

$payload = json_encode([
    'model'       => 'llama-3.3-70b-versatile', // rapide et gratuit sur Groq
    'max_tokens'  => 600,
    'temperature' => 0.8,
    'messages'    => [
        [
            'role'    => 'system',
            'content' => 'Tu réponds UNIQUEMENT en JSON valide, aucun texte autour.'
        ],
        [
            'role'    => 'user',
            'content' => $prompt
        ]
    ]
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_SSL_VERIFYPEER => false,  // ← ajouter cette ligne

    CURLOPT_TIMEOUT        => 20,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ]
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err  = curl_error($ch);
curl_close($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur cURL', 'detail' => $curl_err]);
    exit;
}

if ($http_code !== 200) {
    $data = json_decode($response, true);
    http_response_code(500);
    echo json_encode([
        'error'  => 'Erreur API Groq',
        'code'   => $http_code,
        'detail' => $data['error']['message'] ?? $response
    ]);
    exit;
}

$data = json_decode($response, true);
$text = $data['choices'][0]['message']['content'] ?? '';

// Nettoyer les éventuels backticks markdown
$text = preg_replace('/```json|```/i', '', $text);
$text = trim($text);

$astuces = json_decode($text, true);

if (!is_array($astuces) || count($astuces) === 0) {
    http_response_code(500);
    echo json_encode(['error' => 'JSON invalide reçu', 'raw' => $text]);
    exit;
}

echo json_encode($astuces);
?>