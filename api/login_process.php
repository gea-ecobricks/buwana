<?php
require_once '../buwanaconn_env.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0'); // Suppress in production

// ======= DEV MODE TOGGLE =======
define('DEVMODE', true); // Set to false on production servers
// ===============================

// Set response headers
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

// Start a secure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate the session ID periodically to prevent session fixation
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 1800) { // Regenerate session ID every 30 minutes
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// Retrieve POST data
$credential_key = $_POST['credential_key'] ?? '';
$password = $_POST['password'] ?? '';
$client_id = $_POST['client_id'] ?? '';

// Input validation
if (empty($credential_key) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'invalid_credential'
    ]);
    exit();
}

try {
 // Step 1: Retrieve buwana_id from credentials_tb
 $sqlAuth = "SELECT buwana_id FROM credentials_tb WHERE credential_key = ?";
 $stmtAuth = $buwana_conn->prepare($sqlAuth);
 $stmtAuth->bind_param("s", $credential_key);
 $stmtAuth->execute();
 $stmtAuth->bind_result($buwana_id);
 $stmtAuth->fetch();
 $stmtAuth->close();

 if (!$buwana_id) {
     echo json_encode(['success' => false, 'message' => 'invalid_credential']);
     exit();
 }

 // Step 2: Retrieve password_hash from users_tb
 $sqlUserPass = "SELECT password_hash FROM users_tb WHERE buwana_id = ?";
 $stmtUserPass = $buwana_conn->prepare($sqlUserPass);
 $stmtUserPass->bind_param("i", $buwana_id);
 $stmtUserPass->execute();
 $stmtUserPass->bind_result($password_hash);
 $stmtUserPass->fetch();
 $stmtUserPass->close();


    if (!$buwana_id || !password_verify($password, $password_hash)) {
        // Handle failed login attempts
        $sql_check_failed = "SELECT failed_last_tm, failed_password_count FROM credentials_tb WHERE credential_key = ?";
        $stmt_check_failed = $buwana_conn->prepare($sql_check_failed);
        $stmt_check_failed->bind_param('s', $credential_key);
        $stmt_check_failed->execute();
        $stmt_check_failed->bind_result($failed_last_tm, $failed_password_count);
        $stmt_check_failed->fetch();
        $stmt_check_failed->close();

        // Check if failed_last_tm exists and if it's within the last 10 minutes
        $current_time = new DateTime();
        $last_failed_time = $failed_last_tm ? new DateTime($failed_last_tm) : null;

        if (is_null($last_failed_time) || $current_time->getTimestamp() - $last_failed_time->getTimestamp() > 600) {
            // Reset failed_password_count if no entry or if the last failure was more than 10 minutes ago
            $failed_password_count = 0;
        }

        // Increment failed_password_count and update failed_last_tm
        $failed_password_count += 1;

        $sql_update_failed = "UPDATE credentials_tb
                              SET failed_last_tm = NOW(),
                                  failed_password_count = ?
                              WHERE credential_key = ?";
        $stmt_update_failed = $buwana_conn->prepare($sql_update_failed);
        $stmt_update_failed->bind_param('is', $failed_password_count, $credential_key);
        $stmt_update_failed->execute();
        $stmt_update_failed->close();

        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'invalid_password'
        ]);
        exit();
    }

    // Update login timestamps
    $sql_update_user = "UPDATE users_tb SET last_login = NOW(), login_count = login_count + 1 WHERE buwana_id = ?";
    $stmt_update_user = $buwana_conn->prepare($sql_update_user);
    $stmt_update_user->bind_param('i', $buwana_id);
    $stmt_update_user->execute();
    $stmt_update_user->close();

    $sql_update_credential = "UPDATE credentials_tb SET last_login = NOW(), times_used = times_used + 1 WHERE buwana_id = ?";
    $stmt_update_credential = $buwana_conn->prepare($sql_update_credential);
    $stmt_update_credential->bind_param('i', $buwana_id);
    $stmt_update_credential->execute();
    $stmt_update_credential->close();

    // Set session variable
    $_SESSION['buwana_id'] = $buwana_id;

    // Check if the user is connected to the app
    $connected_apps = [];
    if (!empty($client_id)) {
        $check_sql = "SELECT COUNT(*) FROM user_app_connections_tb WHERE buwana_id = ? AND client_id = ?";
        $check_stmt = $buwana_conn->prepare($check_sql);
        $check_stmt->bind_param('is', $buwana_id, $client_id);
        $check_stmt->execute();
        $check_stmt->bind_result($connection_count);
        $check_stmt->fetch();
        $check_stmt->close();

        //REDIRECT TO CONNECT
            error_log("ClientID: $client_id | BuwanaID: $buwana_id | Connection Count: $connection_count");


        if ($connection_count == 0) {
            // 🚪 Redirect immediately to connect the app
            echo json_encode([
                'success' => true,
                'redirect' => "https://buwana.ecobricks.org/en/app-conect.php?app=$client_id"
            ]);
            exit();

        } else {
            $connected_apps[] = $client_id;
        }
    }

    // Successful login response
    echo json_encode([
        'success' => true,
        'buwana_id' => $buwana_id,
        'connected' => true,
        'connected_apps' => implode(',', $connected_apps),
        'message' => 'login_successful'
    ]);

    exit();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit();
} finally {
    $buwana_conn->close();
}
?>
