<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

$response = ['success' => false];

require_once '../buwanaconn_env.php';

function sendJsonError($error) {
    error_log("Signup-2 Error: $error"); // 🧠 Log it!
    ob_end_clean();
    echo json_encode(['success' => false, 'error' => $error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $buwana_id = $_GET['id'] ?? null;

    if (empty($buwana_id) || !is_numeric($buwana_id)) {
        sendJsonError('invalid_buwana_id');
    }

    // 🧼 Sanitize inputs
    $credential_value = filter_var(trim($_POST['credential_value']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password_hash'] ?? '';

    if (!$credential_value) sendJsonError('invalid_email');
    if (strlen($password) < 6) sendJsonError('invalid_password');

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 🎯 Get user's name (used later for potential messages/logs)
    $stmt = $buwana_conn->prepare("SELECT first_name FROM users_tb WHERE buwana_id = ?");
    if (!$stmt) sendJsonError('db_error_first_name');
    $stmt->bind_param("i", $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();

    if (empty($first_name)) sendJsonError('missing_first_name');

    // 🔁 Check for existing email used by a different buwana_id
    $stmt = $buwana_conn->prepare("SELECT COUNT(*), buwana_id FROM users_tb WHERE email = ?");
    if (!$stmt) sendJsonError('db_error_check_email');
    $stmt->bind_param("s", $credential_value);
    $stmt->execute();
    $stmt->bind_result($count, $existing_buwana_id);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0 && $existing_buwana_id != $buwana_id) {
        sendJsonError('duplicate_email');
    }

    // 🔄 Update user record
    $stmt = $buwana_conn->prepare("UPDATE users_tb
        SET email = ?, password_hash = ?,
            account_status = 'signup-2_process run. Email unverified',
            last_login = NOW()
        WHERE buwana_id = ?");
    if (!$stmt) sendJsonError('db_error_user_update');
    $stmt->bind_param("ssi", $credential_value, $password_hash, $buwana_id);
    if (!$stmt->execute()) sendJsonError('user_update_failed');
    $stmt->close();

    // 🔑 Update credentials record
    $stmt = $buwana_conn->prepare("UPDATE credentials_tb SET credential_key = ?, credential_type = 'email' WHERE buwana_id = ?");
    if (!$stmt) sendJsonError('db_error_credentials');
    $stmt->bind_param("si", $credential_value, $buwana_id);
    $stmt->execute();
    $stmt->close();

    // ✅ Send response
    $response['success'] = true;
    $response['redirect'] = "signup-3.php?id=" . urlencode($buwana_id);
}

ob_end_clean();
echo json_encode($response);
exit();
?>
