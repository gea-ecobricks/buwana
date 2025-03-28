<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start(); // Start output buffering

$response = ['success' => false];

include '../buwanaconn_env.php'; // Buwana database connection
include '../gobrikconn_env.php'; // GoBrik database connection

function sendJsonError($error) {
    ob_end_clean(); // Clear any previous output
    echo json_encode(['success' => false, 'error' => $error]);
    exit();
}

//PART 1: PROCESS SPECIFIC USER
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $buwana_id = $_GET['id'] ?? null; // Get the buwana_id from the URL

    // Validate buwana_id
    if (empty($buwana_id) || !is_numeric($buwana_id)) {
        sendJsonError('invalid_buwana_id');
    }

    // Sanitize and validate inputs
    $credential_value = filter_var($_POST['credential_value'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password_hash'];

    if (empty($credential_value)) {
        sendJsonError('invalid_email');
    }

    if (empty($password) || strlen($password) < 6) {
        sendJsonError('invalid_password');
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // PART 2: Retrieve the Buwana first_name from the database
    $sql_get_first_name = "SELECT first_name FROM users_tb WHERE buwana_id = ?";
    $stmt_get_first_name = $buwana_conn->prepare($sql_get_first_name);
    if (!$stmt_get_first_name) {
        sendJsonError('db_error_first_name');
    }

    $stmt_get_first_name->bind_param("i", $buwana_id);
    $stmt_get_first_name->execute();
    $stmt_get_first_name->bind_result($first_name);
    $stmt_get_first_name->fetch();
    $stmt_get_first_name->close();

    if (empty($first_name)) {
        sendJsonError('missing_first_name');
    }

    // Check if the email already exists in the Buwana database
    $sql_check_email_buwana = "SELECT COUNT(*), buwana_id FROM users_tb WHERE email = ?";
    $stmt_check_email_buwana = $buwana_conn->prepare($sql_check_email_buwana);
    if (!$stmt_check_email_buwana) {
        sendJsonError('db_error_check_email');
    }

    $stmt_check_email_buwana->bind_param("s", $credential_value);
    $stmt_check_email_buwana->execute();
    $stmt_check_email_buwana->bind_result($email_count_buwana, $existing_buwana_id);
    $stmt_check_email_buwana->fetch();
    $stmt_check_email_buwana->close();

    if ($email_count_buwana > 0 && $existing_buwana_id != $buwana_id) {
        sendJsonError('duplicate_email');
    }

    // Check if the email already exists in the GoBrik database
    $sql_check_email_gobrik = "SELECT ecobricker_id FROM tb_ecobrickers WHERE email_addr = ?";
    $stmt_check_email_gobrik = $gobrik_conn->prepare($sql_check_email_gobrik);
    if (!$stmt_check_email_gobrik) {
        sendJsonError('db_error_check_email_gobrik');
    }

    $stmt_check_email_gobrik->bind_param("s", $credential_value);
    $stmt_check_email_gobrik->execute();
    $stmt_check_email_gobrik->bind_result($ecobricker_id);
    $stmt_check_email_gobrik->fetch();
    $stmt_check_email_gobrik->close();

    if ($ecobricker_id) {
        $response['error'] = 'duplicate_gobrik_email';
        $response['redirect'] = "activate.php?id=$ecobricker_id";
        echo json_encode($response);
        ob_end_clean();
        exit();
    }

    // PART 3: Update the Buwana user record. Add their credentials and add the connected app id (in this case GoBrik: 1)
$sql_update_user = "UPDATE users_tb SET email = ?, password_hash = ?, account_status = 'signup_process run. Email unverified', last_login = NOW() WHERE buwana_id = ?";
$stmt_update_user = $buwana_conn->prepare($sql_update_user);

if ($stmt_update_user) {
    $stmt_update_user->bind_param("ssi", $credential_value, $password_hash, $buwana_id);

    if ($stmt_update_user->execute()) {
        // Update connected_app_ids to include GoBrik (app_id: 1)
        $sql_update_connected_apps = "UPDATE users_tb
                                      SET connected_app_ids =
                                      CASE
                                        WHEN connected_app_ids IS NULL OR connected_app_ids = ''
                                        THEN '1'
                                        ELSE CONCAT(connected_app_ids, ',1')
                                      END
                                      WHERE buwana_id = ?";
        $stmt_update_connected_apps = $buwana_conn->prepare($sql_update_connected_apps);

        if ($stmt_update_connected_apps) {
            $stmt_update_connected_apps->bind_param("i", $buwana_id);
            $stmt_update_connected_apps->execute();
            $stmt_update_connected_apps->close();
        } else {
            sendJsonError('db_error_connected_apps_update');
        }

        // Now update the credentials_tb table for the user
        $sql_update_credentials = "UPDATE credentials_tb SET credential_key = ?, credential_type = 'e-mail' WHERE buwana_id = ?";
        $stmt_update_credentials = $buwana_conn->prepare($sql_update_credentials);
        if ($stmt_update_credentials) {
            $stmt_update_credentials->bind_param("si", $credential_value, $buwana_id);
            $stmt_update_credentials->execute();
            $stmt_update_credentials->close();
        } else {
            $response['error'] = 'db_error_credentials';
            echo json_encode($response);
            ob_end_clean();
            exit();
        }


          // PART 4.  Now create the Ecobricker account in GoBrik
$sql_create_ecobricker = "INSERT INTO tb_ecobrickers
(first_name, full_name, buwana_id, email_addr, date_registered, maker_id, buwana_activated, buwana_activation_dt, account_notes)
VALUES (?, ?, ?, ?, NOW(), ?, 1, NOW(), ?)";

$stmt_create_ecobricker = $gobrik_conn->prepare($sql_create_ecobricker);

if (!$stmt_create_ecobricker) {
    sendJsonError('db_error_create_ecobricker');
}

// ✅ Keep buwana_id intact
if ($buwana_id <= 0) {
    sendJsonError('invalid_buwana_id'); // Fail early if buwana_id is invalid
}

// ✅ Assign maker_id separately
$maker_id = ($buwana_id > 0) ? $buwana_id : NULL;

$full_name = $first_name ?: ''; // Ensure full_name has a value
$account_notes = "signup_process run. Email unverified";

$stmt_create_ecobricker->bind_param("ssisss", $first_name, $full_name, $buwana_id, $credential_value, $maker_id, $account_notes);

if ($stmt_create_ecobricker->execute()) {
    $response['success'] = true;
    $response['redirect'] = "confirm-email.php?id=" . $gobrik_conn->insert_id;
} else {
    sendJsonError('db_error_ecobricker');
}

$stmt_create_ecobricker->close(); // Closing the prepared statement


        } else {
            sendJsonError('db_error_user_update');
        }
        $stmt_update_user->close(); // Closing the update statement
    } else {
        sendJsonError('invalid_request');
    }
}

ob_end_clean(); // Clear any previous output

// Return the JSON response
echo json_encode($response);
exit();
?>
