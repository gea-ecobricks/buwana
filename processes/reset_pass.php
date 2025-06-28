<?php
require '../vendor/autoload.php'; // Include Composer's autoloader
require '../buwanaconn_env.php'; // Database connection information
require '../earthenAuth_helper.php'; // For session management
startSecureSession();

// Determine client_id from POST or session
$client_id = $_POST['client_id'] ?? ($_SESSION['client_id'] ?? '');
$client_id = $client_id ? filter_var($client_id, FILTER_SANITIZE_SPECIAL_CHARS) : '';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
                'en' => 'Reset your Buwana password',
                'fr' => 'Réinitialisez votre mot de passe Buwana',
                'es' => 'Restablece tu contraseña de Buwana',
                'de' => 'Setze dein Buwana-Passwort zurück',
                'ar' => 'إعادة تعيين كلمة مرور بوانا الخاصة بك',
                'zh' => '重置您的Buwana密码',
                'id' => 'Atur Ulang Kata Sandi Buwana Anda'
            ];

            $bodies = [
                'en' => "Hello $first_name,<br><br>
                    A password reset was requested at " . date('Y-m-d H:i:s') . " for your Buwana account. If you didn't request this, please disregard!<br><br>
                    To reset your password, please click the following link:<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Reset Password</a><br><br>
                    The Buwana Team",
                'fr' => "Bonjour $first_name,<br><br>
                    Une réinitialisation de mot de passe a été demandée le " . date('Y-m-d H:i:s') . " pour votre compte Buwana. Si vous n'êtes pas à l'origine de cette demande, ignorez ce message.<br><br>
                    Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Réinitialiser le mot de passe</a><br><br>
                    L'équipe Buwana",
                'es' => "Hola $first_name,<br><br>
                    Se solicitó un restablecimiento de contraseña el " . date('Y-m-d H:i:s') . " para tu cuenta de Buwana. Si no lo solicitaste, ignora este mensaje.<br><br>
                    Para restablecer tu contraseña, haz clic en el siguiente enlace:<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Restablecer contraseña</a><br><br>
                    El equipo Buwana",
                'de' => "Hallo $first_name,<br><br>
                    Am " . date('Y-m-d H:i:s') . " wurde eine Zurücksetzung des Passworts für dein Buwana-Konto angefordert. Wenn du dies nicht angefordert hast, ignoriere diese Nachricht.<br><br>
                    Um dein Passwort zurückzusetzen, klicke bitte auf folgenden Link:<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Passwort zurücksetzen</a><br><br>
                    Dein Buwana Team",
                'ar' => "Hello $first_name,<br><br>
                                            A password reset was requested at " . date('Y-m-d H:i:s') . " for your Buwana account. If you didn't request this, please disregard!<br><br>
                                            To reset your password, please click the following link:<br><br>
                                            <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Reset Password</a><br><br>
                                            The Buwana Team",
                'zh' => "您好 $first_name，<br><br>
                    在 " . date('Y-m-d H:i:s') . " 您的Buwana账户提出了重置密码请求。如果不是您本人操作，请忽略此邮件。<br><br>
                    要重置密码，请点击以下链接：<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>重置密码</a><br><br>
                    Buwana 团队",
                'id' => "Halo $first_name,<br><br>
                    Permintaan pengaturan ulang kata sandi dibuat pada " . date('Y-m-d H:i:s') . " untuk akun Buwana Anda. Jika Anda tidak memintanya, abaikan email ini.<br><br>
                    Untuk mengatur ulang kata sandi, silakan klik tautan berikut:<br><br>
                    <a href='https://buwana.ecobricks.org/{$lang}/password-reset.php?app={$client_id}&token={$password_reset_token}'>Atur Ulang Kata Sandi</a><br><br>
                    Tim Buwana"
            ];

            // Send email using Mailgun API
            $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']);
            $mailgunApiKey = getenv('MAILGUN_API_KEY');
$mailgunDomain = getenv('MAILGUN_DOMAIN') ?: 'mail.gobrik.com';

            try {
                $response = $client->post("{$mailgunDomain}/messages", [
                    'auth' => ['api', $mailgunApiKey],
                    'form_params' => [
                        'from' => 'Buwana system <no-reply@mail.gobrik.com>', // Use your Mailgun verified domain email
                        'to' => $email,
                        'subject' => $subjects[$lang] ?? $subjects['en'],
                        'html' => $bodies[$lang] ?? $bodies['en'],
                        'text' => strip_tags($bodies[$lang] ?? $bodies['en']), // Plain text fallback
                    ]
                ]);

                if ($response->getStatusCode() == 200) {
                    $confirmation_code = uniqid("email_", true);
                    error_log("Mailgun: Email sent successfully to $email. Confirmation Code: " . $confirmation_code);
                    header('Location: ../' . $lang . '/login.php?reset_sent_to=' . urlencode($email));
                    exit();
                } else {
                    error_log("Mailgun: Failed to send email to $email. Status: " . $response->getStatusCode());
                    echo '<script>alert("Failed to send email. Please try again later."); window.location.href = "../' . $lang . '/login.php";</script>';
                }

            } catch (RequestException $e) {
                error_log("Mailgun API Exception: " . $e->getMessage());
                echo '<script>alert("Message could not be sent... Please try again later."); window.location.href = "../' . $lang . '/login.php";</script>';
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
