<?php
// Tell the browser we're serving up JSON
header('Content-Type: application/json');

// Include the Buwana DB connection
require_once '../buwanaconn_env.php';

// Get the client_id from the query string (e.g. ?client_id=buwana_mgr_001)
$client_id = $_GET['client_id'] ?? null;

// If no client_id provided, return an error
if (!$client_id) {
    http_response_code(400);
    echo json_encode(['error' => 'client_id is required']);
    exit();
}

// Prepare SQL to fetch the JWT public key for this Buwana App's client_id from the Buwana apps_tb
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

// If no public key found for that app, throw a 404
if (!$publicKey) {
    http_response_code(404);
    echo json_encode(['error' => 'Public key not found for client_id']);
    exit();
}

// Use OpenSSL to parse the public key and get modulus & exponent
$details = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));
$keyData = $details['rsa'];

// Convert raw binary modulus & exponent to URL-safe Base64
$modulus = rtrim(strtr(base64_encode($keyData['n']), '+/', '-_'), '=');
$exponent = rtrim(strtr(base64_encode($keyData['e']), '+/', '-_'), '=');

// Construct and return a JWKS (JSON Web Key Set) array
echo json_encode([
    'keys' => [[
        'kty' => 'RSA',          // Key Type
        'use' => 'sig',          // Intended use: signature
        'alg' => 'RS256',        // Algorithm
        'kid' => $client_id,     // Key ID — using client_id for clarity
        'n' => $modulus,         // Modulus
        'e' => $exponent         // Exponent
    ]]
]);
