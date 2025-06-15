<?php
//Still being used?  Where is this called?
//Languages need added
require '../vendor/autoload.php'; // Path to Composer's autoloader

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function sendVerificationCode($first_name, $email_addr, $verification_code) {
    // Set up the Mailgun API client
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']); // EU-based endpoint
    $mailgunApiKey = getenv('MAILGUN_API_KEY'); // Retrieve Mailgun API key from environment
    $mailgunDomain = 'mail.gobrik.com'; // Your Mailgun domain

    // Email subject and body content
    $subject = 'Your GoBrik Verification Code';
    $html_body = "Hello $first_name!<br><br>If you're reading this, we're glad! The code to activate your account is:<br><br><b>$verification_code</b><br><br>Return back to your browser and enter the code.<br><br>The GoBrik team";
    $text_body = "Hello $first_name!\n\nIf you're reading this, we're glad! The code to activate your account is:\n\n$verification_code\n\nReturn back to your browser and enter the code.\n\nThe GoBrik team";

    try {
        // Send the email using Mailgun's API
        $response = $client->post("{$mailgunDomain}/messages", [
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
