<?php
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : null;

if ($app_id) {
    $sql = "SELECT DATE(u.connected_at) as dt, COUNT(*) as cnt
            FROM user_app_connections_tb u
            JOIN apps_tb a ON u.client_id = a.client_id
            WHERE a.app_id = ?
            GROUP BY DATE(u.connected_at)
            ORDER BY DATE(u.connected_at)";
    $stmt = $buwana_conn->prepare($sql);
    $stmt->bind_param('i', $app_id);
} else {
    $sql = "SELECT DATE(connected_at) as dt, COUNT(*) as cnt FROM user_app_connections_tb GROUP BY DATE(connected_at) ORDER BY DATE(connected_at)";
    $stmt = $buwana_conn->prepare($sql);
}

$labels = [];
$data = [];
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['dt'];
        $data[] = (int)$row['cnt'];
    }
    $stmt->close();
}

echo json_encode([
    'labels' => $labels,
    'datasets' => [[
        'label' => 'Registrations',
        'data' => $data,
        'fill' => false,
        'borderColor' => '#36a2eb'
    ]]
]);
?>
