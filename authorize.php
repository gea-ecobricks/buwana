<?php
session_start();

// 1️⃣ Capture incoming query parameters
$client_id     = $_GET['client_id'] ?? null;
$response_type = $_GET['response_type'] ?? null;
$redirect_uri  = $_GET['redirect_uri'] ?? null;
$scope         = $_GET['scope'] ?? '';
$state         = $_GET['state'] ?? null;
$nonce         = $_GET['nonce'] ?? null;
$lang          = $_GET['lang'] ?? 'en'; // Default to 'en' if not provided

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

// 4️⃣ Check that client_id is valid (you'd normally validate against apps_tb in DB)
$valid_client_ids = ['ecal_7f3da821d0a54f8a9b58']; // Hardcoded for now
if (!in_array($client_id, $valid_client_ids)) {
    http_response_code(400);
    echo "Invalid client_id.";
    exit;
}

// 5️⃣ Is user already logged in?
if (!isset($_SESSION['user_id'])) {
    // Not logged in yet, save the request params to session and redirect to login.php
    $_SESSION['pending_oauth_request'] = [
        'client_id' => $client_id,
        'response_type' => $response_type,
        'redirect_uri' => $redirect_uri,
        'scope' => $scope,
        'state' => $state,
        'nonce' => $nonce,
        'lang' => $lang
    ];
    // Redirect to the proper language login page
    header("Location: /$lang/login.php");
    exit;
}

// 6️⃣ If user is logged in, generate authorization code

// (In production you should generate a secure random code and store it in DB with user info)
$auth_code = bin2hex(random_bytes(16));
$_SESSION['auth_codes'][$auth_code] = [
    'user_id' => $_SESSION['user_id'],
    'client_id' => $client_id,
    'scope' => $scope,
    'nonce' => $nonce,
    'issued_at' => time()
];

// 7️⃣ Redirect back to Earthcal with code and state
$redirect = $redirect_uri . '?code=' . $auth_code . '&state=' . urlencode($state);
header("Location: $redirect");
exit;

?>
