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
    header('HTTP/1.1 403 Forbidden');
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

$buwana_id = $_SESSION['buwana_id'] ?? $_POST['buwana_id'] ?? $_GET['buwana_id'] ?? null;
$client_id = $_POST['client_id'] ?? $_GET['client_id'] ?? null;

if (!$buwana_id || !is_numeric($buwana_id)) {
    echo json_encode(['logged_in' => false]);
    exit;
}

$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji, continent_code, language_id, time_zone, email, last_login, location_full FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $earthling_emoji, $continent_code, $language_id, $time_zone, $email, $last_login, $location_full);
$stmt->fetch();
$stmt->close();

// Fetch user_app_connections_tb.id if client_id is available
$connection_id = null;
if (!empty($client_id)) {
    $conn_stmt = $buwana_conn->prepare("SELECT id FROM user_app_connections_tb WHERE buwana_id = ? AND client_id = ?");
    $conn_stmt->bind_param("is", $buwana_id, $client_id);
    $conn_stmt->execute();
    $conn_stmt->bind_result($connection_id);
    $conn_stmt->fetch();
    $conn_stmt->close();
}

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
