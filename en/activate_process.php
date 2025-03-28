<?php
//THis page is triggered by confirm-email and sends the user to activate-3 if they have a buwana account.  If not they go to activate-2
//update at part2

//PART 0 start up get ids
require_once '../earthenAuth_helper.php'; // Include the authentication helper functions

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get parameters from the URL
$ecobricker_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$buwana_id = filter_input(INPUT_GET, 'buwana_id', FILTER_SANITIZE_NUMBER_INT);

// Check if the user is logged in
if (isLoggedIn()) {
    header('Location: dashboard.php'); // Redirect to dashboard if the user is logged in
    exit();
}

// Initialize variables
$email_addr = '';
$current_date_time = date('Y-m-d H:i:s');

// PART 1: Update GoBrik Database for tb_ecobrickers
require_once("../gobrikconn_env.php");

$sql_update_ecobricker = "UPDATE tb_ecobrickers
                          SET email_confirm_dt = ?,
                              account_notes = 'Signing up... Password set. E-mail verified.'
                          WHERE ecobricker_id = ?";
$stmt_update_ecobricker = $gobrik_conn->prepare($sql_update_ecobricker);

if ($stmt_update_ecobricker) {
    $stmt_update_ecobricker->bind_param('si', $current_date_time, $ecobricker_id);
    $stmt_update_ecobricker->execute();
    $stmt_update_ecobricker->close();
} else {
    die('Error preparing statement for updating tb_ecobrickers: ' . $gobrik_conn->error);
}


// Fetch the email address
$sql_fetch_email = "SELECT email_addr FROM tb_ecobrickers WHERE ecobricker_id = ?";
$stmt_fetch_email = $gobrik_conn->prepare($sql_fetch_email);

if ($stmt_fetch_email) {
    $stmt_fetch_email->bind_param('i', $ecobricker_id);
    $stmt_fetch_email->execute();
    $stmt_fetch_email->bind_result($email_addr);
    $stmt_fetch_email->fetch();
    $stmt_fetch_email->close();
} else {
    die('Error preparing statement for fetching email: ' . $gobrik_conn->error);
}

// PART 2: Update Buwana Database if buwana_id is provided
if (!empty($buwana_id)) {
    require_once("../buwanaconn_env.php");

    $sql_update_credentials = "UPDATE credentials_tb SET email_confirm_dt = ? WHERE buwana_id = ?";
    $stmt_update_credentials = $buwana_conn->prepare($sql_update_credentials);

    if ($stmt_update_credentials) {
        $stmt_update_credentials->bind_param('si', $current_date_time, $buwana_id);
        $stmt_update_credentials->execute();
        $stmt_update_credentials->close();
    } else {
        die('Error preparing statement for updating credentials_tb: ' . $buwana_conn->error);
    }
}




// PART 3: Check registration status on the Ghost platform

// Prepare and encode the email address for use in the API URL
$email_encoded = urlencode($email_addr);
$ghost_api_url = "https://earthen.io/ghost/api/v3/admin/members/?filter=email:$email_encoded";

// Split API Key into ID and Secret for JWT generation
$apiKey = '66db68b5cff59f045598dbc3:5c82d570631831f277b1a9b4e5840703e73a68e948812b2277a0bc11c12c973f';
list($id, $secret) = explode(':', $apiKey);

// Prepare the header and payload for the JWT
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256', 'kid' => $id]);
$now = time();
$payload = json_encode([
    'iat' => $now,
    'exp' => $now + 300, // Token valid for 5 minutes
    'aud' => '/v3/admin/' // Corrected audience value to match the expected pattern
]);

// Base64Url Encode function
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Encode Header and Payload
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);

// Create the Signature
$signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, hex2bin($secret), true);
$base64UrlSignature = base64UrlEncode($signature);

// Create the JWT token
$jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

// Set up the cURL request to the Ghost Admin API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ghost_api_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Ghost ' . $jwt,
    'Content-Type: application/json'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true); // Use GET to fetch data

// Execute the cURL session
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    error_log('Curl error: ' . curl_error($ch));
}

if ($http_code >= 200 && $http_code < 300) {
    // Successful response, parse the JSON data
    $response_data = json_decode($response, true);

    // Check if members are found
    $registered = 0; // Default to not registered
    if ($response_data && isset($response_data['members']) && is_array($response_data['members']) && count($response_data['members']) > 0) {
        $registered = 1; // Member with the given email exists
    }


/// PART 4
// Update GoBrik Database with registration status
$sql_update_registration = "UPDATE tb_ecobrickers
                            SET earthen_registered = ?,
                                account_notes = ?
                            WHERE ecobricker_id = ?";
$stmt_update_registration = $gobrik_conn->prepare($sql_update_registration);

if ($stmt_update_registration) {
    // Determine account_notes based on registration status
    if ($registered) {
        $account_notes = "Signing up... Password set. E-mail verified. Already registered to Earthen.";
    } else {
        $account_notes = "Signing up... Password set. E-mail verified. Not yet registered to Earthen.";
    }

    // Bind parameters and execute the query
    $stmt_update_registration->bind_param('isi', $registered, $account_notes, $ecobricker_id);
    $stmt_update_registration->execute();
    $stmt_update_registration->close();
} else {
    error_log('Error preparing statement for updating earthen_registered in tb_ecobrickers: ' . $gobrik_conn->error);
}


// Check if buwana_id exists
if (empty($buwana_id)) {
    // Redirect to activate-2.php if buwana_id does not exist
    header('Location: activate-2.php?id=' . urlencode($ecobricker_id));
    exit();
}

// Continue if buwana_id exists and update Buwana Database with registration status
$sql_update_earthen_status = "UPDATE credentials_tb SET earthen_registered = ? WHERE buwana_id = ?";
$stmt_update_earthen_status = $buwana_conn->prepare($sql_update_earthen_status);

if ($stmt_update_earthen_status) {
    $stmt_update_earthen_status->bind_param('ii', $registered, $buwana_id);
    $stmt_update_earthen_status->execute();
    $stmt_update_earthen_status->close();
} else {
    error_log('Error preparing statement for updating earthen_registered in credentials_tb: ' . $buwana_conn->error);
}

} else {
    // Handle error
    error_log('HTTP status ' . $http_code . ': ' . $response);
    echo '<script>console.error("API call to Earthen.io failed with HTTP code: ' . $http_code . '");</script>';
    // Skip further processing and redirect
    header('Location: activate-3.php?id=' . $buwana_id);
    exit();
}

// Close the cURL session
curl_close($ch);

// Close database connections
$gobrik_conn->close();
if (isset($buwana_conn)) {
    $buwana_conn->close();
}

// Redirect to the next activation page
header('Location: activate-3.php?id=' . $buwana_id);
exit();
?>




