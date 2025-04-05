<?php
include '../gobrik_env.php'; // Include your GoBrik database credentials

$query = isset($_GET['query']) ? $_GET['query'] : '';

$conn2 = new mysqli($servername, $username, $password, $dbname);

if ($conn2->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $conn2->connect_error]);
    exit();
}

$sql = "SELECT ecobrick_thumb_photo_url, ecobrick_full_photo_url, weight_g, location_full, ecobricker_maker, serial_no
        FROM tb_ecobricks
        WHERE LOWER(serial_no) LIKE ? OR LOWER(location_full) LIKE ? OR LOWER(ecobricker_maker) LIKE ?
        ORDER BY date_logged_ts DESC
        LIMIT 20";

$stmt = $conn2->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn2->error]);
    exit();
}

$search_param = '%' . $query . '%';
$stmt->bind_param("sss", $search_param, $search_param, $search_param);

$stmt->execute();

// Bind results to variables
$stmt->bind_result($ecobrick_thumb_photo_url, $ecobrick_full_photo_url, $weight_g, $location_full, $ecobricker_maker, $serial_no);

$ecobricks = [];
while ($stmt->fetch()) {
    $ecobricks[] = [
        'ecobrick_thumb_photo_url' => $ecobrick_thumb_photo_url,
        'ecobrick_full_photo_url' => 'https://ecobricks.org' . $ecobrick_full_photo_url,
        'weight_g' => $weight_g,
        'location_full' => $location_full,
        'ecobricker_maker' => $ecobricker_maker,
        'serial_no' => $serial_no
    ];
}

$stmt->close();
$conn2->close();

header('Content-Type: application/json');
echo json_encode($ecobricks);
?>
