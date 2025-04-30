<?php
// ----------------------------------------
// 🌐 signup-6_process.php
// Final step of Buwana signup: Save final user settings,
// and provision account in the client app (e.g. GoBrik, Earthcal).
// ----------------------------------------

error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';          // Provides $app_info[]
require_once '../scripts/create_user.php';      // Defines createUserInClientApp()

// --- STEP 1: Validate and extract inputs ---
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

$selected_community = $_POST['community_name'] ?? '';
$selected_country_name = $_POST['country_name'] ?? '';
$selected_language_id = $_POST['language_id'] ?? '';
$earthling_emoji = $_POST['earthling_emoji'] ?? '🌍';

// --- STEP 2: Load app info from $app_info ---
$app_name = $app_info['app_name'] ?? null;
$app_login_url = $app_info['app_login_url'] ?? '/';
$client_id = $app_info['client_id'] ?? null;

if (!$app_name || !$client_id) {
    error_log("❌ Missing app configuration: app_name = $app_name | client_id = $client_id");
    die("❌ Missing app configuration details.");
}

error_log("🔍 App Info Loaded: name = $app_name, login_url = $app_login_url, client_id = $client_id");


// --- STEP 3: Resolve country_id & continent_code ---
$set_country_id = null;
$set_continent_code = null;

$stmt = $buwana_conn->prepare("SELECT country_id, continent_code FROM countries_tb WHERE country_name = ?");
$stmt->bind_param('s', $selected_country_name);
$stmt->execute();
$stmt->bind_result($set_country_id, $set_continent_code);
$stmt->fetch();
$stmt->close();

// --- STEP 4: Update Buwana User Record ---
$update_sql = "
    UPDATE users_tb
    SET continent_code = ?, country_id = ?,
        community_id = (SELECT community_id FROM communities_tb WHERE com_name = ?),
        language_id = ?, earthling_emoji = ?
    WHERE buwana_id = ?
";

$stmt = $buwana_conn->prepare($update_sql);
$stmt->bind_param('sisssi', $set_continent_code, $set_country_id, $selected_community, $selected_language_id, $earthling_emoji, $buwana_id);
$stmt->execute();
$stmt->close();

// --- STEP 5: Load client connection file ---
$client_env_path = "../config/{$app_name}_env.php";
if (!file_exists($client_env_path)) {
    error_log("❌ Client config file not found at: $client_env_path");
    die("❌ Missing DB config: $client_env_path");
}

require_once $client_env_path;
error_log("✅ Loaded client config: $client_env_path");

// --- Detect correct DB connection object ---
$client_conn = $cal_conn ?? $gobrik_conn ?? null;

if (!$client_conn || !($client_conn instanceof mysqli)) {
    error_log("❌ Client DB connection variable is not set or invalid.");
    if (isset($cal_conn)) error_log("🧪 cal_conn is set");
    if (isset($gobrik_conn)) error_log("🧪 gobrik_conn is set");
    die("❌ Client DB connection could not be initialized.");
}

error_log("✅ Client DB connection established successfully.");

// --- STEP 6: Fetch Buwana user fields for provisioning ---
$userData = [];
$stmt = $buwana_conn->prepare("
    SELECT first_name, last_name, full_name, email, terms_of_service, profile_pic,
           country_id, language_id, continent_code, location_full, location_watershed,
           location_lat, location_long, community_id, earthling_emoji
    FROM users_tb
    WHERE buwana_id = ?
");
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result(
    $userData['first_name'], $userData['last_name'], $userData['full_name'], $userData['email'],
    $userData['terms_of_service'], $userData['profile_pic'], $userData['country_id'],
    $userData['language_id'], $userData['continent_code'], $userData['location_full'],
    $userData['location_watershed'], $userData['location_lat'], $userData['location_long'],
    $userData['community_id'], $userData['earthling_emoji']
);
$stmt->fetch();
$stmt->close();

// --- STEP 7: Create user in client app ---
$response = createUserInClientApp($buwana_id, $userData, $app_name, $client_conn, $buwana_conn, $client_id);

if (!$response['success']) {
    error_log("❌ Client user creation failed: " . $response['error']);
    die("There was an error provisioning your account in the app. Please contact support.");
}

// ✅ Redirect to login with first-time status
if ($result['success']) {
    $login_redirect = $app_login_url . "?status=firsttime&id=" . urlencode($buwana_id);
    header("Location: $login_redirect");
    exit();
} else {
    error_log("❌ Failed to create user in client app: " . $result['error']);
    die("❌ Failed to create user in client app.");
}
?>