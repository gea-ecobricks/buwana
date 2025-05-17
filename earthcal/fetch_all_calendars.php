<?php
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // EarthCal database connection

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0'); // Suppress in production

// ======= DEV MODE TOGGLE =======
define('DEVMODE', true); // Set to false on production servers
// ===============================

// Set JSON header early
header('Content-Type: application/json; charset=utf-8');

// Handle CORS
$allowed_origins = [
    'https://cal.earthen.io',
    'https://cycles.earthen.io',
    'https://ecobricks.org',
    'https://gobrik.com',
    'http://localhost:8080'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$origin = rtrim($origin, '/'); // Normalize

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

// ===== API Logic =====
$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response['message'] = 'Invalid request method. Use POST.';
    echo json_encode($response);
    exit();
}

// Parse and validate JSON
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!$input) {
    $response['message'] = 'Malformed or empty JSON payload.';
    echo json_encode($response);
    exit();
}

$buwana_id = $input['buwana_id'] ?? null;

if (empty($buwana_id) || !is_numeric($buwana_id)) {
    $response['message'] = 'Invalid or missing Buwana ID.';
    echo json_encode($response);
    exit();
}

error_log("Fetching calendars for Buwana ID: $buwana_id");

try {
    // Fetch last sync timestamp
    $sqlUser = "SELECT last_sync_ts FROM users_tb WHERE buwana_id = ?";
    $stmtUser = $cal_conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $buwana_id);
    $stmtUser->execute();
    $userData = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // Fetch personal calendars
    $sqlPersonalCalendars = "SELECT calendar_id, calendar_name FROM calendars_tb WHERE buwana_id = ? AND deleted = 0";
    $stmtPersonal = $cal_conn->prepare($sqlPersonalCalendars);
    $stmtPersonal->bind_param("i", $buwana_id);
    $stmtPersonal->execute();
    $personalCalendars = $stmtPersonal->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtPersonal->close();

    // Fetch subscribed calendars
    $sqlSubscribedCalendars = "SELECT c.calendar_id, c.calendar_name FROM cal_subscriptions_tb s
                               JOIN calendars_tb c ON s.calendar_id = c.calendar_id
                               WHERE s.buwana_id = ? AND c.deleted = 0";
    $stmtSubscribed = $cal_conn->prepare($sqlSubscribedCalendars);
    $stmtSubscribed->bind_param("i", $buwana_id);
    $stmtSubscribed->execute();
    $subscribedCalendars = $stmtSubscribed->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtSubscribed->close();

    // Fetch all public calendars
    $sqlPublicCalendars = "SELECT calendar_id, calendar_name FROM calendars_tb WHERE calendar_public = 1 AND deleted = 0";
    $stmtPublic = $cal_conn->prepare($sqlPublicCalendars);
    $stmtPublic->execute();
    $publicCalendars = $stmtPublic->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtPublic->close();

    // Build response
    $response['success'] = true;
    $response['last_sync_ts'] = $userData['last_sync_ts'] ?? null;
    $response['personal_calendars'] = $personalCalendars;
    $response['subscribed_calendars'] = $subscribedCalendars;
    $response['public_calendars'] = $publicCalendars;

} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    $response['message'] = 'An error occurred: ' . $e->getMessage();
} finally {
    $cal_conn->close();
}

echo json_encode($response);
exit();
