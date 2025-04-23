<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Needed for app context persistence

require_once '../buwanaconn_env.php';         // Sets up $buwana_conn
require_once '../fetch_app_info.php';         // Retrieves designated app's core data


// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.62';
$page = 'signup';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));
$is_logged_in = false; // Ensure not logged in for this page

// ✅ Direct session check instead of calling a function
if (!empty($_SESSION['buwana_id'])) {
    $redirect_url = $_SESSION['redirect_url'] ?? $app_info['app_url'] ?? 'https://gobrik.com';
    echo "<script>
        alert('Looks like you already have an account and are logged in! Let\'s take you to your dashboard.');
        window.location.href = '$redirect_url';
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
   <div id="top-page-image" class="credentials-banner top-page-image"></div>

<div id="form-submission-box" class="landing-page-form" >
    <div class="form-container" style="box-shadow: #0000001f 0px 5px 20px;">

            <div style="text-align:center;width:100%;margin:auto;">
                <h2><span data-lang-id="001-register-by">Register by</span> <?php echo $credential_type; ?></h2>
                <p>Ok <?php echo $first_name; ?>! <span data-lang-id="002-now-lets-use">  Let's get you set up on</span> <?= $app_info['app_display_name']; ?>.</p>
            </div>

<!-- <div class="form-item" id="last-name" class="user_lastname" style="display:none!important;">
                    <label for="last_name" data-lang-id="011b-last-name">Now what is your last name?</label><br>
                    <input type="text" id="human_check" class="required" placeholder="Your last name...">
                    <p class="form-caption" data-lang-id="011b-required" style="color:red">*This field is required.</p>
                </div>-->


           <form id="user-signup-form" method="post" action="signup_process.php?id=<?php echo htmlspecialchars($buwana_id); ?>">

             <!-- Email / Credential Field -->
             <div class="form-item float-label-group" id="credential-section">
               <input type="text" id="credential_value" name="credential_value" required aria-label="Your email" placeholder=" " style="padding-left:35px"/>
               <label for="credential_value">
                 <span data-lang-id="004-your">Your</span> <?php echo $credential_type; ?><span data-lang-id="004b-please-x"> please...</span>
               </label>

               <div id="duplicate-email-error" class="form-field-error" data-lang-id="010-duplicate-email">
                 🚧 Whoops! Looks like that e-mail address is already being used by a Buwana Account. Please choose another.
               </div>
               <div id="duplicate-gobrik-email" class="form-warning">
                 🌏 <span data-lang-id="010-gobrik-duplicate">It looks like this email is already being used with a legacy GoBrik account. Please <a href="login.php" class="underline-link">login with this email to upgrade your account.</a></span>
               </div>

               <div id="loading-spinner" class="spinner" style="display: none;margin-left: 10px;margin-top: 1px;"></div>

               <p class="form-caption" data-lang-id="006-email-sub-caption" style="margin-bottom: -10px;">💌 This is the way we will contact you to confirm your account</p>
             </div>

             <!-- Set Password -->
             <div class="form-item float-label-group" id="set-password" style="display: none;">
               <input type="password" id="password_hash" name="password_hash" required minlength="6" placeholder=" " style="font-size: 22px !important;"/>
               <label for="password_hash" data-lang-id="007-set-your-pass-x">Set your password...</label>
               <span toggle="#password_hash" class="toggle-password" style="cursor: pointer; top:36%;margin-right:15px;font-size:20px;">🙈</span>
               <p class="form-caption" data-lang-id="008-password-advice">🔑 Your password must be at least 6 characters.</p>
             </div>

             <!-- Confirm Password -->
             <div class="form-item float-label-group" id="confirm-password-section" style="display: none;">
               <input type="password" id="confirm_password" name="confirm_password" required placeholder=" " style="font-size: 22px !important;"/>
               <label for="confirm_password" data-lang-id="009-confirm-pass-x">Confirm Your Password...</label>
               <span toggle="#confirm_password" class="toggle-password" style="cursor: pointer;margin-bottom:13px;margin-right:15px; font-size:20px">🙈</span>
               <div id="maker-error-invalid" class="form-field-error" data-lang-id="010-pass-error-no-match">👉 Passwords do not match.</div>
             </div>


             <!-- Human Check -->
             <div class="form-item float-label-group" id="human-check-section" style="display: none;">


               <input type="text" id="human_check" name="human_check" required placeholder=" " />
               <label for="human_check" data-lang-id="011-prove-human">Type the word "ecobrick"...</label>
               <p class="form-caption"><span>This is a little test to see if you're human</span>

                 <span data-lang-id="012-fun-fact">🤓 Fun fact: </span>
                 <a href="#" onclick="showModalInfo('ecobrick', '<?php echo $lang; ?>')" class="underline-link" data-lang-id="000-ecobrick-x">ecobrick</a>
                 <span data-lang-id="012b-is-spelled"> is spelled without a space, capital or hyphen!</span>
               </p>
           </div>

               <div style="display:flex;" class="form-item">
                 <input type="checkbox" id="terms" name="terms" required checked>
                 <div class="form-caption"><span data-lang-id="013-by-registering">By registering today, I agree to the </span><a href="#" onclick="openTermsModal(); return false;" class="underline-link"><?= $app_info['app_display_name']; ?> <span>Terms of Use</a>.
                 </div>
               </div>
             </div>

             <!-- Kick-Ass Submit Button -->
             <div id="submit-section" style="display:none;" class="submit-button-wrapper">
               <button type="submit" id="submit-button" class="kick-ass-submit disabled" title="Be sure you wrote ecobrick correctly!">
                 <span id="submit-button-text" data-lang-id="015-register-button-x">Register ➡</span>
                 <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
               </button>
             </div>

           </form>




<div id="browser-back-link" style="font-size: medium; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;" data-lang-id="000-go-back">
    <p style="font-size: medium;" >
        Need to correct something?
        <a href="#" onclick="browserBack(event)">Go back ↩️</a>
    </p>
</div>



    </div>
</div>

    <?php require_once ("../footer-2025.php"); ?>


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
                url: '../scripts/check_email.php',
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
    $('#user-signup-form').on('submit', function(e) {
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


<?php require_once ("../scripts/app_modals.php");?>




</body>
</html>
