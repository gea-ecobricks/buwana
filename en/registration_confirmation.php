<?php
require_once '../earthenAuth_helper.php'; // Include authentication helper functions
require '../vendor/autoload.php'; // Composer autoload for Mailgun API

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

ob_start(); // ✅ Prevent any output before headers

/* ✅ Ensure User is Logged In */
if (!isLoggedIn()) {
    header("Location: login.php?redirect=register.php");
    exit();
}

// Include database connection
require_once '../gobrikconn_env.php';

/* ✅ Get `training_id` and `ecobricker_id` from URL */
$training_id = isset($_GET['training_id']) ? intval($_GET['training_id']) : 0;
$ecobricker_id = isset($_GET['ecobricker_id']) ? intval($_GET['ecobricker_id']) : 0;

/* ✅ Validate Inputs */
if ($training_id <= 0 || $ecobricker_id <= 0) {
    header("Location: register.php?error=invalid");
    exit();
}

/* ✅ Fetch User Details from `tb_ecobrickers` */
$sql_user = "SELECT first_name, email_addr FROM tb_ecobrickers WHERE ecobricker_id = ?";
$stmt_user = $gobrik_conn->prepare($sql_user);
$stmt_user->bind_param("i", $ecobricker_id);
$stmt_user->execute();
$stmt_user->bind_result($first_name, $email_addr);
$stmt_user->fetch();
$stmt_user->close();

/* ✅ Fetch Training Details from `tb_trainings` */
$sql_training = "SELECT training_title, training_date, zoom_link, training_type, feature_photo1_tmb, training_agenda, agenda_url, lead_trainer, trainer_contact_email, zoom_link_full
                 FROM tb_trainings WHERE training_id = ?";
$stmt_training = $gobrik_conn->prepare($sql_training);
$stmt_training->bind_param("i", $training_id);
$stmt_training->execute();
$stmt_training->bind_result($training_title, $training_date, $zoom_link, $training_type, $feature_photo1_tmb, $training_agenda, $agenda_url, $lead_trainer, $trainer_contact_email, $zoom_link_full);
$stmt_training->fetch();
$stmt_training->close();

// ✅ Ensure `$zoom_link_full` is defined (Fix Undefined Variable Warning)
if (!isset($zoom_link_full)) {
    $zoom_link_full = "No additional Zoom details available."; // Provide a fallback value
}

/* ✅ Check if the User is Already Registered */
$sql_check = "SELECT id FROM tb_training_trainees WHERE training_id = ? AND ecobricker_id = ?";
$stmt_check = $gobrik_conn->prepare($sql_check);
$stmt_check->bind_param("ii", $training_id, $ecobricker_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $stmt_check->close();
    $gobrik_conn->close();
    header("Location: register.php?training_id=$training_id&registered=1");
    exit();
}
$stmt_check->close();

/* ✅ Register the User */
$sql_insert = "INSERT INTO tb_training_trainees (training_id, ecobricker_id, rsvp_status) VALUES (?, ?, 'confirmed')";
$stmt_insert = $gobrik_conn->prepare($sql_insert);
$stmt_insert->bind_param("ii", $training_id, $ecobricker_id);

if ($stmt_insert->execute()) {
    $stmt_insert->close();

    // ✅ Send Confirmation Email
    sendTrainingConfirmationEmail($first_name, $email_addr, $training_title, $training_date, $zoom_link, $training_type, $feature_photo1_tmb, $agenda_url, $lead_trainer, $trainer_contact_email, $zoom_link_full);

    // ✅ Redirect User Back to the Registration Page with Success Message
    header("Location: register.php?training_id=$training_id&registered=1");
    exit();
}

// Registration failed, redirect with error message
header("Location: register.php?training_id=$training_id&error=failed");
exit();

$gobrik_conn->close();

/* ✅ Function to Send Training Registration Confirmation Email */
function sendTrainingConfirmationEmail($first_name, $email_addr, $training_title, $training_date, $zoom_link, $training_type, $feature_photo1_tmb, $agenda_url, $lead_trainer, $trainer_contact_email, $zoom_link_full) {
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']); // EU Mailgun API
    $mailgunApiKey = getenv('MAILGUN_API_KEY'); // Get API key from environment
    $mailgunDomain = 'mail.gobrik.com'; // Verified Mailgun domain

    // ✅ Email Subject
    $subject = "Your registration to $training_title is confirmed!";

    // ✅ Email Body (HTML)
    $html_body = "
        <div style='text-align: center; font-family: Arial, sans-serif;'>
            <img src='$feature_photo1_tmb' style='max-width: 100%; border-radius: 8px; margin-bottom: 15px;'>
            <h2>Hi there, $first_name!</h2>
            <p>Alright! 🎉 You're confirmed for our <strong>$training_type</strong> on <strong>$training_date</strong>.</p>
            <p>You can join <strong>$training_title</strong> on Zoom using the following link:</p>
            <p style='font-size: 1.2em;'><a href='$zoom_link' target='_blank' style='color: #0073e6; font-weight: bold;'>🔗 Join Zoom Meeting</a></p>
            <p>The event will open 15 minutes beforehand for a meet & greet.</p>
            <p>You can view the full <strong>$training_type</strong> agenda here:</p>
            <p><a href='$agenda_url' target='_blank' style='color: #0073e6; font-weight: bold;'>📄 View Training Agenda</a></p>
            <br>
            <p>Thank you, and see you then!</p>
            <br><br>
            <p><strong>$lead_trainer</strong></p>
            <p>Contact: <a href='mailto:$trainer_contact_email'>$trainer_contact_email</a></p>
            <br><br><br>
            <hr>
            <p style='font-size:0.9em;'>$zoom_link_full</p>
        </div>
    ";

    // ✅ Plain Text Fallback
    $text_body = "Hi $first_name,\n\nYou're confirmed for our $training_type on $training_date.\n\nJoin $training_title on Zoom: $zoom_link\n\nThe event opens 15 minutes beforehand for a meet & greet.\n\nView the full agenda: $agenda_url\n\nThank you, and see you then!\n\n$lead_trainer\nContact: $trainer_contact_email";

    try {
        $response = $client->post("$mailgunDomain/messages", [
            'auth' => ['api', $mailgunApiKey],
            'form_params' => [
                'from' => 'GoBrik Team <no-reply@mail.gobrik.com>',
                'to' => $email_addr,
                'subject' => $subject,
                'html' => $html_body,
                'text' => $text_body,
                'h:Reply-To' => $trainer_contact_email,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            error_log("Mailgun: Training confirmation email sent successfully to $email_addr");
            return true;
        } else {
            error_log("Mailgun: Failed to send confirmation email.");
            return false;
        }

    } catch (RequestException $e) {
        error_log("Mailgun API Exception: " . $e->getMessage());
        return false;
    }
}
?>
