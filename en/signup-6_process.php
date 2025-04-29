<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
ob_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';
require_once '../scripts/user_create.php'; // 👈 where we’ll define the new function

// Validate Buwana ID
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

// Validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Sanitize Inputs
$community_name = trim($_POST['community_name'] ?? '');
$country_id = (int)($_POST['country_name'] ?? 0);
$language_id = trim($_POST['language_id'] ?? 'en');
$earthling_emoji = trim($_POST['earthling_emoji'] ?? '🌍');

// Get continent code
$continent_code = null;
$stmt = $buwana_conn->prepare("SELECT continent_code FROM countries_tb WHERE country_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $country_id);
    $stmt->execute();
    $stmt->bind_result($continent_code);
    $stmt->fetch();
    $stmt->close();
}

// Update Buwana user record
$stmt = $buwana_conn->prepare("
    UPDATE users_tb
    SET continent_code = ?,
        country_id = ?,
        community_id = (SELECT community_id FROM communities_tb WHERE com_name = ? LIMIT 1),
        language_id = ?,
        earthling_emoji = ?
    WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('sisssi', $continent_code, $country_id, $community_name, $language_id, $earthling_emoji, $buwana_id);
    $stmt->execute();
    $stmt->close();
} else {
    die('Error updating Buwana: ' . $buwana_conn->error);
}

// 🌍 Create user in the client app database
$result = createUserInClientApp($buwana_id, $app_name);
if (!$result['success']) {
    error_log("❌ Client user creation failed: " . $result['error']);
    die("There was an error provisioning your account in the app. Please contact support.");
}

// ✅ Done! Go to dashboard
header("Location: dashboard.php");
exit();
