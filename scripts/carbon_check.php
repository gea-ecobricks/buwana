<?php
// carbon-proxy.php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check if URL parameter is set
if (!isset($_GET['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

// Sanitize the URL input
$url = urlencode($_GET['url']);

// Fetch from Website Carbon API
$api_url = "https://api.websitecarbon.com/b?url=$url";
$response = @file_get_contents($api_url);

if ($response === FALSE) {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to fetch data from Website Carbon API']);
    exit;
}

// Return API response
echo $response;
?>
