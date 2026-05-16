<?php

$apiKey = getenv('GROQ_API_KEY');////////////////baaad tetnaha 
$payload = json_encode([
    'model'      => 'llama-3.3-70b-versatile',
    'max_tokens' => 50,
    'messages'   => [
        ['role' => 'user', 'content' => 'Dis juste: OK']
    ]
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ]
]);

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err  = curl_error($ch);
curl_close($ch);

echo "<pre>";
echo "HTTP CODE : $http_code\n";
echo "CURL ERROR: $curl_err\n";
echo "RESPONSE  :\n" . print_r(json_decode($response, true), true);
echo "</pre>";
?>