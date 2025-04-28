<?php
// carbon_check.php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check if URL parameter is set
if (!isset($_GET['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

$url = urlencode($_GET['url']);
$api_url = "https://api.websitecarbon.com/b?url=$url";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // verify SSL
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; CarbonCheckBot/1.0)");

// Execute cURL request
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Check if there was an error
if ($response === false || $httpcode >= 400) {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to fetch data from Website Carbon API', 'details' => $curl_error]);
    exit;
}

// Success
echo $response;
?>
