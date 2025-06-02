<?php
require_once '../buwanaconn_env.php';
header('Content-Type: application/json');

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : null;
$range   = $_GET['range'] ?? 'month';

$labels = [];
$data   = [];

switch ($range) {
    case '24h':
        $periods     = 24; // hours
        $increment   = 'hour';
        $groupSql    = "DATE_FORMAT(u.connected_at, '%Y-%m-%d %H:00:00')";
        $labelFormat = 'm-d H:00';
        $chartLabel  = 'App connections over the last 24 hours';
        break;
    case 'week':
        $periods     = 7;
        $increment   = 'day';
        $groupSql    = "DATE(u.connected_at)";
        $labelFormat = 'm-d';
        $chartLabel  = 'App connections over the last week';
        break;
    case 'year':
        $periods     = 12; // months
        $increment   = 'month';
        $groupSql    = "DATE_FORMAT(u.connected_at, '%Y-%m')";
        $labelFormat = 'Y-m';
        $chartLabel  = 'App connections over the last year';
        break;
    case 'month':
    default:
        $periods     = 30;
        $increment   = 'day';
        $groupSql    = "DATE(u.connected_at)";
        $labelFormat = 'm-d';
        $chartLabel  = 'App connections over the last 30 days';
        break;
}

$startDate   = new DateTime();
$startDate->modify('-' . ($periods - 1) . ' ' . $increment . 's');
$initialDate  = $startDate->format('Y-m-d H:i:s');

// Total connections before the range
if ($app_id) {
    $sql  = "SELECT COUNT(*) FROM user_app_connections_tb u JOIN apps_tb a ON u.client_id = a.client_id WHERE u.connected_at < ? AND a.app_id = ?";
    $stmt = $buwana_conn->prepare($sql);
    $stmt->bind_param('si', $initialDate, $app_id);
} else {
    $sql  = "SELECT COUNT(*) FROM user_app_connections_tb WHERE connected_at < ?";
    $stmt = $buwana_conn->prepare($sql);
    $stmt->bind_param('s', $initialDate);
}
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();
$currentTotal = (int) $total;

// New connections aggregated for the range
if ($app_id) {
    $sql = "SELECT $groupSql as dt, COUNT(*) as cnt
            FROM user_app_connections_tb u
            JOIN apps_tb a ON u.client_id = a.client_id
            WHERE u.connected_at >= ? AND a.app_id = ?
            GROUP BY dt ORDER BY dt";
    $stmt = $buwana_conn->prepare($sql);
    $stmt->bind_param('si', $initialDate, $app_id);
} else {
    $sql = "SELECT $groupSql as dt, COUNT(*) as cnt
            FROM user_app_connections_tb u
            WHERE u.connected_at >= ?
            GROUP BY dt ORDER BY dt";
    $stmt = $buwana_conn->prepare($sql);
    $stmt->bind_param('s', $initialDate);
}
$stmt->execute();
$result = $stmt->get_result();
$signups = [];
while ($row = $result->fetch_assoc()) {
    $signups[$row['dt']] = (int) $row['cnt'];
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
    $label        = $point->format($labelFormat);
    $currentTotal += $signups[$key] ?? 0;
    $labels[]     = $label;
    $data[]       = $currentTotal;
}

echo json_encode([
    'labels'   => $labels,
    'datasets' => [[
        'label'       => $chartLabel,
        'data'        => $data,
        'fill'        => false,
        'borderColor' => '#36a2eb'
    ]]
]);
?>
