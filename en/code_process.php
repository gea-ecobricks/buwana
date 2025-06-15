<?php
// Sends the activation code via MailGun API
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php'; // Load Composer dependencies, including Guzzle
require_once("../buwanaconn_env.php");

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Initialize variables
$response = array();
$credential_key = $_POST['credential_key'] ?? '';
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$email_addr = '';
$first_name = '';

// Validate input
if (empty($credential_key)) {
    $response['status'] = 'empty_fields';
    echo json_encode($response);
    exit();
}

// Utility function to generate a 5-character code
function generateCode() {
    return strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

// Function to send the login code email
function sendVerificationCode($email_addr, $login_code, $buwana_id, $first_name) {
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']);
    $mailgunApiKey = getenv('MAILGUN_API_KEY');
    $mailgunDomain = 'mail.gobrik.com';

    $loginUrl = "https://gobrik.com/en/login.php?id=" . urlencode($buwana_id) . "&code=" . urlencode($login_code);

    $subject = 'GoBrik Login Code';
    $html_body = "Hello " . htmlspecialchars($first_name) . ",<br><br>Your code to log in to your account is: <b>$login_code</b><br><br>" .
                 "Return to your browser and enter the code or click this link to log in directly:<br><br>" .
                 "<a href=\"$loginUrl\">$loginUrl</a><br><br>The GoBrik team";
    $text_body = "Hello $first_name,\n\nYour code to log in to your account is: $login_code\n\n" .
                 "Return to your browser and enter the code or use this link to log in directly:\n\n$loginUrl\n\nThe GoBrik team";

    try {
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

// PART: Check Buwana Database for the credential
$sql_credential = "SELECT buwana_id, email_addr, first_name, 2fa_issued_count FROM credentials_tb WHERE credential_key = ?";
$stmt_credential = $buwana_conn->prepare($sql_credential);
if ($stmt_credential) {
    $stmt_credential->bind_param('s', $credential_key);
    $stmt_credential->execute();
    $stmt_credential->store_result();

    if ($stmt_credential->num_rows === 1) {
        $stmt_credential->bind_result($buwana_id, $email_addr, $first_name, $issued_count);
        $stmt_credential->fetch();
        $stmt_credential->close();

        $temp_code = generateCode();
        $issued_datetime = date('Y-m-d H:i:s');
        $new_issued_count = $issued_count + 1;

        // Update credentials_tb with new 2FA code
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

                if (sendVerificationCode($email_addr, $temp_code, $buwana_id, $first_name)) {
                    $response['status'] = 'credfound';
                    $response['buwana_id'] = $buwana_id;
                    $response['2fa_code'] = $temp_code;
                    echo json_encode($response);
                    exit();
                } else {
                    file_put_contents('debug.log', "Failed to send email to: $email_addr\n", FILE_APPEND);
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

$buwana_conn->close();
?>
