<?php
// processes/validate-test.php

header('Content-Type: text/plain');

$issuer = 'https://buwana.ecobricks.org';
$config_url = "$issuer/.well-known/openid_configuration.php";

echo "🔍 Fetching OpenID Configuration from: $config_url\n";

$config = json_decode(file_get_contents($config_url), true);
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

$jwks = json_decode(file_get_contents($jwks_uri), true);
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
?>