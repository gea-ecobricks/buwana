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

$app_logo_url        = $_POST['app_logo_url'] ?? '';
$app_logo_dark_url   = $_POST['app_logo_dark_url'] ?? '';
$app_square_icon_url = $_POST['app_square_icon_url'] ?? '';
$app_wordmark_url    = $_POST['app_wordmark_url'] ?? '';
$app_wordmark_dark_url = $_POST['app_wordmark_dark_url'] ?? '';

$success = false;
$error_message = '';

$sql = "UPDATE apps_tb a
        JOIN app_owners_tb ao ON ao.app_id = a.app_id
        SET a.app_logo_url=?, a.app_logo_dark_url=?, a.app_square_icon_url=?, a.app_wordmark_url=?, a.app_wordmark_dark_url=?
        WHERE a.app_id=? AND ao.buwana_id=?";
$stmt = $buwana_conn->prepare($sql);
if ($stmt) {
    if ($stmt->bind_param('ssssssi', $app_logo_url, $app_logo_dark_url, $app_square_icon_url, $app_wordmark_url, $app_wordmark_dark_url, $app_id, $buwana_id)) {
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
