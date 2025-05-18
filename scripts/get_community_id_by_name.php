<?php
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);

    $stmt = $buwana_conn->prepare("SELECT community_id FROM communities_tb WHERE com_name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($community_id);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'community_id' => $community_id]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $buwana_conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
