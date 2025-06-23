<?php
require_once '../earthenAuth_helper.php';
require_once '../vendor/autoload.php';
require_once '../gobrikconn_env.php';
require_once '../buwanaconn_env.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

startSecureSession();

// Logging helper for authentication flow
$authLogFile = dirname(__DIR__) . '/logs/auth.log';
function auth_log($message) {
    global $authLogFile;
    if (!file_exists(dirname($authLogFile))) {
        mkdir(dirname($authLogFile), 0777, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . "] PROCESS: " . $message . PHP_EOL, 3, $authLogFile);
}

auth_log('Login process started');

$credential_key = $_POST['credential_key'] ?? '';
$password = $_POST['password'] ?? '';
$lang = $_POST['lang'] ?? 'en';

$redirect = filter_var($_POST['redirect'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
$client_id = $_POST['client_id'] ?? ($_SESSION['client_id'] ?? null);
$csrf_token = $_POST['csrf_token'] ?? '';

// CSRF check
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    auth_log('CSRF token validation failed');
    header("Location: ../$lang/login.php?status=invalid_csrf");
    exit();
}

auth_log("Credentials received for: $credential_key");

if (empty($credential_key) || empty($password)) {
    auth_log('Empty credential key or password');
    header("Location: ../$lang/login.php?status=empty_fields&key=" . urlencode($credential_key));
    exit();
}

// Check credentials_tb
$sql_credential = "SELECT buwana_id FROM credentials_tb WHERE credential_key = ?";
$stmt_credential = $buwana_conn->prepare($sql_credential);

if ($stmt_credential) {
    $stmt_credential->bind_param('s', $credential_key);
    $stmt_credential->execute();
    $stmt_credential->store_result();

    if ($stmt_credential->num_rows === 1) {
        $stmt_credential->bind_result($buwana_id);
        $stmt_credential->fetch();
        $stmt_credential->close();

        // Fetch user
        $sql_user = "SELECT password_hash, first_name, email, open_id FROM users_tb WHERE buwana_id = ?";
        $stmt_user = $buwana_conn->prepare($sql_user);

        if ($stmt_user) {
            $stmt_user->bind_param('i', $buwana_id);
            $stmt_user->execute();
            $stmt_user->store_result();

            if ($stmt_user->num_rows === 1) {
                $stmt_user->bind_result($password_hash, $first_name, $email, $open_id);
                $stmt_user->fetch();

                if (password_verify($password, $password_hash)) {
                    auth_log("Password verified for buwana_id $buwana_id");

                    // Successful login, update login stats
                    $buwana_conn->query("UPDATE users_tb SET last_login = NOW(), login_count = login_count + 1 WHERE buwana_id = $buwana_id");
                    $buwana_conn->query("UPDATE credentials_tb SET last_login = NOW(), times_used = times_used + 1 WHERE buwana_id = $buwana_id");

                    $_SESSION['buwana_id'] = $buwana_id;
                    $_SESSION['user_id'] = $buwana_id;  // <-- This is needed for authorize.php

                    // Check if this was part of OAuth flow:
                    if (isset($_SESSION['pending_oauth_request'])) {
                        $params = http_build_query($_SESSION['pending_oauth_request']);
                        unset($_SESSION['pending_oauth_request']);
                        header("Location: /authorize?$params");
                        exit();
                    }

                    // Otherwise proceed as normal
                    $default_dashboard = "../$lang/dashboard.php";
                    $redirect_url = !empty($redirect) ? $redirect : $default_dashboard;
                    header("Location: $redirect_url");
                    exit();
                } else {
                    auth_log('Invalid password');
                    header("Location: ../$lang/login.php?status=invalid_password&key=" . urlencode($credential_key));
                    exit();
                }
            }
            $stmt_user->close();
        }
    } else {
        auth_log('Credential not found');
        header("Location: ../$lang/login.php?status=invalid_credential&key=" . urlencode($credential_key));
        exit();
    }
} else {
    auth_log('Error preparing credential query');
    die('Database error.');
}

$buwana_conn->close();
?>
