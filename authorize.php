<?php
session_start();
require_once 'buwanaconn_env.php';

// 🔒 Log helper (optional)
function auth_log($msg) {
    error_log("[AUTHORIZE] $msg");
}

// Allow only GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'method_not_allowed']);
    exit;
}

// --- Capture OAuth query params
$client_id            = $_GET['client_id'] ?? null;
$response_type        = $_GET['response_type'] ?? null;
$redirect_uri         = $_GET['redirect_uri'] ?? null;
$scope                = $_GET['scope'] ?? '';
$state                = $_GET['state'] ?? null;
$nonce                = $_GET['nonce'] ?? null;
$lang                 = $_GET['lang'] ?? 'en';
$code_challenge       = $_GET['code_challenge'] ?? null;
$code_challenge_method= $_GET['code_challenge_method'] ?? null;

// --- Basic parameter validation
if (!$client_id || !$response_type || !$redirect_uri || !$state || !$nonce) {
    http_response_code(400);
    echo json_encode(['error' => 'missing_required_parameters']);
    exit;
}

if ($response_type !== 'code') {
    http_response_code(400);
    echo json_encode(['error' => 'unsupported_response_type']);
    exit;
}

// --- Validate client_id exists in DB
$stmt = $buwana_conn->prepare("SELECT client_id FROM apps_tb WHERE client_id = ?");
$stmt->bind_param("s", $client_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows !== 1) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_client_id']);
    exit;
}
$stmt->close();

// --- If user NOT logged in, store request and redirect to login.php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['pending_oauth_request'] = [
        'client_id' => $client_id,
        'response_type' => $response_type,
        'redirect_uri' => $redirect_uri,
        'scope' => $scope,
        'state' => $state,
        'nonce' => $nonce,
        'lang' => $lang,
        'code_challenge' => $code_challenge,
        'code_challenge_method' => $code_challenge_method
    ];

    header("Location: /$lang/login.php");
    exit;
}

// --- User is logged in: issue authorization code
$user_id = $_SESSION['user_id'];
$auth_code = bin2hex(random_bytes(32));

// Store code in DB
$stmt = $buwana_conn->prepare("INSERT INTO authorization_codes_tb
    (code, user_id, client_id, redirect_uri, scope, nonce, code_challenge, code_challenge_method, issued_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param(
    "sissssss",
    $auth_code,
    $user_id,
    $client_id,
    $redirect_uri,
    $scope,
    $nonce,
    $code_challenge,
    $code_challenge_method
);
$stmt->execute();
$stmt->close();

// Redirect back to client app with code
$redirect = $redirect_uri . '?code=' . urlencode($auth_code) . '&state=' . urlencode($state);
header("Location: $redirect");
exit;
?>
