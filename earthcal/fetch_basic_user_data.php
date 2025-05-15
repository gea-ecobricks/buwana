<?php
require_once '../earthenAuth_helper.php';
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // Include EarthCal database connection

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Suppress warnings and notices
ini_set('display_errors', '0'); // Disable error display for production

if (empty($origin)) {
    // Allow requests with no origin (e.g., file:// or local filesystem)
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');

    // Optionally log for debugging:
    // error_log("CORS allowed for local file-based request.");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); // Preflight complete
    }
}


// Normalize the HTTP_ORIGIN (remove trailing slashes or fragments)
$origin = isset($_SERVER['HTTP_ORIGIN']) ? rtrim($_SERVER['HTTP_ORIGIN'], '/') : '';

if (empty($origin)) {
    // Allow requests with no origin for local development
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');
} elseif (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');
} else {
    error_log('CORS error: Invalid or missing HTTP_ORIGIN - ' . $origin);
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'CORS error: Invalid origin']);
    exit();
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');
    exit(0);
}

$response = ['success' => false];

session_start();
require_once '../buwanaconn_env.php';

header('Content-Type: application/json');

// Accept buwana_id from session, POST, or GET (fallback for non-logged scenarios)
$buwana_id = $_SESSION['buwana_id'] ?? $_POST['buwana_id'] ?? $_GET['buwana_id'] ?? null;

if (!$buwana_id || !is_numeric($buwana_id)) {
    echo json_encode(['logged_in' => false]);
    exit;
}

$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji, continent_code, language_id, time_zone, email FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $earthling_emoji, $continent_code, $language_id, $time_zone, $email);
$stmt->fetch();
$stmt->close();

echo json_encode([
    'logged_in' => true,
    'buwana_id' => $buwana_id,
    'first_name' => $first_name,
    'earthling_emoji' => $earthling_emoji,
    'continent_code' => $continent_code,
    'language_id' => $language_id,
    'time_zone' => $time_zone,
    'email' => $email
]);
exit;
?>
