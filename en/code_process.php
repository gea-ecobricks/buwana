<?php
//Sends the actiavtion code via MailGun API
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php'; // Load Composer dependencies, including Guzzle
require_once("../gobrikconn_env.php");
require_once("../buwanaconn_env.php");

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Initialize variables
$response = array();
$credential_key = $_POST['credential_key'] ?? '';
$ecobricker_id = '';
$buwana_activated = '';
$first_name = '';
$email_addr = '';
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));

if (empty($credential_key)) {
    $response['status'] = 'empty_fields';
    echo json_encode($response);
    exit();
}

// PART 2: Functions

function generateCode() {
    return strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

function sendVerificationCode($email_addr, $login_code, $buwana_id, $first_name) {
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']); // EU-based endpoint
    $mailgunApiKey = getenv('MAILGUN_API_KEY'); // Use environment variable for Mailgun API key
    $mailgunDomain = 'mail.gobrik.com'; // Your Mailgun domain

    // Generate the login URL with the buwana_id and login_code as parameters
    $loginUrl = "https://gobrik.com/en/login.php?id=" . urlencode($buwana_id) . "&code=" . urlencode($login_code);

    $subject = 'GoBrik Login Code';
    $html_body = "Hello " . htmlspecialchars($first_name) . ",<br><br>Your code to log in to your account is: <b>$login_code</b><br><br>" .
                 "Return to your browser and enter the code or click this link to log in directly:<br><br>" .
                 "<a href=\"$loginUrl\">$loginUrl</a><br><br>The GoBrik team";
    $text_body = "Hello $first_name,\n\nYour code to log in to your account is: $login_code\n\n" .
                 "Return to your browser and enter the code or use this link to log in directly:\n\n$loginUrl\n\nThe GoBrik team";

    try {
        // Send email through Mailgun API
        $response = $client->post("{$mailgunDomain}/messages", [
            'auth' => ['api', $mailgunApiKey],
            'form_params' => [
                'from' => 'GoBrik Team <no-reply@mail.gobrik.com>',
                'to' => $email_addr,
                'subject' => $subject,
                'html' => $html_body,
                'text' => $text_body,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            error_log("Mailgun: Verification email sent successfully to $email_addr");
            return true;
        } else {
            error_log("Mailgun: Failed to send verification email. Status: " . $response->getStatusCode());
            return false;
        }

    } catch (RequestException $e) {
        error_log("Mailgun API Exception: " . $e->getMessage());
        return false;
    }
}

// PART 3: Check GoBrik to see if the user account is activated
$sql_check_email = "SELECT ecobricker_id, buwana_activated, email_addr, first_name FROM tb_ecobrickers WHERE email_addr = ?";
$stmt_check_email = $gobrik_conn->prepare($sql_check_email);
if ($stmt_check_email) {
    $stmt_check_email->bind_param('s', $credential_key);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows === 1) {
        $stmt_check_email->bind_result($ecobricker_id, $buwana_activated, $email_addr, $first_name);
        $stmt_check_email->fetch();

        if ($buwana_activated == '0') {
            $response['status'] = 'activation_required';
            $response['redirect'] = "activate.php?id=$ecobricker_id";
            echo json_encode($response);
            exit();
        }

        $stmt_check_email->close();
    } else {
        $stmt_check_email->close();
        $response['status'] = 'not_found';
        $response['message'] = 'Email not found';
        echo json_encode($response);
        exit();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Database query failed: ' . $gobrik_conn->error;
    echo json_encode($response);
    exit();
}

// PART 4: Check Buwana Database for the credential
$sql_credential = "SELECT buwana_id, 2fa_issued_count FROM credentials_tb WHERE credential_key = ?";
$stmt_credential = $buwana_conn->prepare($sql_credential);
if ($stmt_credential) {
    $stmt_credential->bind_param('s', $credential_key);
    $stmt_credential->execute();
    $stmt_credential->store_result();

    if ($stmt_credential->num_rows === 1) {
        $stmt_credential->bind_result($buwana_id, $issued_count);
        $stmt_credential->fetch();
        $stmt_credential->close();

        // Generate a new 5-character 2FA temporary code
        $temp_code = generateCode();
        $issued_datetime = date('Y-m-d H:i:s');
        $new_issued_count = $issued_count + 1;

        // Update the credentials_tb with new 2FA details
        $sql_update = "UPDATE credentials_tb SET
                       2fa_temp_code = ?,
                       2fa_code_issued = ?,
                       2fa_issued_count = ?
                       WHERE buwana_id = ?";
        $stmt_update = $buwana_conn->prepare($sql_update);
        if ($stmt_update) {
            $stmt_update->bind_param('ssii', $temp_code, $issued_datetime, $new_issued_count, $buwana_id);
            if ($stmt_update->execute()) {
                $stmt_update->close();

                // Send the verification code email with the first name
                if (sendVerificationCode($credential_key, $temp_code, $buwana_id, $first_name)) {
                    $response['status'] = 'credfound';
                    $response['buwana_id'] = $buwana_id;
                    $response['2fa_code'] = $temp_code; // Optionally return the code in the response
                    echo json_encode($response);
                    exit();
                } else {
                    file_put_contents('debug.log', "Failed to send email to: $credential_key\n", FILE_APPEND);
                    $response['status'] = 'email_error';
                    $response['message'] = 'Failed to send the email verification code.';
                    echo json_encode($response);
                    exit();
                }

            } else {
                file_put_contents('debug.log', "SQL Update Execution Error: " . $stmt_update->error . "\n", FILE_APPEND);
                $response['status'] = 'error';
                $response['message'] = 'Failed to update 2FA details: ' . $stmt_update->error;
                echo json_encode($response);
                exit();
            }
        } else {
            file_put_contents('debug.log', "SQL Update Preparation Error: " . $buwana_conn->error . "\n", FILE_APPEND);
            $response['status'] = 'error';
            $response['message'] = 'Failed to prepare SQL update: ' . $buwana_conn->error;
            echo json_encode($response);
            exit();
        }
    } else {
        $stmt_credential->close();
        $response['status'] = 'crednotfound';
        echo json_encode($response);
        exit();
    }
} else {
    file_put_contents('debug.log', "SQL Credential Prep Error: " . $buwana_conn->error . "\n", FILE_APPEND);
    $response['status'] = 'error';
    $response['message'] = 'Error preparing statement for credentials_tb: ' . $buwana_conn->error;
    echo json_encode($response);
    exit();
}

// Close the database connections
$buwana_conn->close();
$gobrik_conn->close();

?>
