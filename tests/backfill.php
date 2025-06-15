<?php
require_once '../buwanaconn_env.php';
require_once '../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

$sql = "SELECT buwana_id FROM users_tb WHERE open_id IS NULL OR open_id = ''";
$result = $buwana_conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $uuid = Uuid::uuid4()->toString();
        $buwana_id = $row['buwana_id'];

        $stmt = $buwana_conn->prepare("UPDATE users_tb SET open_id = ? WHERE buwana_id = ?");
        $stmt->bind_param("si", $uuid, $buwana_id);
        $stmt->execute();
        $stmt->close();
    }
    echo "UUIDs successfully backfilled.\n";
} else {
    echo "No users needed UUIDs.\n";
}

$buwana_conn->close();
?>
