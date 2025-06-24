<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'buwanaconn_env.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// --- Logging helper ---
$authLogFile = dirname(__DIR__) . 'logs/auth.log';
function auth_log($message) {
    global $authLogFile;
    if (!file_exists(dirname($authLogFile))) {
        mkdir(dirname($authLogFile), 0777, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . "] TOKEN: " . $message . PHP_EOL, 3, $authLogFile);
}

auth_log("Token request received");

// --- CORS Headers for frontend PKCE clients (Earthcal) ---
$allowedOrigins = [
    "https://earthcal.app"
];
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST");
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "method_not_allowed"]);
    exit;
}

// Read POST params
$grant_type     = $_POST['grant_type'] ?? '';
$code           = $_POST['code'] ?? '';
$redirect_uri   = $_POST['redirect_uri'] ?? '';
$client_id      = $_POST['client_id'] ?? '';
$client_secret  = $_POST['client_secret'] ?? '';
$code_verifier  = $_POST['code_verifier'] ?? '';

if ($grant_type !== 'authorization_code' || !$code || !$redirect_uri || !$client_id) {
    http_response_code(400);
    echo json_encode(["error" => "invalid_request"]);
    exit;
}

// Look up app in DB
$stmt = $buwana_conn->prepare("SELECT client_secret, jwt_private_key FROM apps_tb WHERE client_id = ?");
$stmt->bind_param('s', $client_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
    http_response_code(401);
    echo json_encode(["error" => "invalid_client"]);
    exit;
}

$stmt->bind_result($expected_secret, $jwt_private_key);
$stmt->fetch();
$stmt->close();

// --- HYBRID LOGIC STARTS HERE ---
if (!empty($client_secret)) {
    // Confidential client flow
    auth_log("Confidential client flow for $client_id");

    if (empty($expected_secret)) {
        http_response_code(401);
        echo json_encode(["error" => "client_secret_not_allowed"]);
        exit;
    }

    if ($client_secret !== $expected_secret) {
        http_response_code(401);
        echo json_encode(["error" => "invalid_client_secret"]);
        exit;
    }

    // No PKCE verification needed

} else {
    // Public PKCE flow
    auth_log("PKCE flow for $client_id");

    if (!isset($_SESSION['pkce_codes'][$code])) {
        http_response_code(400);
        echo json_encode(["error" => "pkce_code_not_found"]);
        exit;
    }

    $pkceData = $_SESSION['pkce_codes'][$code];
    $expected_challenge = $pkceData['code_challenge'];
    $challenge_method = $pkceData['code_challenge_method'] ?? 'plain';

    // Verify code_verifier -> code_challenge
    if (empty($code_verifier)) {
        http_response_code(400);
        echo json_encode(["error" => "missing_code_verifier"]);
        exit;
    }

    $calculated_challenge = ($challenge_method === 'S256')
        ? rtrim(strtr(base64_encode(hash('sha256', $code_verifier, true)), '+/', '-_'), '=')
        : $code_verifier;

    if ($calculated_challenge !== $expected_challenge) {
        http_response_code(401);
        echo json_encode(["error" => "invalid_code_verifier"]);
        exit;
    }

    // Clean up used PKCE code
    unset($_SESSION['pkce_codes'][$code]);
}

// Validate authorization code (common for both flows)
if (!isset($_SESSION['auth_codes'][$code])) {
    http_response_code(400);
    echo json_encode(["error" => "invalid_code"]);
    exit;
}

$auth_data = $_SESSION['auth_codes'][$code];
unset($_SESSION['auth_codes'][$code]);

// Fetch user info
$user_id = $auth_data['user_id'];
$stmt_user = $buwana_conn->prepare("SELECT email, first_name, open_id FROM users_tb WHERE buwana_id = ?");
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$stmt_user->bind_result($email, $first_name, $open_id);
$stmt_user->fetch();
$stmt_user->close();

// Issue tokens
$now = time();
$expire = $now + 3600;

$id_token_payload = [
    "iss" => "https://buwana.ecobricks.org",
    "sub" => $open_id ?? ("buwana_$user_id"),
    "aud" => $client_id,
    "exp" => $expire,
    "iat" => $now,
    "email" => $email,
    "given_name" => $first_name,
    "nonce" => $auth_data['nonce']
];

$id_token = JWT::encode($id_token_payload, $jwt_private_key, 'RS256', $client_id);

// Access token (simplified)
$access_token_payload = [
    "iss" => "https://buwana.ecobricks.org",
    "sub" => $open_id ?? ("buwana_$user_id"),
    "scope" => $auth_data['scope'],
    "aud" => $client_id,
    "exp" => $expire,
    "iat" => $now
];

$access_token = JWT::encode($access_token_payload, $jwt_private_key, 'RS256', $client_id);

header('Content-Type: application/json');
echo json_encode([
    "access_token" => $access_token,
    "id_token" => $id_token,
    "token_type" => "Bearer",
    "expires_in" => 3600
]);
exit;
