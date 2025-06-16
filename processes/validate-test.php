<?php
header('Content-Type: application/json');

require_once '../buwanaconn_env.php';

$client_id = 'buwana_mgr_001'; // Hardcoded client_id for testing

$sql = "SELECT jwt_public_key FROM apps_tb WHERE client_id = ?";
$stmt = $buwana_conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit();
}

$stmt->bind_param("s", $client_id);
$stmt->execute();
$stmt->bind_result($publicKey);
$stmt->fetch();
$stmt->close();

if (!$publicKey) {
    http_response_code(404);
    echo json_encode(['error' => 'Public key not found for client_id']);
    exit();
}

$details = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));
$keyData = $details['rsa'];

$modulus = rtrim(strtr(base64_encode($keyData['n']), '+/', '-_'), '=');
$exponent = rtrim(strtr(base64_encode($keyData['e']), '+/', '-_'), '=');

echo json_encode([
    'keys' => [[
        'kty' => 'RSA',
        'use' => 'sig',
        'alg' => 'RS256',
        'kid' => $client_id,
        'n' => $modulus,
        'e' => $exponent
    ]]
]);
