<?php
require_once '../gobrikconn_env.php'; // Include database connection

$ecobricker_id = $_GET['ecobricker_id'] ?? null;

if (!$ecobricker_id || !is_numeric($ecobricker_id)) {
    echo json_encode(['error' => 'Invalid Ecobricker ID']);
    exit();
}

$sql = "SELECT full_name, gea_status, user_roles, user_capabilities FROM tb_ecobrickers WHERE ecobricker_id = ?";
$stmt = $gobrik_conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Database error']);
    exit();
}

$stmt->bind_param("i", $ecobricker_id);
$stmt->execute();
$stmt->bind_result($full_name, $gea_status, $user_roles, $user_capabilities);
$stmt->fetch();

echo json_encode([
    'full_name' => $full_name,
    'gea_status' => $gea_status,
    'user_roles' => $user_roles,
    'user_capabilities' => $user_capabilities,
]);

$stmt->close();
$gobrik_conn->close();
?>
