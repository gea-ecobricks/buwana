<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

header('Content-Type: application/json');

if (empty($_SESSION['buwana_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit();
}

$buwana_id = intval($_SESSION['buwana_id']);
$app_id = isset($_POST['app_id']) ? intval($_POST['app_id']) : 0;

if (!$app_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid app id']);
    exit();
}

$stmt = $buwana_conn->prepare("SELECT a.app_id FROM apps_tb a JOIN app_owners_tb ao ON ao.app_id = a.app_id WHERE a.app_id=? AND ao.buwana_id=?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $buwana_conn->error]);
    exit();
}
$stmt->bind_param('ii', $app_id, $buwana_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit();
}
$stmt->close();

$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA
];

$resource = openssl_pkey_new($config);
if (!$resource) {
    echo json_encode(['success' => false, 'error' => 'Key generation failed']);
    exit();
}

openssl_pkey_export($resource, $private_key_pem);
$details = openssl_pkey_get_details($resource);
$public_key_pem = $details['key'];

$stmt = $buwana_conn->prepare("UPDATE apps_tb SET jwt_private_key=?, jwt_public_key=? WHERE app_id=?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $buwana_conn->error]);
    exit();
}
$stmt->bind_param('ssi', $private_key_pem, $public_key_pem, $app_id);
$success = $stmt->execute();
$error = $stmt->error;
$stmt->close();

if (!$success) {
    echo json_encode(['success' => false, 'error' => $error]);
    exit();
}

echo json_encode(['success' => true, 'public_key' => $public_key_pem]);
exit();
?>
