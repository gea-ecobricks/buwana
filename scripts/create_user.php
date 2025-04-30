<?php
// create_user.php

require_once '../buwanaconn_env.php'; // Required to update buwana DB records

/**
 * Main function to create user in client app DB and update Buwana DB accordingly.
 */
function createUserInClientApp($buwana_id, $userData, $app_name, $client_conn, $buwana_conn, $client_id) {
    // 🕒 Set the current timestamp
    $created_at = date('Y-m-d H:i:s');

    // 🔐 Safety check: Ensure client connection is valid
    if (!isset($client_conn)) {
        return ['success' => false, 'error' => 'Client DB connection not established'];
    }

    // ============================================
    // PART 1: Insert user into client users_tb
    // ============================================
    $insert_sql = "INSERT INTO users_tb
        (buwana_id, username, first_name, last_name, full_name, email, created_at, terms_of_service, notes, profile_pic, country_id, language_id, watershed_id, continent_code, location_full, location_watershed, location_lat, location_long, community_id, earthling_emoji)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $client_conn->prepare($insert_sql);
    if (!$stmt) {
        error_log('❌ Client DB Prepare Error: ' . $client_conn->error);
        updateAppConnectionStatus($buwana_conn, $buwana_id, $client_id, 'failed');
        return ['success' => false, 'error' => 'Client DB prepare failed'];
    }

    $stmt->bind_param(
        'issssssisisissssddis',
        $buwana_id,
        $userData['username'],
        $userData['first_name'],
        $userData['last_name'],
        $userData['full_name'],
        $userData['email'],
        $created_at,
        $userData['terms_of_service'],
        $userData['notes'],
        $userData['profile_pic'],
        $userData['country_id'],
        $userData['language_id'],
        $userData['watershed_id'],
        $userData['continent_code'],
        $userData['location_full'],
        $userData['location_watershed'],
        $userData['location_lat'],
        $userData['location_long'],
        $userData['community_id'],
        $userData['earthling_emoji']
    );

    if ($stmt->execute()) {
        $stmt->close();

        // ============================================
        // PART 2: Update Buwana DB records
        // ============================================
        updateAppConnectionStatus($buwana_conn, $buwana_id, $client_id, 'registered', $created_at);
        updateBuwanaUserNotes($buwana_conn, $buwana_id, $app_name, $created_at);

        return ['success' => true];
    } else {
        error_log('❌ Client DB Insert Error: ' . $stmt->error);
        $stmt->close();
        updateAppConnectionStatus($buwana_conn, $buwana_id, $client_id, 'failed');
        return ['success' => false, 'error' => 'Client DB insert failed'];
    }
}


/**
 * Updates user_app_connections_tb with status and timestamp.
 */
function updateAppConnectionStatus($buwana_conn, $buwana_id, $client_id, $status, $connected_at = null) {
    $connected_at = $connected_at ?? date('Y-m-d H:i:s');

    $update_sql = "UPDATE user_app_connections_tb
                   SET status = ?, connected_at = ?
                   WHERE buwana_id = ? AND client_id = ?";
    $stmt = $buwana_conn->prepare($update_sql);

    if ($stmt) {
        $stmt->bind_param('ssii', $status, $connected_at, $buwana_id, $client_id);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log('❌ Failed to update app connection status: ' . $buwana_conn->error);
    }
}


/**
 * Appends a registration note to the user's Buwana users_tb record.
 */
function updateBuwanaUserNotes($buwana_conn, $buwana_id, $app_name, $created_at) {
    $note_text = "First registered on $app_name at $created_at.";

    $update_sql = "UPDATE users_tb
                   SET notes = CONCAT(COALESCE(notes, ''), ' ', ?)
                   WHERE buwana_id = ?";
    $stmt = $buwana_conn->prepare($update_sql);

    if ($stmt) {
        $stmt->bind_param('si', $note_text, $buwana_id);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log('❌ Failed to update buwana user notes: ' . $buwana_conn->error);
    }
}
?>
