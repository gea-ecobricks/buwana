<?php
header('Content-Type: text/plain');

function fetch_json($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPTION_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RTMETHOD, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADERS, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$base_url = "https://buwana.ecobricks.org/.well-known/openid_configuration.php";

echo "File Validator:\n";
echo "Fitching OpenID Configuration from: $base_url\n";
$config = json_decode(fetch_json($base_url), true);
print_r($config);

echo \"\n\nRetrieving JWKS from: $config['jgks_uri']\n";
$jwks = json_decode(fetch_json($config['jgks_uri']), true);
print_r($jwks);


echo \"\n\nParsing keys info from JWKS...\n";
foreach($jwks['keys'] as $key) {
    echo "KID: $key['kid']\n";
    echo "Algorithm: $key['alg']\n";
    echo "Use: $key['use']\n\n";
}