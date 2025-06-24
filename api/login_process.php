<?php
require_once '../buwanaconn_env.php';

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0'); // Suppress in production

define('DEVMODE', true); // Toggle for development

// ---------------------------------------------
// Part 1: CORS Handling
// ---------------------------------------------
header('Content-Type: application/json; charset=utf-8');

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
    header('Access-Control-Allow-Credentials: true');
} elseif (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
} else {
    error_log('CORS error: Invalid or missing HTTP_ORIGIN - ' . $origin);
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CORS error: Invalid origin']);
    exit();
}

header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ---------------------------------------------
// Part 2: Session Initialization with Cookie Policy
// ---------------------------------------------
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// ---------------------------------------------
// Part 3: Collect Login Credentials
// ---------------------------------------------
$credential_key = $_POST['credential_key'] ?? '';
$password = $_POST['password'] ?? '';
$client_id = $_POST['client_id'] ?? '';

if (empty($credential_key) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'invalid_credential']);
    exit();
}

try {
    // ---------------------------------------------
    // Part 4: Validate Credential Key & Fetch buwana_id
    // ---------------------------------------------
    $sqlAuth = "SELECT buwana_id FROM credentials_tb WHERE credential_key = ?";
    $stmtAuth = $buwana_conn->prepare($sqlAuth);
    $stmtAuth->bind_param("s", $credential_key);
    $stmtAuth->execute();
    $stmtAuth->bind_result($buwana_id);
    $stmtAuth->fetch();
    $stmtAuth->close();

    // Check recent failed attempts
    $sql_failed = "SELECT failed_last_tm, failed_password_count FROM credentials_tb WHERE credential_key = ?";
    $stmt_failed = $buwana_conn->prepare($sql_failed);
    if ($stmt_failed) {
        $stmt_failed->bind_param('s', $credential_key);
        $stmt_failed->execute();
        $stmt_failed->bind_result($failed_last_tm, $failed_password_count);
        $stmt_failed->fetch();
        $stmt_failed->close();

        $current_time = new DateTime();
        $last_failed_time = $failed_last_tm ? new DateTime($failed_last_tm) : null;

        if ($last_failed_time && $current_time->getTimestamp() - $last_failed_time->getTimestamp() <= 600 && $failed_password_count >= 5) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'too_many_attempts']);
            exit();
        }

        if (is_null($last_failed_time) || $current_time->getTimestamp() - $last_failed_time->getTimestamp() > 600) {
            $failed_password_count = 0;
        }
    }

    if (!$buwana_id) {
        echo json_encode(['success' => false, 'message' => 'invalid_credential']);
        exit();
    }

    // ---------------------------------------------
    // Part 5: Verify Password Hash
    // ---------------------------------------------
    $sqlUserPass = "SELECT password_hash FROM users_tb WHERE buwana_id = ?";
    $stmtUserPass = $buwana_conn->prepare($sqlUserPass);
    $stmtUserPass->bind_param("i", $buwana_id);
    $stmtUserPass->execute();
    $stmtUserPass->bind_result($password_hash);
    $stmtUserPass->fetch();
    $stmtUserPass->close();

    if (!$buwana_id || !password_verify($password, $password_hash)) {
        // Password verification failed: log the failed attempt
        $sql_check_failed = "SELECT failed_last_tm, failed_password_count FROM credentials_tb WHERE credential_key = ?";
        $stmt_check_failed = $buwana_conn->prepare($sql_check_failed);
        $stmt_check_failed->bind_param('s', $credential_key);
        $stmt_check_failed->execute();
        $stmt_check_failed->bind_result($failed_last_tm, $failed_password_count);
        $stmt_check_failed->fetch();
        $stmt_check_failed->close();

        $current_time = new DateTime();
        $last_failed_time = $failed_last_tm ? new DateTime($failed_last_tm) : null;

        if (is_null($last_failed_time) || $current_time->getTimestamp() - $last_failed_time->getTimestamp() > 600) {
            $failed_password_count = 0;
        }

        $failed_password_count += 1;

        $sql_update_failed = "UPDATE credentials_tb SET failed_last_tm = NOW(), failed_password_count = ? WHERE credential_key = ?";
        $stmt_update_failed = $buwana_conn->prepare($sql_update_failed);
        if ($stmt_update_failed) {
            $stmt_update_failed->bind_param('is', $failed_password_count, $credential_key);
            $stmt_update_failed->execute();
            $stmt_update_failed->close();
        }

        if ($failed_password_count >= 5) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'too_many_attempts']);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'invalid_password']);
        }
        exit();
    }

    // ---------------------------------------------
    // Part 6: Update Login Statistics
    // ---------------------------------------------
    /*
        On successful login:
        - Record the timestamp of the login in both the users and credentials tables.
        - Increment the login count in users_tb.
        - Increment the use count in credentials_tb.
        These stats are useful for security audits, user engagement tracking, and account management.
    */
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

    // ---------------------------------------------
    // Part 7: Set Session State
    // ---------------------------------------------
    $_SESSION['buwana_id'] = $buwana_id;

    $connection_id = null;

    if (!empty($client_id)) {
        $sql_conn = "SELECT id FROM user_app_connections_tb WHERE buwana_id = ? AND client_id = ?";
        $stmt_conn = $buwana_conn->prepare($sql_conn);
        $stmt_conn->bind_param('is', $buwana_id, $client_id);
        $stmt_conn->execute();
        $stmt_conn->bind_result($connection_id);
        $stmt_conn->fetch();
        $stmt_conn->close();

        if (!$connection_id) {
            // 🔄 New Logic: Fetch user's preferred language for redirect
            $language_id = 'en'; // Default fallback
            $stmt_lang = $buwana_conn->prepare("SELECT language_id FROM users_tb WHERE buwana_id = ?");
            $stmt_lang->bind_param("i", $buwana_id);
            $stmt_lang->execute();
            $stmt_lang->bind_result($language_id);
            $stmt_lang->fetch();
            $stmt_lang->close();

            echo json_encode([
                'success' => true,
                'redirect' => "https://buwana.ecobricks.org/$language_id/app-connect.php?app=$client_id&id=$buwana_id"
            ]);
            exit();
        }

        $_SESSION['connection_id'] = $connection_id;
        $_SESSION['client_id'] = $client_id;
    }

    if (DEVMODE) {
        error_log("SESSION STATE: " . json_encode($_SESSION));
    }

    // ---------------------------------------------
    // Part 8: Return Success Response
    // ---------------------------------------------
    echo json_encode([
        'success' => true,
        'buwana_id' => $buwana_id,
        'client_id' => $client_id,
        'connection_id' => $connection_id,
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
