<?php
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0');

define('DEVMODE', true);

$allowed_origins = [
    'https://cal.earthen.io',
    'https://cycles.earthen.io',
    'https://ecobricks.org',
    'https://gobrik.com',
    'http://localhost:8080'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$origin = rtrim($origin, '/');

if (DEVMODE && empty($origin)) {
    header('Access-Control-Allow-Origin: http://localhost:8080');
} elseif (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    error_log('CORS error: Invalid or missing HTTP_ORIGIN - ' . $origin);
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CORS error: Invalid origin']);
    exit();
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

header('Content-Type: application/json');

session_start();

// Fetch session values
$buwana_id = $_SESSION['buwana_id'] ?? null;
$connection_id = $_SESSION['connection_id'] ?? null;

if (!$buwana_id || !is_numeric($buwana_id)) {
    echo json_encode(['logged_in' => false]);
    exit;
}

// Fetch user data
$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji, continent_code, language_id, time_zone, email, last_login, location_full FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $earthling_emoji, $continent_code, $language_id, $time_zone, $email, $last_login, $location_full);
$stmt->fetch();
$stmt->close();

// Output
echo json_encode([
    'logged_in' => true,
    'buwana_id' => $buwana_id,
    'first_name' => $first_name,
    'earthling_emoji' => $earthling_emoji,
    'continent_code' => $continent_code,
    'language_id' => $language_id,
    'time_zone' => $time_zone,
    'email' => $email,
    'last_login' => $last_login,
    'location_full' => $location_full,
    'connection_id' => $connection_id
]);
exit;
?>
