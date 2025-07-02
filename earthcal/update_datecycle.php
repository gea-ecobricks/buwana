<?php
require_once '../earthenAuth_helper.php';
require_once '../buwanaconn_env.php';
require_once '../calconn_env.php'; // Include EarthCal database connection

header('Content-Type: application/json');

// CORS configuration
$allowed_origins = [
    'https://cal.earthen.io',
    'https://cycles.earthen.io',
    'https://ecobricks.org',
    'https://earthcal.app',
    'http://localhost',
    'file://'
];

$origin = isset($_SERVER['HTTP_ORIGIN']) ? rtrim($_SERVER['HTTP_ORIGIN'], '/') : '';
if (empty($origin)) {
    header('Access-Control-Allow-Origin: *');
} elseif (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'CORS error: Invalid origin']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate required fields, including unique_key.
$required_fields = [
    'unique_key', 'buwana_id', 'cal_id', 'cal_name', 'cal_color', 'title', 'date', 'time', 'time_zone',
    'day', 'month', 'year', 'frequency', 'last_edited', 'created_at'
];
foreach ($required_fields as $field) {
    if (!isset($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit();
    }
}

// Extract and sanitize inputs.
$buwana_id  = (int)$data['buwana_id'];
$cal_id     = (int)$data['cal_id'];
$cal_name   = $cal_conn->real_escape_string($data['cal_name']);
$cal_color  = $cal_conn->real_escape_string($data['cal_color']);
$title      = $cal_conn->real_escape_string($data['title']);
$date       = $cal_conn->real_escape_string($data['date']);  // e.g., "2025-02-02"
$time       = $cal_conn->real_escape_string($data['time']);
$time_zone  = $cal_conn->real_escape_string($data['time_zone']);
$day        = (int)$data['day'];
$month      = (int)$data['month'];
$year       = (int)$data['year'];
$frequency  = $cal_conn->real_escape_string($data['frequency']);
$last_edited = date('Y-m-d H:i:s', strtotime($data['last_edited']));
$created_at  = $cal_conn->real_escape_string($data['created_at']); // Human-readable date string.
$unique_key  = $cal_conn->real_escape_string($data['unique_key']);

// Optional fields.
$completed       = isset($data['completed']) ? $cal_conn->real_escape_string($data['completed']) : "0";
$pinned          = isset($data['pinned']) ? $cal_conn->real_escape_string($data['pinned']) : "0";
$public          = isset($data['public']) ? $cal_conn->real_escape_string($data['public']) : "0";
$comment         = isset($data['comment']) ? $cal_conn->real_escape_string($data['comment']) : "";
$comments        = isset($data['comments']) ? $cal_conn->real_escape_string($data['comments']) : "";
$datecycle_color = isset($data['datecycle_color']) ? $cal_conn->real_escape_string($data['datecycle_color']) : "#000";
$synced          = (int)$data['synced'];
$conflict        = isset($data['conflict']) ? $cal_conn->real_escape_string($data['conflict']) : "0";
$delete_it       = isset($data['delete_it']) ? $cal_conn->real_escape_string($data['delete_it']) : "0";

try {
    // Prepare the update query.
    $query = "
        UPDATE datecycles_tb
        SET buwana_id = ?, cal_id = ?, cal_name = ?, cal_color = ?, title = ?, date = ?, time = ?, time_zone = ?, day = ?, month = ?, year = ?, frequency = ?, last_edited = ?,
            created_at = ?, completed = ?, pinned = ?, public = ?, comment = ?, comments = ?, datecycle_color = ?, synced = ?, conflict = ?, delete_it = ?
        WHERE unique_key = ?
    ";

    $stmt = $cal_conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $cal_conn->error);
    }

    // The order of parameters is:
    // 1. buwana_id (i)
    // 2. cal_id (i)
    // 3. cal_name (s)
    // 4. cal_color (s)
    // 5. title (s)
    // 6. date (s)
    // 7. time (s)
    // 8. time_zone (s)
    // 9. day (i)
    // 10. month (i)
    // 11. year (i)
    // 12. frequency (s)
    // 13. last_edited (s)
    // 14. created_at (s)
    // 15. completed (s)
    // 16. pinned (s)
    // 17. public (s)
    // 18. comment (s)
    // 19. comments (s)
    // 20. datecycle_color (s)
    // 21. synced (i)
    // 22. conflict (s)
    // 23. delete_it (s)
    // 24. unique_key (s)
    //
    // Thus, the type string is:
    // "ii" + "ssssss" + "iii" + "s" + "s" + "s" + "s" + "s" + "s" + "s" + "s" + "i" + "s" + "s"
    // Which equates to:
    $type_string = "iissssssiiisssssssssisss";
    // Let's break it down:
    // "ii"               => parameters 1-2
    // "ssssss"           => parameters 3-8
    // "iii"              => parameters 9-11
    // "s"                => parameter 12
    // "s"                => parameter 13
    // "s"                => parameter 14
    // "s"                => parameter 15
    // "s"                => parameter 16
    // "s"                => parameter 17
    // "s"                => parameter 18
    // "s"                => parameter 19
    // "s"                => parameter 20
    // "i"                => parameter 21
    // "s"                => parameter 22
    // "s"                => parameter 23
    // "s"                => parameter 24
    // Total letters: 2 + 6 + 3 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 + 1 = 24

    $stmt->bind_param(
        $type_string,
        $buwana_id,
        $cal_id,
        $cal_name,
        $cal_color,
        $title,
        $date,
        $time,
        $time_zone,
        $day,
        $month,
        $year,
        $frequency,
        $last_edited,
        $created_at,
        $completed,
        $pinned,
        $public,
        $comment,
        $comments,
        $datecycle_color,
        $synced,
        $conflict,
        $delete_it,
        $unique_key
    );

    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No record updated.']);
    }
    $stmt->close();
} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>
