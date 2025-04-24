<?php
// ✅ This script is triggered after email confirmation. Updates the Buwana user record and checks for Earthen registration.

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once("../buwanaconn_env.php");

// Get buwana_id from URL
$buwana_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$buwana_id) {
    die("Invalid or missing Buwana ID.");
}

$current_date_time = date('Y-m-d H:i:s');

// PART 1: Fetch user's email
$sql_fetch_email = "SELECT email FROM users_tb WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql_fetch_email);
if (!$stmt) die("DB error fetching email: " . $buwana_conn->error);
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($email);
$stmt->fetch();
$stmt->close();

if (empty($email)) {
    die("Email not found for Buwana ID $buwana_id.");
}

// PART 2: Update credentials_tb (email confirmed)
$sql_update_credentials = "UPDATE credentials_tb SET email_confirm_dt = ? WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql_update_credentials);
if ($stmt) {
    $stmt->bind_param("si", $current_date_time, $buwana_id);
    $stmt->execute();
    $stmt->close();
} else {
    error_log("❌ DB error updating credentials_tb: " . $buwana_conn->error);
}

// ✅ PART 3: Update users_tb 'notes' with email confirmation
$note = "Step 3: User's email confirmed.";
$sql_update_notes = "UPDATE users_tb SET notes = ? WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql_update_notes);
if ($stmt) {
    $stmt->bind_param("si", $note, $buwana_id);
    $stmt->execute();
    $stmt->close();
} else {
    error_log("❌ DB error updating users_tb.notes: " . $buwana_conn->error);
}

// PART 4: Check if user is registered on Ghost (Earthen.io)
$email_encoded = urlencode($email);
$ghost_api_url = "https://earthen.io/ghost/api/v3/admin/members/?filter=email:$email_encoded";

$apiKey = '66db68b5cff59f045598dbc3:5c82d570631831f277b1a9b4e5840703e73a68e948812b2277a0bc11c12c973f';
list($id, $secret) = explode(':', $apiKey);

// Create JWT for Ghost API
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256', 'kid' => $id]);
$now = time();
$payload = json_encode(['iat' => $now, 'exp' => $now + 300, 'aud' => '/v3/admin/']);

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$jwt = base64UrlEncode($header) . '.' . base64UrlEncode($payload);
$signature = hash_hmac('sha256', $jwt, hex2bin($secret), true);
$jwt .= '.' . base64UrlEncode($signature);

// cURL to Ghost API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ghost_api_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Ghost $jwt",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// PART 5: Check if Ghost response was successful
$registered = 0;
if ($http_code >= 200 && $http_code < 300) {
    $data = json_decode($response, true);
    if (!empty($data['members'])) {
        $registered = 1;
    }
} else {
    error_log("❌ Ghost API failed: $http_code - $response");
}

// PART 6: Update Buwana 'credentials_tb' with Earthen status
$sql_earthen_update = "UPDATE credentials_tb SET earthen_registered = ? WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql_earthen_update);
if ($stmt) {
    $stmt->bind_param("ii", $registered, $buwana_id);
    $stmt->execute();
    $stmt->close();
} else {
    error_log("❌ DB error updating earthen_registered: " . $buwana_conn->error);
}

// ✅ All done! Redirect to activation step 3
header("Location: activate-3.php?id=$buwana_id");
exit();
?>
