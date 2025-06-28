<?php
// Turn on or off error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session before any output
session_start();

// Determine language from form submission
$lang = isset($_POST['lang']) ? preg_replace('/[^a-zA-Z]/', '', $_POST['lang']) : 'en';

// Client ID for redirects
$client_id = isset($_POST['client_id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['client_id']) : '';

// Database credentials
include '../buwanaconn_env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? trim($_POST['token']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';

    if ($token && $password && $confirmPassword) {
        if ($password === $confirmPassword && strlen($password) >= 6) {
            // Check if token is valid and fetch basic user info
            $stmt = $buwana_conn->prepare("SELECT buwana_id, first_name, email FROM users_tb WHERE password_reset_token = ?");
            if (!$stmt) {
                die("Prepare statement failed: " . $buwana_conn->error);
            }
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($buwana_id, $first_name, $email);
            $stmt->fetch();
            $stmt->close();

            if ($email) {
                // Fetch credential key for login link
                $credential_key = '';
                $stmt_cred = $buwana_conn->prepare("SELECT credential_key FROM credentials_tb WHERE buwana_id = ? LIMIT 1");
                if ($stmt_cred) {
                    $stmt_cred->bind_param('i', $buwana_id);
                    $stmt_cred->execute();
                    $stmt_cred->bind_result($credential_key);
                    $stmt_cred->fetch();
                    $stmt_cred->close();
                }

                // Update the user's password and reset token details in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $currentDateTime = date('Y-m-d H:i:s');
                $stmt = $buwana_conn->prepare("UPDATE users_tb SET password_hash = ?, password_last_reset_dt = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE email = ?");
                if (!$stmt) {
                    die("Prepare statement failed: " . $buwana_conn->error);
                }
                $stmt->bind_param("sss", $hashedPassword, $currentDateTime, $email);
                $stmt->execute();
                $stmt->close();

                $redirect_url = '../' . $lang . '/password-reset.php?status=reset&firstname=' . urlencode($first_name) . '&id=' . urlencode($buwana_id) . '&key=' . urlencode($credential_key) . '&app=' . urlencode($client_id);
                header('Location: ' . $redirect_url);
                exit();
            } else {
                echo '<script>alert("Invalid token. Please try reseting your password again."); window.location.href = "../' . $lang . '/login.php?app=' . urlencode($client_id) . '";</script>';
                exit();
            }
        } else {
            echo '<script>alert("Passwords do not match or are not long enough. Please try again."); window.location.href = "../' . $lang . '/password-reset.php?token=' . urlencode($token) . '";</script>';
            exit();
        }
    } else {
        echo '<script>alert("All fields are required. Please try again."); window.location.href = "../' . $lang . '/password-reset.php?token=' . urlencode($token) . '";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid request. Please try again reseting your password again."); window.location.href = "../' . $lang . '/login.php?app=' . urlencode($client_id) . '";</script>';
    exit();
}

// Close the database connection
$buwana_conn->close();
?>
