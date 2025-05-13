<?php
require_once '../earthenAuth_helper.php';
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // Include EarthCal database connection

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Suppress warnings and notices
ini_set('display_errors', '0'); // Disable error display for production

$allowed_origins = [
    'https://cal.earthen.io',
    'https://cycles.earthen.io',
    'https://ecobricks.org',
    'https://gobrik.com',
    'http://localhost',
    'file://' // Allow local Snap apps or filesystem-based origins
];

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

// Check the request method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response['message'] = 'Invalid request method. Use POST.';
    echo json_encode($response);
    exit();
}

// Get the JSON input from the request
$input = json_decode(file_get_contents('php://input'), true);
$buwana_id = $input['buwana_id'] ?? null;

// Validate inputs
if (empty($buwana_id) || !is_numeric($buwana_id)) {
    $response['message'] = 'Invalid or missing Buwana ID.';
    echo json_encode($response);
    exit();
}

try {
    // Fetch user data from users_tb
    $sqlUser = "SELECT first_name, last_sync_ts, continent_code, location_full FROM users_tb WHERE buwana_id = ?";
    $stmtUser = $cal_conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $buwana_id);
    $stmtUser->execute();
    $userData = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // Fetch personal calendars
    $sqlPersonalCalendars = "SELECT calendar_id, calendar_name FROM calendars_tb WHERE buwana_id = ?";
    $stmtPersonal = $cal_conn->prepare($sqlPersonalCalendars);
    $stmtPersonal->bind_param("i", $buwana_id);
    $stmtPersonal->execute();
    $personalCalendars = $stmtPersonal->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtPersonal->close();

    // Fetch subscribed calendars
    $sqlSubscribedCalendars = "SELECT c.calendar_id, c.calendar_name FROM cal_subscriptions_tb s
                               JOIN calendars_tb c ON s.calendar_id = c.calendar_id
                               WHERE s.buwana_id = ?";
    $stmtSubscribed = $cal_conn->prepare($sqlSubscribedCalendars);
    $stmtSubscribed->bind_param("i", $buwana_id);
    $stmtSubscribed->execute();
    $subscribedCalendars = $stmtSubscribed->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtSubscribed->close();

    // Fetch public calendars
    $sqlPublicCalendars = "SELECT calendar_id, calendar_name FROM calendars_tb WHERE calendar_public = 1";
    $stmtPublic = $cal_conn->prepare($sqlPublicCalendars);
    $stmtPublic->execute();
    $publicCalendars = $stmtPublic->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtPublic->close();

    // Prepare response
    $response['success'] = true;
    $response['user'] = $userData;
    $response['personal_calendars'] = $personalCalendars;
    $response['subscribed_calendars'] = $subscribedCalendars;
    $response['public_calendars'] = $publicCalendars;


} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    $cal_conn->close();
}

echo json_encode($response);
exit();
?>
