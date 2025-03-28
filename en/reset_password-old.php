<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include Composer's autoloader
require '../buwanaconn_env.php'; // Database connection information

// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set language and validate the email input
$lang = isset($_POST['lang']) ? filter_var($_POST['lang'], FILTER_SANITIZE_STRING) : 'en';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';

if ($email) {
    try {
        // Check if email exists in the database
        $stmt = $buwana_conn->prepare("SELECT email, first_name FROM users_tb WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $buwana_conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($result_email, $first_name);
        $stmt->fetch();
        $stmt->close();

        if ($result_email) {
            // Generate a unique token
            $password_reset_token = bin2hex(random_bytes(16));
            $password_reset_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // Update the user's password reset token and expiry in the database
            $stmt = $buwana_conn->prepare("UPDATE users_tb SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $buwana_conn->error);
            }
            $stmt->bind_param("sss", $password_reset_token, $password_reset_expires, $email);
            $stmt->execute();
            $stmt->close();

            // Language-specific email content
            $subjects = [
                'en' => 'Reset your GoBrik password',
                'fr' => 'Réinitialisez votre mot de passe GoBrik',
                'es' => 'Restablezca su contraseña de GoBrik',
                'id' => 'Atur Ulang Kata Sandi GoBrik Anda'
            ];

            $bodies = [
                'en' => "Hello $first_name,<br><br>
                    A password reset was requested at " . date('Y-m-d H:i:s') . " on GoBrik.com for your Buwana account. If you didn't request this, please disregard!<br><br>
                    To reset your password, please click the following link:<br><br>
                    <a href='https://beta.gobrik.com/{$lang}/password-reset.php?token={$password_reset_token}'>Reset Password</a><br><br>
                    The GoBrik Team",
                // Additional language bodies as above...
            ];

            $mail = new PHPMailer(true);
            try {
                  // Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.ecobricks.org';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME'); // Use environment variable
        $mail->Password = getenv('SMTP_PASSWORD'); // Use environment variable
        $mail->SMTPSecure = false;
        $mail->Port = 26;

                // Recipients
                $mail->setFrom('no-reply@gobrik.com', 'GoBrik System');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subjects[$lang] ?? $subjects['en'];
                $mail->Body = $bodies[$lang] ?? $bodies['en'];

                // Attempt to send the email and log the result
                if ($mail->send()) {
                    $confirmation_code = uniqid("email_", true);
                    error_log("Email sent successfully to $email. Confirmation Code: " . $confirmation_code);
                    echo '<script>alert("An email with a link to reset your GoBrik Buwana password has been sent!"); window.location.href = "../' . $lang . '/login.php";</script>';
                } else {
                    error_log("Error: Failed to send email to $email. Mailer Error: " . $mail->ErrorInfo);
                    echo '<script>alert("Message could not be sent. Mailer Error: ' . htmlspecialchars($mail->ErrorInfo) . '"); window.location.href = "../' . $lang . '/login.php";</script>';
                }

            } catch (Exception $e) {
                error_log("PHPMailer Exception: Could not send email. Error: " . $e->getMessage());
                echo '<script>alert("Message could not be sent. Exception: ' . htmlspecialchars($e->getMessage()) . '"); window.location.href = "../' . $lang . '/login.php";</script>';
            }
        } else {
            header('Location: ../' . $lang . '/login.php?email_not_found&email=' . urlencode($email));
            exit();
        }
    } catch (Exception $e) {
        error_log("Database Exception: " . $e->getMessage());
        echo "<script>console.error('Error: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
} else {
    echo '<script>alert("Please enter a valid email address."); window.location.href = "../' . $lang . '/login.php";</script>';
}

// Close the database connection
$buwana_conn->close();
?>
