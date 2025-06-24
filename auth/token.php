<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../buwanaconn_env.php';
use Firebase\JWT\JWT;

// Logging helper
$authLogFile = dirname(__DIR__) . '/logs/auth.log';
function auth_log($message) {
    global $authLogFile;
    if (!file_exists(dirname($authLogFile))) {
        mkdir(dirname($authLogFile), 0777, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . "] TOKEN: " . $message . PHP_EOL, 3, $authLogFile);
}

auth_log("Token endpoint called");

// Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "method_not_allowed"]);
    exit;
}

// Read POST body
$grant_type = $_POST['grant_type'] ?? '';
$code = $_POST['code'] ?? '';
$redirect_uri = $_POST['redirect_uri'] ?? '';
$client_id = $_POST['client_id'] ?? '';
$client_secret = $_POST['client_secret'] ?? null;
$code_verifier = $_POST['code_verifier'] ?? null;

if ($grant_type !== 'authorization_code' || !$code || !$redirect_uri || !$client_id) {
    http_response_code(400);
    echo json_encode(["error" => "invalid_request"]);
    exit;
}

// Validate client_id and get client_secret + jwt_private_key
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

// Validate authorization code
if (!isset($_SESSION['auth_codes'][$code])) {
    http_response_code(400);
    echo json_encode(["error" => "invalid_code"]);
    exit;
}

$auth_data = $_SESSION['auth_codes'][$code];
unset($_SESSION['auth_codes'][$code]); // One-time use only

// Validate either client_secret OR PKCE code_verifier
if (!empty($client_secret)) {
    // Legacy confidential client: validate client_secret
    if ($client_secret !== $expected_secret) {
        http_response_code(401);
        echo json_encode(["error" => "invalid_client_secret"]);
        exit;
    }
} elseif (!empty($auth_data['code_challenge'])) {
    // PKCE flow: validate code_verifier against code_challenge
    if (empty($code_verifier)) {
        http_response_code(400);
        echo json_encode(["error" => "missing_code_verifier"]);
        exit;
    }

    // Calculate SHA256(code_verifier) → base64url encoding
    $hashed = hash('sha256', $code_verifier, true);
    $calculated_challenge = rtrim(strtr(base64_encode($hashed), '+/', '-_'), '=');

    if ($calculated_challenge !== $auth_data['code_challenge']) {
        http_response_code(400);
        echo json_encode(["error" => "invalid_code_verifier"]);
        exit;
    }
    auth_log("PKCE verification successful.");
} else {
    // No client_secret, no code_challenge → invalid
    http_response_code(400);
    echo json_encode(["error" => "missing_authentication"]);
    exit;
}

// At this point, authenticated successfully
// Fetch user data
$user_id = $auth_data['user_id'];
$stmt_user = $buwana_conn->prepare("SELECT email, first_name, open_id FROM users_tb WHERE buwana_id = ?");
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$stmt_user->bind_result($email, $first_name, $open_id);
$stmt_user->fetch();
$stmt_user->close();

$now = time();
$expire = $now + 3600;

// Generate id_token
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

// Generate access_token
$access_token_payload = [
    "iss" => "https://buwana.ecobricks.org",
    "sub" => $open_id ?? ("buwana_$user_id"),
    "scope" => $auth_data['scope'],
    "aud" => $client_id,
    "exp" => $expire,
    "iat" => $now
];
$access_token = JWT::encode($access_token_payload, $jwt_private_key, 'RS256', $client_id);

// Return tokens
header('Content-Type: application/json');
echo json_encode([
    "access_token" => $access_token,
    "id_token" => $id_token,
    "token_type" => "Bearer",
    "expires_in" => 3600
]);
exit;
?>
