<?php
require_once '../gobrikconn_env.php'; // Include the database connection

header('Content-Type: application/json');

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Validate inputs
$status = $_POST['status'] ?? '';
$ecobrick_id = $_POST['ecobrick_id'] ?? '';

if (empty($status) || empty($ecobrick_id)) {
    echo json_encode(['success' => false, 'message' => 'Missing required inputs.']);
    exit();
}

// Update the ecobrick status in the database
try {
    $update_stmt = $gobrik_conn->prepare("UPDATE tb_ecobricks SET status = ? WHERE ecobrick_unique_id = ?");
    if (!$update_stmt) {
        throw new Exception("Failed to prepare the update statement: " . $gobrik_conn->error);
    }

    $update_stmt->bind_param("si", $status, $ecobrick_id);

    if ($update_stmt->execute()) {
        if ($update_stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows were updated. Please check the ecobrick ID.']);
        }
    } else {
        throw new Exception("Error executing update query: " . $update_stmt->error);
    }

    $update_stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while updating the status.']);
}

$gobrik_conn->close();
?>
