<?php
session_start();
require_once '../buwanaconn_env.php';

header('Content-Type: application/json');

$buwana_id = $_SESSION['buwana_id'] ?? null;

if (!$buwana_id || !is_numeric($buwana_id)) {
    echo json_encode(['logged_in' => false]);
    exit;
}

$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji, continent_code, language_id, time_zone FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $earthling_emoji, $continent_code, $language_id, $time_zone);
$stmt->fetch();
$stmt->close();

echo json_encode([
    'logged_in' => true,
    'buwana_id' => $buwana_id,
    'first_name' => $first_name,
    'earthling_emoji' => $earthling_emoji,
    'continent_code' => $continent_code,
    'language_id' => $language_id,
    'time_zone' => $time_zone
]);
exit;
?>