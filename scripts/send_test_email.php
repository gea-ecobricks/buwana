<?php
require_once '../earthenAuth_helper.php';
require_once '../gobrikconn_env.php';

require '../vendor/autoload.php'; // Path to Composer's autoloader
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

header('Content-Type: application/json');
$response = [];

try {
    // Fetch the next ecobricker to send an email
    $sql_fetch_ecobricker = "
        SELECT ecobricker_id, first_name, date_registered, email_addr
        FROM tb_ecobrickers
        WHERE test_email_status = 'unsent'
          AND buwana_activated = 1
        ORDER BY ecobricker_id ASC
        LIMIT 1";
    $stmt_fetch_ecobricker = $gobrik_conn->prepare($sql_fetch_ecobricker);
    if (!$stmt_fetch_ecobricker) {
        throw new Exception('Error preparing fetch statement: ' . $gobrik_conn->error);
    }
    $stmt_fetch_ecobricker->execute();
    $stmt_fetch_ecobricker->bind_result($ecobricker_id, $first_name, $date_registered, $email_addr);
    $stmt_fetch_ecobricker->fetch();
    $stmt_fetch_ecobricker->close();

    if (empty($ecobricker_id)) {
        throw new Exception('No more emails to send. All eligible ecobrickers have been notified.');
    }

    // Set up the Mailgun API client
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']); // EU-based endpoint
    $mailgunApiKey = getenv('MAILGUN_API_KEY'); // Retrieve Mailgun API key from environment
    $mailgunDomain = 'mail.gobrik.com'; // Your Mailgun domain

    // Email subject and body content
    $subject = "New Year... new GoBrik! Fully regenerated to be corporate code-free.";
    $html_body = "
        <p>Happy New Year $first_name!</p>
        <p>In celebration of 2025, we're excited to launch the new GoBrik 3.0.</p>
        <p>While on $date_registered, you signed up for GoBrik, today 1/1/2025 we invite you to regenerate your account on our fully revamped system to preserve ownership of all your logged ecobricks, brikcoin, and validation credit balances.</p>
        <p><a href='https://gobrik.com'>Please login to activate your account</a></p>
        <p>Together we can keep plastic out of industry and out of the biosphere.</p>
        <p>Russell and the GoBrik, GEA team.</p>";
    $text_body = "
        Happy New Year $first_name!

        In celebration of 2025, we're excited to launch the new GoBrik 3.0.

        While on $date_registered, you signed up for GoBrik, today 1/1/2025 we invite you to regenerate your account on our fully revamped system to preserve ownership of all your logged ecobricks, brikcoin, and validation credit balances.

        Please login to activate your account: https://gobrik.com

        Together we can keep plastic out of industry and out of the biosphere.

        Russell and the GoBrik, GEA team.";

    try {
        // Send the email using Mailgun's API
        $response = $client->post("$mailgunDomain/messages", [
            'auth' => ['api', $mailgunApiKey],
            'form_params' => [
                'from' => 'GoBrik Team <no-reply@mail.gobrik.com>', // Verified domain email
                'to' => $email_addr,
                'subject' => $subject,
                'html' => $html_body,
                'text' => $text_body, // Plain text fallback
            ]
        ]);

        // Check response status
        if ($response->getStatusCode() !== 200) {
            throw new Exception("Mailgun: Failed to send email. Status: " . $response->getStatusCode());
        }

        // Update test_email_status in the database
        $sql_update_status = "UPDATE tb_ecobrickers SET test_email_status = 'sent' WHERE ecobricker_id = ?";
        $stmt_update_status = $gobrik_conn->prepare($sql_update_status);
        if (!$stmt_update_status) {
            throw new Exception('Error preparing update statement: ' . $gobrik_conn->error);
        }
        $stmt_update_status->bind_param('i', $ecobricker_id);
        $stmt_update_status->execute();
        $stmt_update_status->close();

        $response = [
            'success' => true,
            'message' => "Email sent successfully to $email_addr."
        ];
    } catch (RequestException $e) {
        throw new Exception("Mailgun API Exception: " . $e->getMessage());
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
    ];
}

echo json_encode($response);
exit();

?>
