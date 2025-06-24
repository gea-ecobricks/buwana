<?php
session_start();

// 1️⃣ Capture incoming query parameters
$client_id     = $_GET['client_id'] ?? null;
$response_type = $_GET['response_type'] ?? null;
$redirect_uri  = $_GET['redirect_uri'] ?? null;
$scope         = $_GET['scope'] ?? '';
$state         = $_GET['state'] ?? null;
$nonce         = $_GET['nonce'] ?? null;
$lang          = $_GET['lang'] ?? 'en';
$code_challenge = $_GET['code_challenge'] ?? null;
$code_challenge_method = $_GET['code_challenge_method'] ?? null;

// 2️⃣ Validate language ID
$valid_languages = ['en', 'fr', 'id', 'de', 'zh', 'ar', 'es'];
if (!in_array($lang, $valid_languages)) {
    $lang = 'en';
}

// 3️⃣ Basic parameter validation
if (!$client_id || !$response_type || !$redirect_uri || !$state || !$nonce) {
    http_response_code(400);
    echo "Missing required parameters.";
    exit;
}

if ($response_type !== 'code') {
    http_response_code(400);
    echo "Unsupported response_type";
    exit;
}

// 4️⃣ Validate client_id (Normally: DB lookup, for now hardcoded)
$valid_client_ids = ['ecal_7f3da821d0a54f8a9b58'];
if (!in_array($client_id, $valid_client_ids)) {
    http_response_code(400);
    echo "Invalid client_id.";
    exit;
}

// 5️⃣ If user not logged in, redirect to login and store pending request
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

// 6️⃣ If logged in, issue authorization code
$auth_code = bin2hex(random_bytes(16));
$_SESSION['auth_codes'][$auth_code] = [
    'user_id' => $_SESSION['user_id'],
    'client_id' => $client_id,
    'scope' => $scope,
    'nonce' => $nonce,
    'issued_at' => time(),
    'code_challenge' => $code_challenge,
    'code_challenge_method' => $code_challenge_method
];

// 7️⃣ Redirect back to client with code and state
$redirect = $redirect_uri . '?code=' . $auth_code . '&state=' . urlencode($state);
header("Location: $redirect");
exit;
?>
