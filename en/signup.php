<?php
session_start(); // Needed for app context persistence

require_once '../earthenAuth_helper.php';     // Include auth helper functions
require_once '../buwanaconn_env.php';         // Sets up $buwana_conn
require_once '../fetch_app_info.php';         // Retrieves designated app's core data

// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.51';
$page = 'signup';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));
$is_logged_in = false; // Ensure not logged in for this page

// Check if the user is already logged in and redirect to app dashboard if they are
if (isLoggedIn()) {
    $redirect_url = $_SESSION['redirect_url'] ?? $app_info['app_url'] ?? 'https://gobrik.com';
    echo "<script>
        alert('Looks like you already have an account and are logged in! Let\'s take you to your dashboard.');
        window.location.href = '$redirect_url';
    </script>";
    exit();
}

$success = false;


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $first_name = trim($_POST['first_name']);
    $credential = trim($_POST['credential']);

    // Set other required fields
    $full_name = $first_name;
    $created_at = date("Y-m-d H:i:s");
    $last_login = date("Y-m-d H:i:s");
    $account_status = 'name set only';
    $role = 'ecobricker';
    $notes = "beta testing the first signup form";

    // Prepare the SQL statement for inserting user data into the Buwana user_tb
    $sql_user = "INSERT INTO users_tb (first_name, full_name, created_at, last_login, account_status, role, notes) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_user = $buwana_conn->prepare($sql_user);

    // Bind the data to the user_tb (s = string)
    if ($stmt_user) {
        $stmt_user->bind_param("sssssss", $first_name, $full_name, $created_at, $last_login, $account_status, $role, $notes);

        if ($stmt_user->execute()) {
            $buwana_id = $buwana_conn->insert_id;

            // Prepare the SQL statement for inserting credential data into credentials_tb
            $sql_credential = "INSERT INTO credentials_tb (buwana_id, credential_type, times_used, failed_password_count, last_login) VALUES (?, ?, 0, 0, ?)";
            $stmt_credential = $buwana_conn->prepare($sql_credential);

            if ($stmt_credential) {
                $stmt_credential->bind_param("iss", $buwana_id, $credential, $last_login);

                if ($stmt_credential->execute()) {
                    $success = true;
                    // Redirect to signup-2.php with the buwana_id in the URL
                    header("Location: signup-2.php?id=$buwana_id");
                    exit();
                } else {
                    error_log("Error executing credential statement: " . $stmt_credential->error);
                    $error_message = "An error occurred while creating your account. Please try again.";
                }
                $stmt_credential->close();
            } else {
                error_log("Error preparing credential statement: " . $buwana_conn->error);
                $error_message = "An error occurred while creating your account. Please try again.";
            }
        } else {
            error_log("Error executing user statement: " . $stmt_user->error);
            $error_message = "An error occurred while creating your account. Please try again.";
        }
        $stmt_user->close();
    } else {
        error_log("Error preparing user statement: " . $buwana_conn->error);
        $error_message = "An error occurred while creating your account. Please try again.";
    }

    $buwana_conn->close();
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

<?php if ($success): ?>
    <script type="text/javascript">
        showSuccessMessage();
    </script>
<?php endif; ?>


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

       <!--SIGNUP FORM-->
<form id="user-signup-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

   <div class="form-item float-label-group">
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



    <div class="form-item">
        <select id="credential" name="credential" aria-label="Preferred Credential" required style="font-size: 20px !important;color:var(--subdued-text);margin-left:5px;" >
            <option value="" disabled selected data-lang-id="006-credential-choice">Select how you register...</option>
            <option value="email">E-mail</option>
            <option value="mail">Phone number</option>
            <option value="peer" disabled>Peer</option>
        </select>
        <p class="form-caption" data-lang-id="007-way-to-contact">We'll send your account confirmation messages this way.  Later you'll login this way.</p>
        <!--ERRORS-->
        <div id="credential-error-required" class="form-field-error" data-lang-id="000-field-required-error">This field is required.</div>
    </div>


<div class="submit-button-wrapper">
  <button type="submit" id="submit-button" class="kick-ass-submit">
    <span id="submit-button-text" data-lang-id="016-submit-to-password">Next ➡️</span>
    <span id="submit-spinner" class="spinner" style="display: none;"></span>
  </button>

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
document.addEventListener('DOMContentLoaded', () => {
  // === DOM Elements ===
  const form = document.getElementById('user-signup-form');
  const firstNameInput = document.getElementById('first_name');
  const credentialSelect = document.getElementById('credential');
  const submitButton = document.getElementById('submit-button');
  const btn = submitButton;
  const btnText = document.getElementById('submit-button-text');
  const spinner = document.getElementById('submit-spinner');
  const errorRequired = document.getElementById('maker-error-required');
  const errorLong = document.getElementById('maker-error-long');
  const errorInvalid = document.getElementById('maker-error-invalid');
  const credentialError = document.getElementById('credential-error-required');

  // === Helper Functions ===

  function hasInvalidChars(value) {
    const invalidChars = /[\'\"><]/;
    return invalidChars.test(value);
  }

  function displayError(element, show) {
    element.style.display = show ? 'block' : 'none';
  }

  function validateFieldsLive() {
    // This function now simply checks — it doesn't modify the button anymore
    const firstNameValid = firstNameInput.value.trim().length > 0 && firstNameInput.value.trim().length <= 255;
    const credentialValid = credentialSelect.value !== "";

    // You can still hook up visual cues here later if desired
    return firstNameValid && credentialValid;
  }

  function validateOnSubmit() {
    let isValid = true;
    const firstName = firstNameInput.value.trim();
    const credential = credentialSelect.value;

    // Validate First Name
    displayError(errorRequired, firstName === '');
    displayError(errorLong, firstName.length > 255);
    displayError(errorInvalid, hasInvalidChars(firstName));

    if (firstName === '' || firstName.length > 255 || hasInvalidChars(firstName)) {
      isValid = false;
    }

    // Validate Credential
    displayError(credentialError, credential === '');
    if (credential === '') {
      isValid = false;
    }

    return isValid;
  }

  // === Event Listeners ===

  // Live validation
  firstNameInput.addEventListener('input', validateFieldsLive);
  credentialSelect.addEventListener('change', validateFieldsLive);
  validateFieldsLive(); // Initial check

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    if (validateOnSubmit()) {
      // Trigger click animation
      btn.classList.add('click-animating');
      btn.classList.remove('pulse-started');

      // Show spinner, hide text
      btnText.style.display = 'none';
      spinner.style.display = 'inline-block';

      // Remove hover animation
      btn.removeAttribute('data-hovered');
      btn.classList.remove('pulse-started');

      // Delay actual form submission
      setTimeout(() => {
        form.submit();
      }, 1500);
    } else {
      // 🚨 Trigger shake if form is invalid
      shakeElement(btn);
    }
  });


  // Allow Enter to submit unless focus is on button or select
  form.addEventListener('keypress', function (event) {
    if (event.key === "Enter") {
      if (["BUTTON", "SELECT"].includes(event.target.tagName)) {
        event.preventDefault();
      } else {
        this.dispatchEvent(new Event('submit', { cancelable: true }));
      }
    }
  });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.kick-ass-submit');

  btn.addEventListener('mouseenter', () => {
    btn.setAttribute('data-hovered', 'true');
    btn.classList.remove('pulse-started', 'returning');

    // Delay before starting pulse (after entrance finishes)
    setTimeout(() => {
      btn.classList.add('pulse-started');
    }, 400); // Matches duration of powerStripeEntrance
  });

  btn.addEventListener('mouseleave', () => {
    btn.removeAttribute('data-hovered');
    btn.classList.remove('pulse-started');

    // Trigger return animation
    btn.classList.add('returning');

    // Remove return class after animation completes
    setTimeout(() => {
      btn.classList.remove('returning');
    }, 500); // Match the actual duration of powerStripeReturn
  });
});
</script>





</body>

</html>
