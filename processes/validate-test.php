<?php
// processes/validate-test.php

header('Content-Type: text/plain');

function fetchUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "❌ cURL error: " . curl_error($ch) . "\n";
        return false;
    }
    curl_close($ch);
    return $result;
}

$issuer = 'https://buwana.ecobricks.org';
$config_url = "$issuer/.well-known/openid_configuration.php";

echo "🔍 Fetching OpenID Configuration from: $config_url\n";

$config_json = fetchUrl($config_url);
$config = json_decode($config_json, true);

if (!$config) {
    die("❌ Failed to fetch or decode OpenID configuration.\n");
}

print_r($config);

// Validate the JWKS URI
if (!isset($config['jwks_uri'])) {
    die("❌ 'jwks_uri' missing in configuration.\n");
}

$jwks_uri = $config['jwks_uri'];
echo "\n🔐 Fetching JWKS from: $jwks_uri\n";

$jwks_json = fetchUrl($jwks_uri);
$jwks = json_decode($jwks_json, true);

if (!$jwks || !isset($jwks['keys'])) {
    die("❌ Failed to fetch or decode JWKS keys.\n");
}

echo "✅ JWKS contains " . count($jwks['keys']) . " key(s):\n";
foreach ($jwks['keys'] as $key) {
    echo "- Key ID (kid): " . ($key['kid'] ?? 'N/A') . "\n";
    echo "  Algorithm: " . ($key['alg'] ?? 'N/A') . "\n";
    echo "  Use: " . ($key['use'] ?? 'N/A') . "\n";
}

echo "\n🎉 Validation Complete.\n";
