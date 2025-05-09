<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';
require_once 'create_user.php'; // Includes createUserInClientApp()

// Get POSTed form data
$buwana_id = isset($_POST['buwana_id']) ? (int) $_POST['buwana_id'] : null;
$client_id = $_POST['client_id'] ?? null;

// Validate inputs
if (!$buwana_id || !$client_id) {
    die("❌ Missing Buwana ID or Client ID.");
}

// Get app info
$app_name = $app_info['app_name'] ?? 'default_app';
$app_dashboard_url = $app_info['app_dashboard_url'] ?? '/';

// 🔗 Load the correct client DB connection
$client_db_path = "../client_dbs/{$client_id}_conn.php";
if (!file_exists($client_db_path)) {
    die("❌ Could not find DB connection for client ID: $client_id");
}
require_once $client_db_path; // Provides $client_conn

if (!isset($client_conn)) {
    die("❌ Client DB connection not properly initialized.");
}

// 🧠 Fetch full user data from Buwana
$stmt = $buwana_conn->prepare("SELECT * FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param('i', $buwana_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$userData = $result->fetch_assoc()) {
    die("❌ Buwana user not found.");
}
$stmt->close();

// ✅ Create the user in the client app
$response = createUserInClientApp($buwana_id, $userData, $app_name, $client_conn, $buwana_conn, $client_id);

// 🎯 Redirect or error
if ($response['success']) {
    header("Location: $app_dashboard_url");
    exit;
} else {
    echo "<h2>⚠️ Failed to connect your account</h2>";
    echo "<p>Error: " . htmlspecialchars($response['error']) . "</p>";
    echo "<p><a href='javascript:history.back()'>Try again</a></p>";
}
?>
