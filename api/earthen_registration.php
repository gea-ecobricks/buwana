<?php
// Enable error reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS headers
header('Access-Control-Allow-Origin: https://book.earthen.io');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Split API Key into ID and Secret
$apiKey = '662e2789d27acf008a250c99:cd1a8de113c3e3d984c6926727b2a7c1ed671b425f616119b3b37a377254634a';
list($id, $secret) = explode(':', $apiKey);

// Base64Url Encode helper function
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Create JWT
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256', 'kid' => $id]);
$now = time();
$payload = json_encode([
    'iat' => $now,
    'exp' => $now + 300, // valid for 5 minutes
    'aud' => '/v3/admin/'
]);

$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);
$signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", hex2bin($secret), true);
$base64UrlSignature = base64UrlEncode($signature);
$jwt = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";

// Read and decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (
    !$input ||
    empty($input['email']) ||
    empty($input['name']) ||
    empty($input['country'])
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields: email, name, country']);
    exit();
}

// Prepare Ghost member payload
$memberPayload = json_encode([
    'email' => $input['email'],
    'name' => $input['name'],
    'note' => 'Registered on the Earthen Ethics Earthbook site.',
    'labels' => [
        ['name' => 'Earthen Ethics registration'],
        ['name' => $input['country']]
    ]
]);

// Initialize cURL request to Ghost Admin API
$ghostApiUrl = 'https://earthen.io/ghost/api/v3/admin/members/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ghostApiUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Ghost ' . $jwt,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $memberPayload);

// Execute the request
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Error handling
if (curl_errno($ch)) {
    error_log('Curl error: ' . curl_error($ch));
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal cURL error']);
    exit();
}

curl_close($ch);

// Success response
if ($httpcode >= 200 && $httpcode < 300) {
    echo $response;
} else {
    error_log('Ghost API error (' . $httpcode . '): ' . $response);
    http_response_code($httpcode);
    echo $response;
}
?>
