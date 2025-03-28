<?php
require_once '../earthenAuth_helper.php'; // Include the authentication helper functions

// Set up page variables
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$version = '0.393';
$page = 'activate';
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

// PART 1: Setup
$ecobricker_id = $_GET['id'] ?? null;
$first_name = '';
$last_name = '';
$full_name = '';
$email_addr = '';
$brk_balance = 0;
$user_roles = '';
$birth_date = '';
$terms_of_service = 1;
$earthen_newsletter_join = 1;



// PART 2: Database Connections
require_once '../gobrikconn_env.php';
require_once '../buwanaconn_env.php';

// Redirect give various scenarios. Helper function to redirect with messages
function redirect_with_message($url, $message) {
    $_SESSION['error_message'] = $message;
    header("Location: $url");
    exit();
}

// Fetch user info from GoBrik
function get_user_info($ecobricker_id, $conn) {
    $sql = "SELECT first_name, last_name, full_name, email_addr, brk_balance, user_roles, birth_date FROM tb_ecobrickers WHERE ecobricker_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $ecobricker_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name, $full_name, $email_addr, $brk_balance, $user_roles, $birth_date);
        $stmt->fetch();
        $stmt->close();
        return compact('first_name', 'last_name', 'full_name', 'email_addr', 'brk_balance', 'user_roles', 'birth_date');
    } else {
        error_log('Error preparing statement: ' . $conn->error);
        return null;
    }
}

// PART 3: Check if ecobricker already has a buwana_id
function check_existing_buwana($ecobricker_id, $conn) {
    $sql = "SELECT buwana_id FROM tb_ecobrickers WHERE ecobricker_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $ecobricker_id);
        $stmt->execute();
        $stmt->bind_result($buwana_id);
        $stmt->fetch();
        $stmt->close();
        return $buwana_id;
    } else {
        error_log('Error preparing statement for checking buwana_id: ' . $conn->error);
        return null;
    }
}

// Check if ecobricker already has a buwana_id
$buwana_id = check_existing_buwana($ecobricker_id, $gobrik_conn);
if ($buwana_id) {
    redirect_with_message("activate-3.php?id=" . urlencode($buwana_id), "Account already activated.");
}

// PART 4: Fetch user details from GoBrik database
$user_info = get_user_info($ecobricker_id, $gobrik_conn);
if (!$user_info) {
    redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "An error occurred while fetching user details. Please try again.");
}

// Extract user details from the fetched array
extract($user_info);

// PART 5: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate passwords
    $password = $_POST['form_password'];
    $confirm_password = $_POST['confirm_password'];
    $terms_accepted = isset($_POST['terms']);
    $newsletter_opt_in = isset($_POST['newsletter']) ? 1 : 0;

    if ($password !== $confirm_password) {
        redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Your passwords don't match. Please try again.");
    }

    if (strlen($password) < 6) {
        redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Your new password is too short! Please try again.");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // PART 6: Insert new user into Buwana database or check for existing email
$sql_check_email = "SELECT buwana_id FROM users_tb WHERE email = ?";
$stmt_check_email = $buwana_conn->prepare($sql_check_email);

if ($stmt_check_email) {
    $stmt_check_email->bind_param("s", $email_addr);
    $stmt_check_email->execute();
    $stmt_check_email->bind_result($existing_buwana_id);
    $stmt_check_email->fetch();
    $stmt_check_email->close();

    if ($existing_buwana_id) {
        // Existing user found, redirect to activate-3.php
        redirect_with_message("activate-3.php?id=" . urlencode($existing_buwana_id), "Whoops! You've already done this process. Continue now by updating your account.");
    } else {
        // No existing user, insert new user
        $sql_insert_buwana = "INSERT INTO users_tb (first_name, last_name, full_name, email, password_hash, brikcoin_balance, role, account_status, created_at, terms_of_service, notes, validation_credits, earthen_newsletter_join, birth_date)
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'Activated from legacy gobrik, step 2 only', NOW(), 1, 'First experimental activations', 3, ?, ?)";
        $stmt_insert_buwana = $buwana_conn->prepare($sql_insert_buwana);

        if ($stmt_insert_buwana) {
            $stmt_insert_buwana->bind_param('sssssisis', $first_name, $last_name, $full_name, $email_addr, $password_hash, $brk_balance, $user_roles, $newsletter_opt_in, $birth_date);

            if ($stmt_insert_buwana->execute()) {
                // Successfully inserted, get the new buwana_id
                $buwana_id = $stmt_insert_buwana->insert_id;  // Set buwana_id globally
            } else {
                redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error creating account. Please try again.");
            }

            $stmt_insert_buwana->close();
        } else {
            redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error preparing account creation. Please try again.");
        }
    }
} else {
    redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error checking email. Please try again.");
}

// PART 7: INSERT into credentials_tb with the new credential key and type
$sql_insert_credential = "INSERT INTO credentials_tb (buwana_id, credential_key, credential_type) VALUES (?, ?, 'email')";
$stmt_insert_credential = $buwana_conn->prepare($sql_insert_credential);

if ($stmt_insert_credential) {
    $stmt_insert_credential->bind_param("is", $buwana_id, $email_addr);
    if (!$stmt_insert_credential->execute()) {
        redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error inserting credentials. Please try again.");
    }
    $stmt_insert_credential->close();
} else {
    redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error preparing credential insert. Please try again.");
}

// PART 8: Update GoBrik database's ecobricker with Buwana ID and other details
error_log("buwana_id: $buwana_id, ecobricker_id: $ecobricker_id"); // Log IDs to ensure they exist

if ($gobrik_conn->ping()) {
    error_log("GoBrik connection is alive.");
} else {
    error_log("GoBrik connection lost.");
}

$sql_update_gobrik = "UPDATE tb_ecobrickers SET buwana_id = ?, buwana_activated = 1, buwana_activation_dt = NOW(), account_notes = 'Activated step 2, Second experimental migrations' WHERE ecobricker_id = ?";
$stmt_update_gobrik = $gobrik_conn->prepare($sql_update_gobrik);

if ($stmt_update_gobrik) {
    $stmt_update_gobrik->bind_param('ii', $buwana_id, $ecobricker_id);
    if ($stmt_update_gobrik->execute()) {
        error_log("GoBrik records updated successfully for ecobricker_id: $ecobricker_id");
        $stmt_update_gobrik->close();
    } else {
        error_log("Error executing GoBrik update: " . $stmt_update_gobrik->error);
        redirect_with_message("activate-2.php?id=" . urlencode($ecobricker_id), "Error updating GoBrik records. Please try again.");
    }
} else {
    error_log("Error preparing GoBrik statement: " . $gobrik_conn->error);
    echo "<script>
        alert('Error preparing update for GoBrik records. Please try again.');
        window.location.href = 'activate-2.php?id=" . urlencode($ecobricker_id) . "';
    </script>";
    exit();
}


// Redirect to the next step
header("Location: activate-3.php?id=" . urlencode($buwana_id));
exit();


    // Close the Buwana database connection
    $buwana_conn->close();
}
?>







<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">
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
            <h2 data-lang-id="001-set-your-pass">Set Your New Password</h2>
            <p><span data-lang-id="001-alright">Alright </span> <?php echo htmlspecialchars($first_name); ?>: <span data-lang-id="002-to-get-going"> To get going with your upgraded account please set a new password...</span></p>
        </div>

        <!--ACTIVATE 2 FORM-->
<form id="password-confirm-form" method="post" action="activate-2.php?id=<?php echo htmlspecialchars($ecobricker_id); ?>">
   <div class="form-item" id="set-password">
    <label for="form_password" data-lang-id="007-set-your-pass">Set your password:</label><br>
    <div class="password-wrapper">
        <input type="password" id="form_password" name="form_password" required minlength="6">
        <span class="toggle-password" toggle="#form_password">🔒</span>
    </div>
    <p class="form-caption" data-lang-id="008-password-advice">🔑 Your password must be at least 6 characters.</p>
</div>

<div class="form-item" id="confirm-password-section" style="display:none;">
    <label for="confirm_password" data-lang-id="009-confirm-pass">Confirm Your Password:</label><br>
    <div class="password-wrapper">
        <input type="password" id="confirm_password" name="confirm_password" required>
        <span class="toggle-password" toggle="#confirm_password">🔒</span>
    </div>
    <div id="maker-error-invalid" class="form-field-error" style="margin-top:10px;display:none;" data-lang-id="010-pass-error-no-match">👉 Passwords do not match.</div>
</div>


            <div class="form-item" id="human-check-section">
                <div>
                    <input type="checkbox" id="terms" name="terms" required checked>
                    <label for="terms" style="font-size:medium;" class="form-caption" data-lang-id="013-by-registering">By registering today, I agree to the <a href="#" onclick="showModalInfo('terms')" class="underline-link">GoBrik Terms of Service</a></label>
                </div>
                <div>
                    <input type="checkbox" id="newsletter" name="newsletter" checked>
                    <label for="newsletter" style="font-size:medium;" class="form-caption" data-lang-id="014-i-agree-newsletter">Please send me the <a href="#" onclick="showModalInfo('earthen')" class="underline-link">Earthen newsletter</a> for app, ecobrick, and earthen updates</label>
                </div>
            </div>

            <div id="submit-section" style="text-align:center;margin-top:15px;" data-lang-id="015-confirm-pass-button">
                <input type="submit" id="submit-button" value="Confirm Password" class="submit-button disabled">
            </div>
        </form>

    </div>


</div>
</div>
    <!--FOOTER STARTS HERE-->
    <?php require_once ("../footer-2024.php"); ?>

<script>
//activate.php send new users here

/*FORM FIELD VALIDATION*/

$(document).ready(function() {
    // Form elements
    const passwordField = document.getElementById('form_password');
    const confirmPasswordSection = document.getElementById('confirm-password-section');
    const confirmPasswordField = document.getElementById('confirm_password');
    const makerErrorInvalid = document.getElementById('maker-error-invalid');
    const submitButton = document.getElementById('submit-button');
    const termsCheckbox = document.getElementById('terms');

    // Show confirm password field when password length is at least 6 characters
    passwordField.addEventListener('input', function() {
        if (passwordField.value.length >= 6) {
            confirmPasswordSection.style.display = 'block';
        } else {
            confirmPasswordSection.style.display = 'none';
            makerErrorInvalid.style.display = 'none';
            updateSubmitButtonState();
        }
    });

    // Enable submit button when passwords match and terms are checked
    confirmPasswordField.addEventListener('input', function() {
        if (passwordField.value === confirmPasswordField.value) {
            makerErrorInvalid.style.display = 'none';
            updateSubmitButtonState();
        } else {
            makerErrorInvalid.style.display = 'block';
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
            submitButton.classList.remove('enabled');
        }
    });

    // Update button state when terms checkbox is clicked
    termsCheckbox.addEventListener('change', updateSubmitButtonState);

    // Function to update the submit button state
    function updateSubmitButtonState() {
        if (
            passwordField.value.length >= 6 &&
            passwordField.value === confirmPasswordField.value &&
            termsCheckbox.checked
        ) {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
            submitButton.classList.add('enabled');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
            submitButton.classList.remove('enabled');
        }
    }
});


$(document).ready(function() {
    // No need to prevent form submission or handle AJAX
    $('#password-confirm-form').on('submit', function(e) {
        // The form will submit normally
        // Server-side validation will handle errors and redirection
    });
});



</script>

</body>
</html>
