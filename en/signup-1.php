<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'signup-1_process.php';
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Needed for app context persistence

require_once '../buwanaconn_env.php';         // Sets up $buwana_conn
require_once '../fetch_app_info.php';         // Retrieves designated app's core data


// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.772';
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
    header("Location: $redirect_url"); // Fallback in case JS doesn't run
    exit();
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



<?php require_once ("../includes/signup-inc.php");?>


<div class="splash-title-block"></div>
<div id="splash-bar"></div>

<!-- PAGE CONTENT -->
   <div id="top-page-image" class="app-signup-banner top-page-image"></div>

<div id="form-submission-box" class="landing-page-form" >
    <div class="form-container" style="box-shadow: #0000001f 0px 5px 20px;">

        <div style="text-align:center;width:100%;margin:auto;">
            <div id="status-message" data-lang-id="001-signup-heading" style="font-family: 'Arvo';margin-top:15px;">
                Create Your Account
            </div>

            <div id="sub-status-message" style="margin-bottom:15px;"><?= htmlspecialchars($app_info['app_display_name']) ?><span data-lang-id="002-signup-subtext"> uses the Buwana Authentication protocol— a powerful and private, opensource and for-Earth protocol that powers regenerative apps.</div>
        </div>

       <!--SIGNUP-1 FORM-->
<form id="user-signup-form" method="post" action="signup-1_process.php" novalidate>

   <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
     <input type="text" id="first_name" name="first_name"
            aria-label="Your first name"
            maxlength="255"
            required
            placeholder=" " />
     <label for="first_name" data-lang-id="003-firstname">What's your first name?</label>
     <!-- ERRORS -->
     <div id="maker-error-required" class="form-field-error" data-lang-id="000-field-required-error">This field is required.</div>
     <div id="maker-error-long" class="form-field-error" data-lang-id="000-name-field-too-long-error">The name is too long. Max 255 characters.</div>
     <div id="maker-error-invalid" class="form-field-error" data-lang-id="005b-name-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
   </div>



<div class="form-item credential-select-wrapper">
    <select id="credential" name="credential" aria-label="Preferred Credential" required
        style="font-size: 20px; font-family: 'Mulish',sans-serif; padding-left: 15px; color: var(--subdued-text);">
        <option value="" disabled selected data-lang-id="006-credential-choice">Select how you register...</option>
        <option value="e-mail">E-mail</option>
        <option value="Phone number">Phone number</option>
        <option value="peer" disabled>Peer</option>
    </select>


    <!-- Error Message -->
    <div id="credential-error-required" class="form-field-error" data-lang-id="000-field-required-error">
        This field is required.
    </div>
</div>



<div class="submit-button-wrapper">

<button type="submit" id="submit-button" class="kick-ass-submit">
  <span id="submit-button-text">Next ➡</span>
  <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
</button>


</div>


</div>

</form>

</div>

    <div style="font-size: medium; text-align: center; margin: auto; align-self: center;padding-top:40px;padding-bottom:50px;margin-top: 0px;">
        <p style="font-size:medium;"><span data-lang-id="000-already-have-account">Already have a Buwana account?</span> <a href="<?= htmlspecialchars($app_info['app_url']) ?>/">Login</a>.</p>
    </div>

    </div><!--closes Landing content-->






 </div>

</div><!--closes main and starry background-->

<!--FOOTER STARTS HERE-->

<?php require_once ("../footer-2025.php");?>

</div><!--close page content-->


<script>

    document.addEventListener('DOMContentLoaded', function () {
        const credentialSelect = document.getElementById('credential');

        function updateCredentialColor() {
            if (credentialSelect.value === "") {
                credentialSelect.style.color = "var(--subdued-text)";
            } else {
                credentialSelect.style.color = "var(--h1)";
            }
        }

        // Run it initially (in case a value is pre-selected)
        updateCredentialColor();

        // Run it whenever user changes selection
        credentialSelect.addEventListener('change', updateCredentialColor);
    });


document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('user-signup-form');
  const submitButton = document.getElementById('submit-button');
  const firstNameInput = document.getElementById('first_name');
  const credentialSelect = document.getElementById('credential');
  const btnText = document.getElementById('submit-button-text');

  const errorRequired = document.getElementById('maker-error-required');
  const errorLong = document.getElementById('maker-error-long');
  const errorInvalid = document.getElementById('maker-error-invalid');
  const credentialError = document.getElementById('credential-error-required');

  function hasInvalidChars(value) {
    const invalidChars = /[\'\"><]/;
    return invalidChars.test(value);
  }

  function displayError(element, show) {
    if (element) element.style.display = show ? 'block' : 'none';
  }

  function validateFieldsLive() {
    const firstName = firstNameInput.value.trim();
    const isFirstNameValid = firstName.length > 0 && firstName.length <= 255;
    const isCredentialValid = credentialSelect.value !== "";

    return isFirstNameValid && isCredentialValid;
  }

  function validateOnSubmit() {
    let isValid = true;
    const firstName = firstNameInput.value.trim();
    const credential = credentialSelect.value;

    displayError(errorRequired, firstName === '');
    displayError(errorLong, firstName.length > 255);
    displayError(errorInvalid, hasInvalidChars(firstName));
    displayError(credentialError, credential === '');

    if (firstName === '' || firstName.length > 255 || hasInvalidChars(firstName)) {
      isValid = false;
    }

    if (credential === '') {
      isValid = false;
    }

    return isValid;
  }

  // Real-time validation
  firstNameInput.addEventListener('input', validateFieldsLive);
  credentialSelect.addEventListener('change', validateFieldsLive);

  // Custom submit logic
  form.addEventListener('submit', function (event) {
    event.preventDefault();

    if (validateOnSubmit()) {
      form.dispatchEvent(new Event('kickAssSubmit')); // Let global script handle animations
    } else {
      shakeElement(submitButton);
    }
  });


});
</script>




<?php require_once ("../scripts/app_modals.php");?>






</body>

</html>
