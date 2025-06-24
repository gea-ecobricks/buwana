<?php
require_once 'vendor/autoload.php';
require_once 'buwanaconn_env.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// --- 1️⃣ Get Authorization Bearer token ---
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing or invalid Authorization header']);
    exit;
}

$jwt = $matches[1];

// --- 2️⃣ Parse JWT ---
$parts = explode('.', $jwt);
if (count($parts) !== 3) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JWT format']);
    exit;
}

$payload = json_decode(base64_decode($parts[1]), true);
if (!$payload) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JWT payload']);
    exit;
}

$client_id = $payload['aud'] ?? null;
if (!$client_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing audience (client_id) in JWT']);
    exit;
}

// --- 3️⃣ Lookup public key ---
$stmt = $buwana_conn->prepare("SELECT jwt_public_key FROM apps_tb WHERE client_id = ?");
$stmt->bind_param("s", $client_id);
$stmt->execute();
$stmt->bind_result($public_key);
$stmt->fetch();
$stmt->close();

if (!$public_key) {
    http_response_code(401);
    echo json_encode(['error' => 'Unknown client_id']);
    exit;
}

// --- 4️⃣ Verify JWT signature ---
try {
    $decoded = JWT::decode($jwt, new Key($public_key, 'RS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token', 'details' => $e->getMessage()]);
    exit;
}

// --- 5️⃣ Check expiration ---
if ($decoded->exp < time()) {
    http_response_code(401);
    echo json_encode(['error' => 'Token expired']);
    exit;
}

// --- 6️⃣ Parse buwana_id from sub ---
$sub = $decoded->sub ?? null;
if (!$sub) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing sub claim']);
    exit;
}

if (strpos($sub, 'buwana_') === 0) {
    $buwana_id = intval(str_replace('buwana_', '', $sub));
} else {
    $buwana_id = intval($sub);
}

// --- 7️⃣ Fetch user info from DB ---
$stmt_user = $buwana_conn->prepare("
    SELECT email, first_name, earthling_emoji, community_id, continent_code
    FROM users_tb WHERE buwana_id = ?
");
$stmt_user->bind_param("i", $buwana_id);
$stmt_user->execute();
$stmt_user->bind_result($email, $first_name, $earthling_emoji, $community_id, $continent_code);
$stmt_user->fetch();
$stmt_user->close();

// --- 8️⃣ Return userinfo ---
$response = [
    'sub' => $sub,
    'email' => $email,
    'given_name' => $first_name,
    'buwana:earthlingEmoji' => $earthling_emoji,
    'buwana:community' => $community_id,
    'buwana:location.continent' => $continent_code
];

echo json_encode($response);
exit;
?>
