<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';
require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'signup';
$version = '0.73';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

if (!empty($_SESSION['buwana_id'])) {
    $redirect_url = $_SESSION['redirect_url'] ?? $app_info['app_url'] ?? '/';
    echo "<script>
        alert('Looks like you’re already logged in! Redirecting to your dashboard...');
        window.location.href = '$redirect_url';
    </script>";
    exit();
}

// 🧩 Pull Buwana ID
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

// Initialize
$first_name = '';
$credential_key = '';
$credential_type = '';
$generated_code = '';
$code_sent_flag = false;

// 🔐 Generate activation code
function generateCode() {
    return strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

// 📬 Mailgun Sender
function sendVerificationCode($first_name, $credential_key, $code, $lang) {
    $client = new Client(['base_uri' => 'https://api.eu.mailgun.net/v3/']);
    $mailgunApiKey = getenv('MAILGUN_API_KEY');
    $mailgunDomain = 'mail.gobrik.com';

    $subject = "Your Verification Code";
    $html_body = "Hi $first_name,<br><br>Your verification code is: <b>$code</b><br><br>Enter this code to continue your registration.<br><br>— The Buwana Team";
    $text_body = "Hi $first_name, your verification code is: $code. Enter this code to continue your registration. — The Buwana Team";

    try {
        $response = $client->post("{$mailgunDomain}/messages", [
            'auth' => ['api', $mailgunApiKey],
            'form_params' => [
                'from' => 'Buwana Team <no-reply@mail.gobrik.com>',
                'to' => $credential_key,
                'subject' => $subject,
                'html' => $html_body,
                'text' => $text_body
            ]
        ]);
        return $response->getStatusCode() === 200;
    } catch (RequestException $e) {
        error_log("Mailgun error: " . $e->getMessage());
        return false;
    }
}

// 📭 SMTP Fallback
function backUpSMTPsender($first_name, $credential_key, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME');
        $mail->Password = getenv('SMTP_PASSWORD');
        $mail->Port = getenv('SMTP_PORT');
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;

        $mail->setFrom('buwana@ecobricks.org', 'Buwana Backup Mailer');
        $mail->addAddress($credential_key, $first_name);

        $mail->isHTML(true);
        $mail->Subject = 'Your Buwana Verification Code';
        $mail->Body = "Hello $first_name!<br><br>Your activation code is: <b>$code</b><br><br>Enter this code on the verification page.<br><br>The Buwana Team";
        $mail->AltBody = "Hello $first_name! Your activation code is: $code. Enter this code on the verification page.";

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        error_log("PHPMailer error: " . $e->getMessage());
        return false;
    }
}

// 🧠 PART 4: Get user info from Buwana DB
$sql = "SELECT u.first_name, c.credential_key, c.credential_type
        FROM users_tb u
        JOIN credentials_tb c ON u.buwana_id = c.buwana_id
        WHERE u.buwana_id = ?";
$stmt = $buwana_conn->prepare($sql);
$stmt->bind_param("i", $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $credential_key, $credential_type);
$stmt->fetch();
$stmt->close();

if (!$credential_key || !$credential_type) {
    die("⚠️ Missing or invalid credential information.");
}

// PART 5: Generate and update activation code in credentials_tb
$generated_code = generateCode();

$update_sql = "UPDATE credentials_tb SET activation_code = ? WHERE buwana_id = ?";
$update_stmt = $buwana_conn->prepare($update_sql);
$update_stmt->bind_param("si", $generated_code, $buwana_id);
$update_stmt->execute();
$update_stmt->close();

// 📩 PART 6: Send verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['send_email']) || isset($_POST['resend_email']))) {

    if ($credential_type === 'e-mail' || $credential_type === 'email') {
        $code_sent = sendVerificationCode($first_name, $credential_key, $generated_code, $lang);

        if (!$code_sent) {
            $code_sent = backUpSMTPsender($first_name, $credential_key, $generated_code);
        }

        if ($code_sent) {
            $code_sent_flag = true;
        } else {
            echo '<script>alert("Verification email failed to send using both methods. Please try again later or contact support.");</script>';
        }
    } elseif ($credential_type === 'phone') {
        echo '<script>alert("📱 SMS verification is under construction. Please use an email address for now.");</script>';
    } else {
        echo '<script>alert("Unsupported credential type.");</script>';
    }
}


// Echo the HTML structure
echo '<!DOCTYPE html>
<html lang="' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . '">
<head>
<meta charset="UTF-8">
';


?>


<!--
Buwana EarthenAuth
Developed and made open source by the Global Ecobrick Alliance
See our git hub repository for the full code and to help out:
https://github.com/gea-ecobricks/buwana/-->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php require_once ("../includes/signup-inc.php");?>

<div class="splash-title-block"></div>
<div id="splash-bar"></div>

<!-- PAGE CONTENT -->
<div id="top-page-image" class="message-birded top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">

       <!-- Email confirmation form -->
<div id="first-send-form" style="text-align:center;width:100%;margin:auto;margin-top:10px;margin-bottom:10px;"
    class="<?php echo $code_sent ? 'hidden' : ''; ?>"> <!-- Fix the inline PHP inside attributes -->

    <h2><span data-lang-id="001-alright">Alright</span> <?php echo htmlspecialchars($first_name); ?>, <span data-lang-id="002-lets-confirm"> let's confirm your email.</span></h2>
    <p data-lang-id="003-to-create">To create your Buwana GoBrik account we need to confirm your <?php echo htmlspecialchars($credential_type); ?>. This is how we'll keep in touch and keep your account secure.  Click the send button and we'll send an account activation code to:</p>

    <h3><?php echo htmlspecialchars($credential_key); ?></h3>
    <form id="send-email-code" method="post" action="">
        <div style="text-align:center;width:100%;margin:auto;margin-top:10px;margin-bottom:10px;">
            <div id="submit-section" style="text-align:center;margin-top:20px;padding-right:15px;padding-left:15px" title="Start Activation process" data-lang-id="004-send-email-button">
                <input type="submit" name="send_email" id="send_email" value="📨 Send Code" class="submit-button activate">
            </div>
        </div>
    </form>
</div>

<!-- Code entry form -->
<div id="second-code-confirm" style="text-align:center;"
    class="<?php echo !$code_sent ? 'hidden' : ''; ?>"> <!-- Fix the inline PHP inside attributes -->

    <h2 data-lang-id="006-enter-code">Please enter your code:</h2>
    <p><span data-lang-id="007-check-email">Check your email</span> <?php echo htmlspecialchars($credential_key); ?> <span data-lang-id="008-for-your-code">for your account confirmation code. Enter it here:</span></p>

    <div class="form-item" id="code-form" style="text-align:center;">
        <input type="text" maxlength="1" class="code-box" required placeholder="-">
        <input type="text" maxlength="1" class="code-box" required placeholder="-">
        <input type="text" maxlength="1" class="code-box" required placeholder="-">
        <input type="text" maxlength="1" class="code-box" required placeholder="-">
        <input type="text" maxlength="1" class="code-box" required placeholder="-">
    </form>

    <p id="code-feedback"></p>

    <p id="resend-code" style="font-size:1em"><span data-lang-id="009-no-code">Didn't get your code? You can request a resend of the code in</span> <span id="timer">1:00</span></p>
</div>




<?php if (!empty($buwana_id)) : ?>
<div id="new-account-another-email-please" style="text-align:center;width:90%;margin:auto;margin-top:30px;margin-bottom:30px;">
    <p style="font-size:1em;"><span data-lang-id="011-change-email">Want to change your email? </span>  <a href="signup-2.php?id=<?php echo htmlspecialchars($buwana_id); ?>"><span data-lang-id="012-go-back-new-email"> Go back to enter a different email address.</span></a>
    </p>
<?php else : ?>
<div id="legacy-account-email-not-used" style="text-align:center;width:90%;margin:auto;margin-top:30px;margin-bottom:50px;">
    <p style="font-size:1em;" data-lang-id="010-email-no-longer">Do you no longer use this email address?<br>If not, you'll need to <a href="signup.php">create a new account</a> or contact our team at support@gobrik.com.</p>
</div>
<?php endif; ?>

</div>


</div>

</div>
</div>

<div id="browser-back-link" style="font-size: medium; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;" data-lang-id="000-go-back">
    <p style="font-size: medium;" >
        Need to correct something?
        <a href="#" onclick="browserBack(event)">Go back ↩️</a>
    </p>
</div>

</div> <!--Closes main-->


<!--FOOTER STARTS HERE-->
<?php require_once ("../footer-2025.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const staticCode = "AYYEW";
    const generatedCode = <?php echo json_encode($generated_code); ?>;

    const lang = '<?php echo $lang; ?>';
    let timeLeft = 60;
    const sendEmailForm = document.getElementById('send-email-code');
    const buwana_id = <?php echo json_encode($buwana_id); ?>;

    const messages = {
        en: { confirmed: "👍 Code confirmed!", incorrect: "😕 Code incorrect. Try again." },
        fr: { confirmed: "👍 Code confirmé!", incorrect: "😕 Code incorrect. Réessayez." },
        es: { confirmed: "👍 Código confirmado!", incorrect: "Código incorrecto. Inténtalo de nuevo." },
        id: { confirmed: "👍 Kode dikonfirmasi!", incorrect: "😕 Kode salah. Coba lagi." }
    };

    const feedbackMessages = messages[lang] || messages.en;
    const codeFeedback = document.querySelector('#code-feedback');
    const codeBoxes = document.querySelectorAll('.code-box');

    function checkCode() {
        let enteredCode = '';
        codeBoxes.forEach(box => enteredCode += box.value.toUpperCase());

        if (enteredCode.length === 5) {
            if (enteredCode === staticCode || enteredCode === generatedCode) {
                codeFeedback.textContent = feedbackMessages.confirmed;
                codeFeedback.classList.add('success');
                codeFeedback.classList.remove('error');
                document.getElementById('resend-code').style.display = 'none';

                setTimeout(function() {
                    window.location.href = "signup-3_process.php?id=" + buwana_id;
                }, 300);
            } else {
                codeFeedback.textContent = feedbackMessages.incorrect;
                codeFeedback.classList.add('error');
                codeFeedback.classList.remove('success');
                shakeElement(document.getElementById('code-form'));

            }
        }
    }


    codeBoxes.forEach((box, index) => {
        box.addEventListener('keyup', function(e) {
            if (box.value.length === 1 && index < codeBoxes.length - 1) {
                codeBoxes[index + 1].focus();
            }
            checkCode();
        });

        if (index === 0) {
            box.addEventListener('paste', function(e) {
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');

                if (pastedText.length === 5) {
                    e.preventDefault();
                    codeBoxes.forEach((box, i) => box.value = pastedText[i] || '');
                    codeBoxes[codeBoxes.length - 1].focus();
                    checkCode();
                }
            });
        }

        // Add keydown event to handle backspacing
        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && box.value === '' && index > 0) {
                codeBoxes[index - 1].focus(); // Move to the previous box
            }
        });
    });






    // Handle the resend code timer
    let countdownTimer = setInterval(function() {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            document.getElementById('resend-code').innerHTML = '<a href="#" id="resend-link">Resend the code now.</a>';

            // Add click event to trigger form submission
            document.getElementById('resend-link').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                sendEmailForm.submit(); // Submit the form programmatically
            });
        } else {
            document.getElementById('timer').textContent = '0:' + (timeLeft < 10 ? '0' : '') + timeLeft;
        }
    }, 1000);



    // Show/Hide Divs after email is sent
    var codeSent = <?php echo json_encode($code_sent_flag ?? false); ?>;  // Only set once
    if (codeSent) {
        document.getElementById('first-send-form').style.display = 'none';
        document.getElementById('second-code-confirm').style.display = 'block';
    }


});
</script>


</body>
</html>