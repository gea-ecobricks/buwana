<?php
require_once '../vendor/autoload.php';
require_once '../buwanaconn_env.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Grab the Authorization header
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing or invalid Authorization header']);
    exit;
}

$jwt = $matches[1];

// Decode JWT header to extract kid
$header = json_decode(base64_decode(explode('.', $jwt)[0]), true);
$kid = $header['kid'] ?? null;
if (!$kid) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing kid in JWT header']);
    exit;
}

// Fetch public key from database using kid (client_id)
$stmt = $buwana_conn->prepare("SELECT jwt_public_key FROM apps_tb WHERE client_id = ?");
$stmt->bind_param("s", $kid);
$stmt->execute();
$stmt->bind_result($public_key);
$stmt->fetch();
$stmt->close();

if (empty($public_key)) {
    http_response_code(404);
    echo json_encode(['error' => 'Public key not found']);
    exit;
}

// Decode the token
try {
    $decoded = JWT::decode($jwt, new Key($public_key, 'RS256'));
    echo json_encode((array) $decoded);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Token invalid', 'message' => $e->getMessage()]);
    exit;
}
?>
