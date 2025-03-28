<?php
require_once '../earthenAuth_helper.php'; // Include the authentication helper functions

// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.42';
$page = 'signup';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

$is_logged_in = false; // Ensure not logged in for this page

// Check if the user is logged in
if (isLoggedIn()) {
    echo "<script>
        alert('Looks like you already have an account and are logged in! Let\'s take you to your dashboard.');
        window.location.href = 'dashboard.php';
    </script>";
    exit();
}

$response = ['success' => false];
$buwana_id = $_GET['id'] ?? null;

// Initialize user variables
$credential_type = '';
$credential_key = '';
$first_name = '';
$account_status = '';
$country_icon = '';

// Include database connection
include '../buwanaconn_env.php';

// Look up user information if buwana_id is provided
if ($buwana_id) {
    $sql_lookup_credential = "SELECT credential_type, credential_key FROM credentials_tb WHERE buwana_id = ?";
    $stmt_lookup_credential = $buwana_conn->prepare($sql_lookup_credential);
    if ($stmt_lookup_credential) {
        $stmt_lookup_credential->bind_param("i", $buwana_id);
        $stmt_lookup_credential->execute();
        $stmt_lookup_credential->bind_result($credential_type, $credential_key);
        $stmt_lookup_credential->fetch();
        $stmt_lookup_credential->close();
    } else {
        $response['error'] = 'db_error';
    }

    $sql_lookup_user = "SELECT first_name, account_status FROM users_tb WHERE buwana_id = ?";
    $stmt_lookup_user = $buwana_conn->prepare($sql_lookup_user);
    if ($stmt_lookup_user) {
        $stmt_lookup_user->bind_param("i", $buwana_id);
        $stmt_lookup_user->execute();
        $stmt_lookup_user->bind_result($first_name, $account_status);
        $stmt_lookup_user->fetch();
        $stmt_lookup_user->close();
    } else {
        $response['error'] = 'db_error';
    }

    $credential_type = htmlspecialchars($credential_type);
    $first_name = htmlspecialchars($first_name);

    if ($account_status !== 'name set only') {
        $response['error'] = 'account_status';
    }
}
?>


<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">
<title>Step 2 - Sign up | GoBrik</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--
GoBrik.com site version 3.0
Developed and made open source by the Global Ecobrick Alliance
See our git hub repository for the full code and to help out:
https://github.com/gea-ecobricks/gobrik-3.0/tree/main/en-->

<?php require_once ("../includes/signup-inc.php");?>


<div class="splash-title-block"></div>
<div id="splash-bar"></div>

<!-- PAGE CONTENT -->
   <div id="top-page-image" class="credentials-banner top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">

            <div style="text-align:center;width:100%;margin:auto;">
                <h2 data-lang-id="001-setup-access-heading">Setup Your Access</h2>
                <p>Ok <?php echo $first_name; ?>, <span data-lang-id="002-setup-access-heading-a">let's use your </span> <?php echo $credential_type; ?> <span data-lang-id="003-setup-access-heading-b">as your means of registration and the way we contact you.</span></p>
            </div>


            <!--SIGNUP FORM-->
            <form id="password-confirm-form" method="post" action="signup_process.php?id=<?php echo htmlspecialchars($buwana_id); ?>">
                <div class="form-item" id="credential-section">

                    <!-- <div class="form-item" id="last-name" class="user_lastname" style="display:none!important;">
                    <label for="last_name" data-lang-id="011b-last-name">Now what is your last name?</label><br>
                    <input type="text" id="human_check" class="required" placeholder="Your last name...">
                    <p class="form-caption" data-lang-id="011b-required" style="color:red">*This field is required.</p>
                </div>-->

                    <label for="credential_value"><span data-lang-id="004-your">Your</span> <?php echo $credential_type; ?><span data-lang-id="004b-please"> please:</span></label><br>
                    <div id="duplicate-email-error" class="form-field-error" style="margin-top:10px;margin-bottom:-13px;" data-lang-id="010-duplicate-email">🚧 Whoops! Looks like that e-mail address is already being used by a Buwana Account. Please choose another.</div>
                    <div id="duplicate-gobrik-email" class="form-warning" style="margin-top:10px;margin-bottom:-13px;" ><span data-lang-id="010-gobrik-duplicate">🌏 It looks like this email is already being used with a legacy GoBrik account. Please <a href="login.php" class="underline-link">login with this email to upgrade your account.</a></div>

                    <div class="input-container">
                        <input type="text" id="credential_value" name="credential_value" required style="padding-left:45px;" aria-label="your email">
                        <div id="loading-spinner" class="spinner" style="display: none;"></div>
<!--                        <div id="credential-pin" class="pin-icon">⚪</div>
-->                    </div>
                <p class="form-caption" data-lang-id="006-email-sub-caption">💌 This is the way we will contact you to confirm your account</p>
                </div>

                <div class="form-item" id="set-password" style="display: none;">
                    <label for="password_hash" data-lang-id="007-set-your-pass">Set your password:</label><br>
                    <div class="password-wrapper">
                        <input type="password" id="password_hash" name="password_hash" required minlength="6">
                        <span toggle="#password_hash" class="toggle-password" style="cursor: pointer;">🔒</span>
                    </div>
                    <p class="form-caption" data-lang-id="008-password-advice">🔑 Your password must be at least 6 characters.</p>
                </div>

                <div class="form-item" id="confirm-password-section" style="display: none;">
                    <label for="confirm_password" data-lang-id="009-confirm-pass">Confirm Your Password:</label><br>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <span toggle="#confirm_password" class="toggle-password" style="cursor: pointer;">🔒</span>
                    </div>
                    <div id="maker-error-invalid" class="form-field-error" style="margin-top:10px;" data-lang-id="010-pass-error-no-match">👉 Passwords do not match.</div>
                </div>


                <div class="form-item" id="human-check-section" style="display: none;">
                    <label for="human_check" data-lang-id="011-prove-human">Please prove you are human by typing the word "ecobrick" below:</label><br>
                    <input type="text" id="human_check" name="human_check" required>
                    <p class="form-caption"><span data-lang-id="012-fun-fact">🤓 Fun fact: </span> <a href="#" onclick="showModalInfo('ecobrick', '<?php echo $lang; ?>')" class="underline-link" data-lang-id="000-ecobrick">ecobrick</a><span data-lang-id="012b-is-spelled"> is spelled without a space, capital or hyphen!</span></p>
                    <div style="margin-top:-16px">
                        <input type="checkbox" id="terms" name="terms" required checked>
                        <label for="terms" class="form-caption" data-lang-id="013-by-registering">By registering today, I agree to the <a href="#" onclick="showModalInfo('terms', '<?php echo $lang; ?>')" class="underline-link">GoBrik Terms of Service</a></label>
                    </div>
                <!--
                    <div>
                        <input type="checkbox" id="newsletter" name="newsletter" checked>
                        <label for="newsletter" style="font-size:1.0;" class="form-caption" data-lang-id="014-i-agree-newsletter">I agree to receive the <a href="#" onclick="showModalInfo('earthen', '<?php echo $lang; ?>')" class="underline-link">Earthen newsletter</a> for app, ecobrick, and earthen updates</label>
                    </div>
                -->



                </div>

                <div id="submit-section" style="display:none;text-align:center;margin-top:15px;" title="Be sure you wrote ecobrick correctly!" data-lang-id="015-register-button">
                    <input type="submit" id="submit-button" value="Register" class="submit-button disabled">
                </div>
            </form>


        </div>

<div style="font-size: medium; text-align: center; margin: auto; align-self: center;padding-top:40px;padding-bottom:40px;margin-top: 0px;">
        <p style="font-size:medium;" data-lang-id="000-already-have-account">Already have an account? <a href="login.php">Login</a></p>
    </div>



    </div>
</div>

    <!--FOOTER STARTS HERE-->
    <?php require_once ("../footer-2024.php"); ?>


<script>
$(document).ready(function() {
    // Elements
    const credentialField = document.getElementById('credential_value');
    const passwordField = document.getElementById('password_hash');
    const confirmPasswordField = document.getElementById('confirm_password');
    const humanCheckField = document.getElementById('human_check');
    const termsCheckbox = document.getElementById('terms');
    const submitButton = document.getElementById('submit-button');
    const confirmPasswordSection = document.getElementById('confirm-password-section');
    const humanCheckSection = document.getElementById('human-check-section');
    const submitSection = document.getElementById('submit-section');
    const setPasswordSection = document.getElementById('set-password');
    const makerErrorInvalid = document.getElementById('maker-error-invalid');
    const duplicateEmailError = $('#duplicate-email-error');
    const duplicateGobrikEmail = $('#duplicate-gobrik-email');
    const loadingSpinner = $('#loading-spinner');

    // Initially hide all sections except the email field
    setPasswordSection.style.display = 'none';
    confirmPasswordSection.style.display = 'none';
    humanCheckSection.style.display = 'none';
    submitSection.style.display = 'none';

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Live email checking and validation
    $('#credential_value').on('input blur', function() {
        const email = $(this).val();

        if (isValidEmail(email)) {
            loadingSpinner.removeClass('green red').show();

            $.ajax({
                url: 'check_email.php',
                type: 'POST',
                data: { credential_value: email },
                success: function(response) {
                    loadingSpinner.hide();

                    try {
                        var res = JSON.parse(response);
                    } catch (e) {
                        console.error("Invalid JSON response", response);
                        alert("An error occurred while checking the email.");
                        return;
                    }

                    // Handle different responses
                    if (res.success) {
                        duplicateEmailError.hide();
                        duplicateGobrikEmail.hide();
                        loadingSpinner.removeClass('red').addClass('green').show();
                        setPasswordSection.style.display = 'block';
                    } else if (res.error === 'duplicate_email') {
                        duplicateEmailError.show();
                        duplicateGobrikEmail.hide();
                        loadingSpinner.removeClass('green').addClass('red').show();
                        setPasswordSection.style.display = 'none';
                    } else if (res.error === 'duplicate_gobrik_email') {
                        duplicateGobrikEmail.show();
                        duplicateEmailError.hide();
                        loadingSpinner.removeClass('red').addClass('green').show();
                        setPasswordSection.style.display = 'none'; // don't allow user to proceed with password setup
                    } else {
                        alert("An error occurred: " + res.error);
                    }
                },
                error: function() {
                    loadingSpinner.hide();
                    alert('An error occurred while checking the email. Please try again.');
                }
            });
        } else {
            setPasswordSection.style.display = 'none'; // Hide password section if email is invalid
        }
    });

    // Show confirm password field when password length is at least 6 characters
    passwordField.addEventListener('input', function() {
        if (passwordField.value.length >= 6) {
            confirmPasswordSection.style.display = 'block';
        } else {
            confirmPasswordSection.style.display = 'none';
            humanCheckSection.style.display = 'none';
            submitSection.style.display = 'none';
        }
    });

    // Show human check section and submit button when passwords match
    confirmPasswordField.addEventListener('input', function() {
        if (passwordField.value === confirmPasswordField.value) {
            makerErrorInvalid.style.display = 'none';
            humanCheckSection.style.display = 'block';
            submitSection.style.display = 'block';
        } else {
            makerErrorInvalid.style.display = 'block';
            humanCheckSection.style.display = 'none';
            submitSection.style.display = 'none';
        }
    });

// Activate submit button when a valid word is typed and terms checkbox is checked
function updateSubmitButtonState() {
    const validWords = ['ecobrick', 'ecoladrillo', 'écobrique', 'ecobrique']; // List of accepted words
    const enteredWord = humanCheckField.value.toLowerCase(); // Get the user's input and convert to lowercase

    // Check if the entered word is in the list of valid words and if the terms checkbox is checked
    if (validWords.includes(enteredWord) && termsCheckbox.checked) {
        submitButton.classList.remove('disabled');
        submitButton.classList.add('enabled');
        submitButton.disabled = false;
    } else {
        submitButton.classList.remove('enabled');
        submitButton.classList.add('disabled');
        submitButton.disabled = true;
    }
}


    humanCheckField.addEventListener('input', updateSubmitButtonState);
    termsCheckbox.addEventListener('change', updateSubmitButtonState);

    // Form submission
    $('#password-confirm-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally
        loadingSpinner.removeClass('green red').show();

        $.ajax({
            url: 'signup_process.php?id=<?php echo htmlspecialchars($buwana_id); ?>',
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                loadingSpinner.hide();
                try {
                    var res = JSON.parse(response);
                } catch (e) {
                    alert('An error occurred while processing the form.');
                    return;
                }

                if (res.success) {
                    window.location.href = res.redirect || 'confirm-email.php?id=<?php echo htmlspecialchars($buwana_id); ?>';
                } else if (res.error === 'duplicate_email') {
                    duplicateEmailError.show();
                    duplicateGobrikEmail.hide();
                    loadingSpinner.removeClass('green').addClass('red').show();
                } else if (res.error === 'duplicate_gobrik_email') {
                    duplicateGobrikEmail.show();
                    duplicateEmailError.hide();
                    loadingSpinner.removeClass('red').addClass('green').show();
                } else {
                    alert('An unexpected error occurred. Please try again.');
                }
            },
            error: function() {
                loadingSpinner.hide();
                alert('An error occurred while processing the form. Please try again.');
            }
        });
    });
});


/* Control the header position as the page scrolls*/


</script>





</body>
</html>
