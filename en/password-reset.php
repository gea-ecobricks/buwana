<?php
require_once '../earthenAuth_helper.php'; // Include the authentication helper functions

// Set page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.773';
$page = 'reset';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// Initialize user variables
$first_name = '';
$buwana_id = '';
$is_logged_in = isLoggedIn(); // Check if the user is logged in using the helper function

// Check if user is logged in and session active
if ($is_logged_in) {
    header('Location: dashboard.php');
    exit();
}


// Get the status, id (buwana_id), code, and key (credential_key) from URL
$status = isset($_GET['status']) ? filter_var($_GET['status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
$buwana_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : '';
$code = isset($_GET['code']) ? filter_var($_GET['code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
$credential_key = ''; // Initialize $credential_key as empty
$first_name = '';  // Initialize the first_name variable


include '../buwanaconn_env.php'; // This file provides the database server, user, dbname information to access the server


$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if ($token) {
    // Check if token is valid
    $stmt = $buwana_conn->prepare("SELECT email FROM users_tb WHERE password_reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if (!$email) {
        echo '<script>alert("Invalid token. Please try again."); window.location.href = "login.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("No token provided. Please try again."); window.location.href = "login.php";</script>';
    exit();
}

// Echo the HTML structure
echo '<!DOCTYPE html>
<html lang="' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . '">
<head>
<meta charset="UTF-8">
<title>Password Reset</title>
';

require_once ("../includes/reset-inc.php");

echo '


<!-- PAGE CONTENT -->
<div id="top-page-image" class="credentials-banner top-page-image" style="margin-top: 65px;"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">

        <div style="text-align:center;width:100%;margin:auto;">
            <h3 data-lang-id="001-reset-title">Let\'s Reset Your Password</h3>
            <h4 data-lang-id="002-reset-subtitle" style="margin-top:12px; margin-bottom:8px;">Enter your new password for your Buwana account.</h4>
        </div>

        <!-- Reset password form -->
        <form id="resetForm" method="post" action="../processes/process_reset.php">
            <input type="hidden" name="token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">
            <div class="form-item">
                <p data-lang-id="003-new-pass">New password:</p>
                <div class="password-wrapper" data-lang-id="004-password-field">
                    <input type="password" id="password" name="password" required placeholder="Your new password...">
                    <span toggle="#password" class="toggle-password" style="cursor: pointer;">🔒</span>
                </div>
                <p class="form-caption" data-lang-id="011-six-characters">Password must be at least 6 characters long.</p>
                <div id="password-error" class="form-field-error" style="display:none;margin-top:0px;">👉 New password is not long enough!</div>
            </div>



            <div class="form-item">
                <p data-lang-id="012-re-enter">Re-enter password to confirm:</p>
                <div data-lang-id="013-password-wrapper" class="password-wrapper">
                    <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Re-enter password...">
                    <span toggle="#confirmPassword" class="toggle-password" style="cursor: pointer;">🔒</span>
                </div>
                <div id="confirm-password-error" class="form-field-error" style="display:none;margin-top:5px;" data-lang-id="013-password-match">👉 Passwords do not match.</div>
            </div>

            <div style="text-align:center;">
                <input type="submit" style="text-align:center;margin-top:15px;width:30%; min-width: 175px;" id="submit-button" value="🔑 Reset Password" class="submit-button enabled">
            </div>
        </form>
    </div>
    <div style="text-align:center;width:100%;margin:auto;margin-top:34px;"><p style="font-size:medium;" data-lang-id="015-no-need">No need to reset your password?  <a href="login.php">Login</a></p></div>
</div>
</div>';

require_once ("../footer-2025.php");

echo '

<script>
document.getElementById("resetForm").addEventListener("submit", function(event) {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var isValid = true;

    if (password.length < 6) {
        document.getElementById("password-error").style.display = "block";
        isValid = false;
    } else {
        document.getElementById("password-error").style.display = "none";
    }

    if (password !== confirmPassword) {
        document.getElementById("confirm-password-error").style.display = "block";
        isValid = false;
    } else {
        document.getElementById("confirm-password-error").style.display = "none";
    }

    if (!isValid) {
        event.preventDefault();
    }
});



</script>

</body>
</html>';
?>
