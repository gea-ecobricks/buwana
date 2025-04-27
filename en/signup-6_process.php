<?php
require_once '../earthenAuth_helper.php';
require_once("../buwanaconn_env.php");

// Get buwana_id from URL
$buwana_id = $_GET['id'] ?? null;

if (!$buwana_id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request.');
}

// Collect form data
$community_name   = $_POST['community_name'] ?? null;
$country_id       = $_POST['country_name'] ?? null; // now actually the country_id
$language_id      = $_POST['language_id'] ?? null;
$earthling_emoji  = $_POST['earthling_emoji'] ?? null;

// Lookup continent_code for selected country_id
$continent_code = null;

$sql_country = "SELECT continent_code FROM countries_tb WHERE country_id = ?";
$stmt_country = $buwana_conn->prepare($sql_country);

if ($stmt_country) {
    $stmt_country->bind_param('i', $country_id);
    $stmt_country->execute();
    $stmt_country->bind_result($continent_code);
    $stmt_country->fetch();
    $stmt_country->close();
} else {
    die('Failed to prepare country lookup: ' . $buwana_conn->error);
}

// Update users_tb
$sql_update = "UPDATE users_tb
    SET continent_code = ?,
        country_id = ?,
        community_id = (SELECT community_id FROM communities_tb WHERE com_name = ?),
        language_id = ?,
        earthling_emoji = ?
    WHERE buwana_id = ?";

$stmt_update = $buwana_conn->prepare($sql_update);

if ($stmt_update) {
    $stmt_update->bind_param('sisssi', $continent_code, $country_id, $community_name, $language_id, $earthling_emoji, $buwana_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    die('Failed to prepare update statement: ' . $buwana_conn->error);
}

// Redirect to login with first-time status
header('Location: login.php?status=firsttime&id=' . urlencode($buwana_id));
exit();
?>
