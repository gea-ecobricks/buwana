<?php
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

$range = $_GET['range'] ?? 'month';
$labels = [];
$data = [];

switch ($range) {
    case '24h':
        $periods = 24; // hours
        $increment = 'hour';
        $groupSql = "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";
        $labelFormat = 'm-d H:00';
        $chartLabel = 'Buwana total users over the last 24 hours';
        break;
    case 'week':
        $periods = 7;
        $increment = 'day';
        $groupSql = "DATE(created_at)";
        $labelFormat = 'm-d';
        $chartLabel = 'Buwana total users over the last week';
        break;
    case 'year':
        $periods = 12; // months
        $increment = 'month';
        $groupSql = "DATE_FORMAT(created_at, '%Y-%m')";
        $labelFormat = 'Y-m';
        $chartLabel = 'Buwana total users over the last year';
        break;
    case 'month':
    default:
        $periods = 30;
        $increment = 'day';
        $groupSql = "DATE(created_at)";
        $labelFormat = 'm-d';
        $chartLabel = 'Buwana total users over the last 30 days';
        break;
}

$startDate = new DateTime();
$startDate->modify('-' . ($periods - 1) . ' ' . $increment . 's');
$initialDate = $startDate->format('Y-m-d H:i:s');

// Total users before the range
$stmt = $buwana_conn->prepare("SELECT COUNT(*) FROM users_tb WHERE created_at < ?");
$stmt->bind_param('s', $initialDate);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();
$currentTotal = (int)$total;

// New signups aggregated for the range
$stmt = $buwana_conn->prepare("SELECT $groupSql as dt, COUNT(*) as cnt FROM users_tb WHERE created_at >= ? GROUP BY dt ORDER BY dt");
$stmt->bind_param('s', $initialDate);
$stmt->execute();
$result = $stmt->get_result();
$signups = [];
while ($row = $result->fetch_assoc()) {
    $signups[$row['dt']] = (int)$row['cnt'];
}
$stmt->close();

for ($i = 0; $i < $periods; $i++) {
    $point = clone $startDate;
    $point->modify('+' . $i . ' ' . $increment);
    if ($increment === 'month') {
        $key = $point->format('Y-m');
    } elseif ($increment === 'hour') {
        $key = $point->format('Y-m-d H:00:00');
    } else {
        $key = $point->format('Y-m-d');
    }
    $label = $point->format($labelFormat);
    $currentTotal += $signups[$key] ?? 0;
    $labels[] = $label;
    $data[] = $currentTotal;
}

echo json_encode([
    'labels' => $labels,
    'datasets' => [[
        'label' => $chartLabel,
        'data' => $data,
        'fill' => false,
        'borderColor' => '#36a2eb'
    ]]
]);
?>
