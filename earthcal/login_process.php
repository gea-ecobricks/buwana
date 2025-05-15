<?php
require_once '../earthenAuth_helper.php';
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // Include EarthCal database connection

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // Suppress warnings and notices
ini_set('display_errors', '0'); // Disable error display for production

$allowed_origins = [
    'https://cycles.earthen.io',
    'https://ecobricks.org',
    'https://gobrik.com',
    'http://localhost',
    'file://',
    'file:///home/russs/PycharmProjects/earthcalendar/',
    'https://cal.earthen.io'// Allow local Snap apps or filesystem-based origins
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

startSecureSession();

// PART 1: Grab user credentials from the POST request
$credential_key = $_POST['credential_key'] ?? '';
$password = $_POST['password'] ?? '';

// Input validation
if (empty($credential_key) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Credential key or password is missing.'
    ]);
    exit();
}

<?php
require_once '../earthenAuth_helper.php';
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // EarthCal database connection

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0'); // Suppress in production

// ======= DEV MODE TOGGLE =======
define('DEVMODE', true); // Set to false on production servers
// ===============================

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
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'CORS error: Invalid origin']);
    exit();
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

// === Main Logic ===

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request method. Use POST.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$buwana_id = $input['buwana_id'] ?? null;

if (empty($buwana_id) || !is_numeric($buwana_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing Buwana ID.']);
    exit();
}

try {
    $client_id = 'ecal_7f3da821d0a54f8a9b58'; // ✅ EarthCal app ID

    // 🔍 Ensure EarthCal is connected to this Buwana ID
    $check_sql = "SELECT COUNT(*) FROM user_app_connections_tb WHERE buwana_id = ? AND client_id = ?";
    $check_stmt = $buwana_conn->prepare($check_sql);
    $check_stmt->bind_param('is', $buwana_id, $client_id);
    $check_stmt->execute();
    $check_stmt->bind_result($connection_count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($connection_count == 0) {
        header("Location: https://buwana.ecobricks.org/app-connect.php?id=$buwana_id&client_id=$client_id");
        exit();
    }

    // ✅ Fetch user data from EarthCal's users_tb
    $sqlUser = "SELECT buwana_id, first_name, earthling_emoji, last_sync_ts, language_id, time_zone
                FROM users_tb WHERE buwana_id = ?";
    $stmtUser = $cal_conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $buwana_id);
    $stmtUser->execute();
    $userData = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    if (!$userData) {
        throw new Exception("User not found.");
    }

    // 📅 Fetch calendar names
    $calendar_names = [];
    $sql_calendars = "SELECT calendar_name FROM calendars_tb WHERE buwana_id = ?";
    $stmt_calendars = $cal_conn->prepare($sql_calendars);
    $stmt_calendars->bind_param('i', $buwana_id);
    $stmt_calendars->execute();
    $result_calendars = $stmt_calendars->get_result();
    while ($row = $result_calendars->fetch_assoc()) {
        $calendar_names[] = $row['calendar_name'];
    }
    $stmt_calendars->close();

    // 📌 Update login timestamp and count
    $sql_update = "UPDATE users_tb SET last_login = NOW(), login_count = login_count + 1 WHERE buwana_id = ?";
    $stmt_update = $buwana_conn->prepare($sql_update);
    $stmt_update->bind_param('i', $buwana_id);
    $stmt_update->execute();
    $stmt_update->close();

    // ✅ Final success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'buwana_id' => $userData['buwana_id'],
        'first_name' => $userData['first_name'],
        'earthling_emoji' => $userData['earthling_emoji'],
        'language_id' => $userData['language_id'],
        'time_zone' => $userData['time_zone'],
        'last_sync_ts' => $userData['last_sync_ts'],
        'calendar_names' => $calendar_names
    ]);
    exit();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit();
} finally {
    $buwana_conn->close();
    $cal_conn->close();
}
?>

