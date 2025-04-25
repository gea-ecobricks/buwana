<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../buwanaconn_env.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buwana_id = $_GET['id'] ?? null;

    if (empty($buwana_id) || !is_numeric($buwana_id)) {
        die("⚠️ Invalid or missing Buwana ID.");
    }

    $user_location_full = $_POST['location_full'] ?? '';
    $user_lat = $_POST['latitude'] ?? null;
    $user_lon = $_POST['longitude'] ?? null;
    $location_watershed = $_POST['watershed_select'] ?? '';

    // Try to extract country name
    $location_parts = explode(',', $user_location_full);
    $selected_country = trim(end($location_parts));

    // Find country_id and continent_code
    $sql_country = "SELECT country_id, continent_code FROM countries_tb WHERE country_name = ?";
    $stmt_country = $buwana_conn->prepare($sql_country);
    $set_country_id = null;
    $set_continent_code = null;

    if ($stmt_country) {
        $stmt_country->bind_param('s', $selected_country);
        $stmt_country->execute();
        $stmt_country->bind_result($set_country_id, $set_continent_code);
        $stmt_country->fetch();
        $stmt_country->close();
    }

    // Update user record in users_tb
    $sql_update = "UPDATE users_tb
        SET continent_code = ?, country_id = ?, location_full = ?,
            location_lat = ?, location_long = ?, location_watershed = ?,
            account_status = 'signup-4_process run. Location set'
        WHERE buwana_id = ?";
    $stmt_update = $buwana_conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param(
            'sissdsi',
            $set_continent_code,
            $set_country_id,
            $user_location_full,
            $user_lat,
            $user_lon,
            $location_watershed,
            $buwana_id
        );
        $stmt_update->execute();
        $stmt_update->close();

        // ✅ Redirect to the next step
        header("Location: signup-5.php?id=" . urlencode($buwana_id));
        exit();
    } else {
        die("Error preparing statement: " . $buwana_conn->error);
    }
}
?>
