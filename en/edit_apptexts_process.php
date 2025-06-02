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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update_app'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

$buwana_id = intval($_SESSION['buwana_id']);
$app_id    = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;

$app_slogan      = $_POST['app_slogan'] ?? '';
$app_terms_txt   = $_POST['app_terms_txt'] ?? '';
$app_privacy_txt = $_POST['app_privacy_txt'] ?? '';
$app_emojis_array = $_POST['app_emojis_array'] ?? '';

$success = false;
$error_message = '';

$sql = "UPDATE apps_tb SET app_slogan=?, app_terms_txt=?, app_privacy_txt=?, app_emojis_array=? WHERE app_id=? AND owner_buwana_id=?";
$stmt = $buwana_conn->prepare($sql);
if ($stmt) {
    if ($stmt->bind_param('ssssii', $app_slogan, $app_terms_txt, $app_privacy_txt, $app_emojis_array, $app_id, $buwana_id)) {
        $success = $stmt->execute();
        if (!$success) {
            $error_message = $stmt->error;
        }
    } else {
        $error_message = $stmt->error;
    }
    $stmt->close();
} else {
    $error_message = $buwana_conn->error;
}

echo json_encode(['success' => $success, 'error' => $error_message]);
exit();
?>
