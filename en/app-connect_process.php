<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';
require_once '../scripts/create_user.php'; // Includes createUserInClientApp()

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


// --- STEP 5: Load client connection file ---
$client_env_path = "../config/{$app_name}_env.php";

if (!file_exists($client_env_path)) {
    error_log("❌ Client config file not found at: $client_env_path");
    die("❌ Missing DB config: $client_env_path");
}

require_once $client_env_path;
error_log("✅ Loaded client config: $client_env_path");

// --- Validate $client_conn existence and connection ---
if (!isset($client_conn) || !($client_conn instanceof mysqli) || $client_conn->connect_error) {
    error_log("❌ Client DB connection is not set or is invalid.");
    die("❌ Client DB connection could not be initialized.");
}

error_log("✅ Client DB connection ($app_name) established successfully.");



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
