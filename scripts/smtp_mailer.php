<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include Composer's autoloader for PHPMailer

/**
 * Function to send an email using PHPMailer and SMTP.
 *
 * @param string $to      Recipient email address.
 * @param string $subject Email subject.
 * @param string $body    Email body (HTML format).
 * @param string $from    Sender email address.
 * @return array          Contains 'success' (bool) and 'debug_info' (string) keys.
 */
function smtp_mail($to, $subject, $body, $from = 'no-reply@gobrik.com') {
    $debugInfo = ''; // To store SMTP debug info

    try {
        $mail = new PHPMailer(true);

        // Enable verbose debug output
        $mail->SMTPDebug = 2; // Debug level: 2 = detailed server response
        $mail->Debugoutput = function ($str, $level) use (&$debugInfo) {
            $debugInfo .= "Level $level: $str\n";
        };

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.ecobricks.org'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME'); // SMTP username (use environment variable)
        $mail->Password = getenv('SMTP_PASSWORD'); // SMTP password (use environment variable)
        $mail->SMTPSecure = false; // Disable TLS/SSL if unnecessary
        $mail->Port = 26; // SMTP port

        // Recipients
        $mail->setFrom($from, 'GoBrik System');
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send the email
        if ($mail->send()) {
            error_log("Email sent successfully to $to with subject '$subject'.");
            return [
                'success' => true,
                'debug_info' => $debugInfo
            ];
        } else {
            error_log("Failed to send email to $to. Error: " . $mail->ErrorInfo);
            return [
                'success' => false,
                'debug_info' => $debugInfo . "\nError: " . $mail->ErrorInfo
            ];
        }
    } catch (Exception $e) {
        error_log("Exception: Could not send email to $to. Error: " . $e->getMessage());
        return [
            'success' => false,
            'debug_info' => $debugInfo . "\nException: " . $e->getMessage()
        ];
    }
}
?>
