<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start(); // Start output buffering

$response = ['success' => false];

require_once '../buwanaconn_env.php'; // Buwana DB only (no GoBrik now!)

function sendJsonError($error) {
    ob_end_clean(); // Clear any previous output
    echo json_encode(['success' => false, 'error' => $error]);
    exit();
}

// PART 1: Process the incoming POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $buwana_id = $_GET['id'] ?? null;

    if (empty($buwana_id) || !is_numeric($buwana_id)) {
        sendJsonError('invalid_buwana_id');
    }

    // Sanitize inputs
    $credential_value = filter_var($_POST['credential_value'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password_hash'];

    if (empty($credential_value)) sendJsonError('invalid_email');
    if (empty($password) || strlen($password) < 6) sendJsonError('invalid_password');

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // PART 2: Get user's first name from Buwana DB
    $sql = "SELECT first_name FROM users_tb WHERE buwana_id = ?";
    $stmt = $buwana_conn->prepare($sql);
    if (!$stmt) sendJsonError('db_error_first_name');
    $stmt->bind_param("i", $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();

    if (empty($first_name)) sendJsonError('missing_first_name');

    // PART 3: Check for email uniqueness in Buwana DB
    $sql = "SELECT COUNT(*), buwana_id FROM users_tb WHERE email = ?";
    $stmt = $buwana_conn->prepare($sql);
    if (!$stmt) sendJsonError('db_error_check_email');
    $stmt->bind_param("s", $credential_value);
    $stmt->execute();
    $stmt->bind_result($count, $existing_buwana_id);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0 && $existing_buwana_id != $buwana_id) {
        sendJsonError('duplicate_email');
    }

    // PART 4: Update the user record
    $sql = "UPDATE users_tb
            SET email = ?, password_hash = ?,
                account_status = 'signup-2_process run. Email unverified',
                last_login = NOW()
            WHERE buwana_id = ?";
    $stmt = $buwana_conn->prepare($sql);
    if (!$stmt) sendJsonError('db_error_user_update');

    $stmt->bind_param("ssi", $credential_value, $password_hash, $buwana_id);
    if (!$stmt->execute()) sendJsonError('user_update_failed');
    $stmt->close();

    // PART 5: Update credentials_tb
    $sql = "UPDATE credentials_tb
            SET credential_key = ?, credential_type = 'e-mail'
            WHERE buwana_id = ?";
    $stmt = $buwana_conn->prepare($sql);
    if (!$stmt) sendJsonError('db_error_credentials');
    $stmt->bind_param("si", $credential_value, $buwana_id);
    $stmt->execute();
    $stmt->close();

    // ✅ Success – Redirect to confirm page with buwana_id
    $response['success'] = true;
    $response['redirect'] = "signup-3.php?id=" . urlencode($buwana_id);
}

ob_end_clean();
echo json_encode($response);
exit();
?>
