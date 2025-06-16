<?php
// .well-known/jwks.php

require_once '../buwanaconn_env.php';

header('Content-Type: application/json');

$client_id = $_GET['client_id'] ?? null;
if (!$client_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing client_id parameter']);
    exit();
}

$sql = "SELECT jwt_public_key FROM apps_tb WHERE client_id = ?";
$stmt = $buwana_conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $buwana_conn->error]);
    exit();
}

$stmt->bind_param('s', $client_id);
$stmt->execute();
$stmt->bind_result($public_key_pem);
$stmt->fetch();
$stmt->close();

if (!$public_key_pem) {
    http_response_code(404);
    echo json_encode(['error' => 'Public key not found for this client_id']);
    exit();
}

$public_key_pem = trim($public_key_pem);
$public_key_details = openssl_pkey_get_details(openssl_pkey_get_public($public_key_pem));

if (!$public_key_details || $public_key_details['type'] !== OPENSSL_KEYTYPE_RSA) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid public key']);
    exit();
}

$modulus = rtrim(strtr(base64_encode($public_key_details['rsa']['n']), '+/', '-_'), '=');
$exponent = rtrim(strtr(base64_encode($public_key_details['rsa']['e']), '+/', '-_'), '=');

$jwk = [
    'keys' => [[
        'kty' => 'RSA',
        'use' => 'sig',
        'alg' => 'RS256',
        'kid' => $client_id,
        'n' => $modulus,
        'e' => $exponent
    ]]
];

echo json_encode($jwk, JSON_PRETTY_PRINT);
