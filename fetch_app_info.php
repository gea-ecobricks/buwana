<?php
// fetch_app_info.php

session_start(); // Enable session to store client_id and app context
require_once 'buwanaconn_env.php'; // DB connection ($buwana_conn)

$default_client_id = 'gbrk_f2c61a85a4cd4b8b89a7';
$client_id = isset($_GET['app']) ? trim($_GET['app']) : ($_SESSION['client_id'] ?? $default_client_id);

// Store the current client_id in session for later pages
$_SESSION['client_id'] = $client_id;

// Prepare and run SQL to get app info
$sql = "SELECT * FROM apps_tb WHERE client_id = ?";
$stmt = $buwana_conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if app exists
    if ($result && $result->num_rows > 0) {
        $app_info = $result->fetch_assoc();
    } else {
        // Fallback to GoBrik app
        $stmt->close();
        $stmt = $buwana_conn->prepare($sql);
        $stmt->bind_param("s", $default_client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $app_info = $result ? $result->fetch_assoc() : [];
        $client_id = $default_client_id;
        $_SESSION['client_id'] = $default_client_id;
    }

    $stmt->close();
} else {
    error_log("Error preparing app info statement: " . $buwana_conn->error);
    $app_info = [];
}

// Merge with default fallbacks for safety
$app_info = array_merge([
    'app_name' => 'gobrik',
    'app_display_name' => 'goBrik',
    'app_logo_url' => 'https://gobrik.com/svgs/gobrik-logo.svg',
    'app_slogan' => 'Track your plastic and build with purpose.',
    'app_description' => 'Ecological platform to log your plastic and build with ecobricks.',
    'app_url' => 'https://gobrik.com',
    'redirect_uris' => 'https://gobrik.com/en/index.html',
    'privacy_policy_url' => 'https://gobrik.com/en/privacy.html',
    'terms_url' => 'https://gobrik.com/en/terms.html'
], $app_info);

// Save redirect URI for later redirect after signup/login
$redirect_uris = explode(',', $app_info['redirect_uris'] ?? '');
$_SESSION['redirect_url'] = trim($redirect_uris[0]) ?: $app_info['app_url'];

// 🔁 Update app's last_used_dt
$update_sql = "UPDATE apps_tb SET last_used_dt = NOW() WHERE client_id = ?";
$update_stmt = $buwana_conn->prepare($update_sql);
if ($update_stmt) {
    $update_stmt->bind_param("s", $client_id);
    $update_stmt->execute();
    $update_stmt->close();
}
?>
