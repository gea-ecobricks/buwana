<?php
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

$days = 30;
$labels = [];
$data = [];

$startDate = new DateTime();
$startDate->modify('-' . ($days - 1) . ' days');
$initialDate = $startDate->format('Y-m-d');

// Total users before the range
$stmt = $buwana_conn->prepare("SELECT COUNT(*) FROM users_tb WHERE DATE(created_at) < ?");
$stmt->bind_param('s', $initialDate);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();
$currentTotal = (int)$total;

// New signups per day for the range
$stmt = $buwana_conn->prepare("SELECT DATE(created_at) as dt, COUNT(*) as cnt FROM users_tb WHERE created_at >= ? GROUP BY DATE(created_at) ORDER BY DATE(created_at)");
$stmt->bind_param('s', $initialDate);
$stmt->execute();
$result = $stmt->get_result();
$signups = [];
while ($row = $result->fetch_assoc()) {
    $signups[$row['dt']] = (int)$row['cnt'];
}
$stmt->close();

for ($i = 0; $i < $days; $i++) {
    $date = clone $startDate;
    $date->modify('+' . $i . ' days');
    $label = $date->format('Y-m-d');
    $currentTotal += $signups[$label] ?? 0;
    $labels[] = $label;
    $data[] = $currentTotal;
}

echo json_encode([
    'labels' => $labels,
    'datasets' => [[
        'label' => 'Buwana total users over the last 30 days',
        'data' => $data,
        'fill' => false,
        'borderColor' => '#36a2eb'
    ]]
]);
?>
