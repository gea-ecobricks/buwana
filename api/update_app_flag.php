<?php
session_start();
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

$buwana_id = intval($_SESSION['buwana_id'] ?? 0);
$app_id = intval($_POST['app_id'] ?? 0);
$field = $_POST['field'] ?? '';
$value = isset($_POST['value']) ? intval($_POST['value']) : null;

if(!$buwana_id || !$app_id || !in_array($field, ['is_active','allow_signup']) || !in_array($value,[0,1])){
    echo json_encode(['success'=>false]);
    exit();
}

$sql = $field === 'is_active'
    ? 'UPDATE apps_tb SET is_active=? WHERE app_id=? AND owner_buwana_id=?'
    : 'UPDATE apps_tb SET allow_signup=? WHERE app_id=? AND owner_buwana_id=?';
$stmt = $buwana_conn->prepare($sql);
$success = false;
if($stmt){
    $stmt->bind_param('iii',$value,$app_id,$buwana_id);
    $success = $stmt->execute();
    $stmt->close();
}

echo json_encode(['success'=>$success]);
