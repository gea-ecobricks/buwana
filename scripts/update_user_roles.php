<?php
require_once '../gobrikconn_env.php'; // Include database connection

$data = json_decode(file_get_contents('php://input'), true);

$ecobricker_id = $data['ecobricker_id'] ?? null;
$gea_status = $data['gea_status'] ?? null;
$user_roles = $data['user_roles'] ?? null;
$capabilities = $data['capabilities'] ?? null;

if (!$ecobricker_id || !is_numeric($ecobricker_id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid Ecobricker ID']);
    exit();
}

$sql = "UPDATE tb_ecobrickers SET gea_status = ?, user_roles = ?, user_capabilities = ? WHERE ecobricker_id = ?";
$stmt = $gobrik_conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit();
}

$stmt->bind_param("sssi", $gea_status, $user_roles, $capabilities, $ecobricker_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error updating user roles']);
}

$stmt->close();
$gobrik_conn->close();
?>
