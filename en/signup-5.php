<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'signup-5';
$version = '0.772';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// Already logged in?
if (!empty($_SESSION['buwana_id'])) {
    $redirect_url = $_SESSION['redirect_url'] ?? $app_info['app_url'] ?? '/';
    echo "<script>
        alert('Looks like you’re already logged in! Redirecting to your dashboard...');
        window.location.href = '$redirect_url';
    </script>";
    exit();
}

// 🧩 Validate buwana_id
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

// 🧠 Fetch user info
$first_name = 'User';
$sql = "SELECT first_name FROM users_tb WHERE buwana_id = ?";
$stmt = $buwana_conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();
}


$response = ['success' => false];
$buwana_id = $_GET['id'] ?? null;
$ghost_member_id = '';

// Initialize user variables
$credential_type = '';
$credential_key = '';
$first_name = '';
$account_status = '';
$country_icon = '';
// Global variable to store the user's subscribed newsletters
$subscribed_newsletters = [];


// Include database connection
//include '../buwanaconn_env.php';
//include '../gobrikconn_env.php';
require_once ("../scripts/earthen_subscribe_functions.php");

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



// Check subscription status
$is_subscribed = false;
$earthen_subscriptions = ''; // To store newsletter names if subscribed
if (!empty($credential_key)) {
    // Call the function and capture the JSON response
    $api_response = checkEarthenEmailStatus($credential_key);

    // Parse the API response
    $response_data = json_decode($api_response, true);

    // Check if the response is valid JSON and handle accordingly
    if (json_last_error() === JSON_ERROR_NONE && isset($response_data['status']) && $response_data['status'] === 'success') {
        if ($response_data['registered'] === 1) {
            $is_subscribed = true;
            // Join newsletter names with commas for display
            $earthen_subscriptions = implode(', ', $subscribed_newsletters);
        }
    } else {
        // Handle invalid JSON or other errors
        echo '<script>console.error("Invalid JSON response or error: ' . htmlspecialchars($response_data['message'] ?? 'Unknown error') . '");</script>';
    }
}

}

?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php require_once ("../includes/signup-5-inc.php");?>

<!--<div class="splash-title-block"></div>
    <div id="splash-bar"></div>-->

<!-- PAGE CONTENT -->
   <?php
   $page_key = str_replace('-', '_', $page); // e.g. 'signup-1' → 'signup_1'
   ?>

   <div id="top-page-image"
        class="top-page-image"
        data-light-img="<?= htmlspecialchars($app_info[$page_key . '_top_img_light']) ?>"
        data-dark-img="<?= htmlspecialchars($app_info[$page_key . '_top_img_dark']) ?>">
   </div>


<div id="form-submission-box" class="landing-page-form">
    <div class="form-container">
        <div style="text-align:center;width:100%;margin:auto;">
            <h2 data-lang-id="001-select-subs">Select Earthen Subscriptions</h2>
            <h4 style="color:#748931;" data-lang-id="002-sub-subtitle">We share news by email.</h4>
            <p><span  data-lang-id="003-get-your">Our <a href="#" onclick="openAboutEarthen" class="underline-link"> Earthen newsletter</a> was first sent 10 years ago from the land of the Igorot people.  Its still free, but now we have different channels.  Later you can upgrade to a paid subscription to support the movement.</span>
            </p>
           <div id="subscribed" style="color:green;display:<?php echo $is_subscribed ? 'block' : 'none'; ?>;">
                <?php if ($is_subscribed && !empty($earthen_subscriptions)): ?>
                    <p style="color:green;font-size:1em;">👍 <span data-lang-id="005-nice">Nice! You're already subscribed to:</span> <?php echo htmlspecialchars($earthen_subscriptions); ?>.  <span data-lang-id="006-choose"> Choose to add or remove subscriptions below:</span></p>
                <?php endif; ?>
            </div>
            <div id="not-subscribed" style="color:grey;display:<?php echo !$is_subscribed ? 'block' : 'none'; ?>;" data-lang-id="007-not-subscribed"><?php echo $credential_key; ?>.<span data-lang-id="004-later-upgrade"> not yet subscribed to any Earthen newsletters yet.  All are free with upgrade options later.  Please select:</div>
            <div id="earthen-server-error" class="form-field-error"></div>


            <!-- SLECT SUBSCRIPTIONS FORM
            Last Step <?php echo $first_name; ?>...-->
                   <!-- SIGNUP FORM -->
            <!-- SIGNUP FORM -->
            <form id="user-signup-form" method="post" action="signup-5_process.php" style="margin-top:30px;">
                 <input type="hidden" name="buwana_id" value="<?php echo htmlspecialchars($buwana_id); ?>">
                <input type="hidden" name="credential_key" value="<?php echo htmlspecialchars($credential_key); ?>">
                <input type="hidden" name="subscribed_newsletters" value="<?php echo htmlspecialchars(json_encode($subscribed_newsletters)); ?>">
                <input type="hidden" name="ghost_member_id" value="<?php echo htmlspecialchars($ghost_member_id); ?>">
                <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>"> <!-- Added input for first_name -->

                <div class="subscription-boxes">
                    <!-- Subscription boxes will be populated here by the PHP function -->
                    <?php grabActiveEarthenSubs(); ?>
                </div>

                     <!-- Kick-Ass Submit Button -->
                <div id="submit-section" class="submit-button-wrapper">
                   <p data-lang-id="008c-your-activation-complete-2" style="text-align:center;margin-top:35px;">Your Buwana account activation is almost complete!  We saved the best part for last.</p>

                    <button type="submit" id="submit-button" class="kick-ass-submit">
                        <span id="submit-button-text" data-lang-id="015-next-button-x">Finalize ➡</span>
                        <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
                    </button>
                </div>

            <p class="form-caption" style="text-align:center; margin-top: 10px;font-size:0.9em;" data-lang-id="009-terms">Earthen newsletters and GoBrik are sent according to our non-profit, privacy <a href="#" onclick="openBuwanaTerms" class="underline-link"> Terms of Service</a>.</p>

            </form>
        </div>
    </div>
</div>



<div id="browser-back-link" style="font-size: medium; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;">
    <p><a href="#" onclick="browserBack(event)" data-lang-id="000-go-back">↩ Go back one</a></p>
</div>


</div> <!--CLoses main-->

<!-- FOOTER STARTS HERE -->
<?php require_once ("../footer-2025.php"); ?>

<script>



document.addEventListener('DOMContentLoaded', function () {
    const subBoxes = document.querySelectorAll('.sub-box');

    subBoxes.forEach(box => {
        const checkbox = box.querySelector('.sub-checkbox');

        // Toggle checkbox when box is clicked
        box.addEventListener('click', function (event) {
            if (event.target !== checkbox && event.target.className !== 'checkbox-label') {
                checkbox.checked = !checkbox.checked;
            }
            updateBoxStyle(box, checkbox.checked);
        });

        // Update style on checkbox change
        checkbox.addEventListener('change', function () {
            updateBoxStyle(box, checkbox.checked);
        });
    });

    function updateBoxStyle(box, isSelected) {
        if (isSelected) {
            box.style.border = '2px solid green';
            box.style.backgroundColor = 'var(--darker)';
        } else {
            box.style.border = '1px solid rgba(128, 128, 128, 0.5)';
            box.style.backgroundColor = 'transparent';
        }
    }
});



function enhanceNewsletterInfo() {
    // Define the newsletters and their corresponding updates
    const updates = {
        'gea-trainers': 'English | monthly',
        'gea-trainer-newsletter-indonesian': 'Bahasa Indonesia | setiap bulan',
        'updates-by-russell': 'English | monthly',
        'gobrik-news-updates': 'English | monthly',
        'default-newsletter': 'English | monthly'
    };

    // Loop through each update and modify the inner HTML of the matching newsletter divs
    Object.keys(updates).forEach(newsletter => {
        const element = document.querySelector(`#${newsletter} .sub-lang`);
        if (element) {
            element.innerHTML = updates[newsletter];
        }
    });
}

// Call the function to apply the updates
enhanceNewsletterInfo();




function openAboutEarthen() {
    const content = `
        <div style="text-align: center;margin:auto;padding:10%;">
            <div class="bioregions-top" style="width:375px;height:155px;margin:margin:auto auto -10px auto"></div>
            <h2 data-lang-id="013-watershed-title">Watersheds</h2>
            <p data-lang-id="014-watershed-description">A watershed is an area defined by the drainage of rain, melting snow, or ice converging to a single point, typically a river, lake, or ocean. These basins form natural boundaried bioregions, usually demarked by the crests of hills or mountains. Watersheds play a crucial ecological role and provide water for human use.</p>
            <h2>💦</h2>
        </div>
    `;
    openModal(content);
}


function openBuwanaTerms() {
    const content = `
        <div style="text-align: center;margin:auto;padding:10%;">
            <div class="bioregions-top" style="width:375px;height:155px;margin:margin:auto auto -10px auto"></div>
            <h2 data-lang-id="013-watershed-title">Watersheds</h2>
            <p data-lang-id="014-watershed-description">A watershed is an area defined by the drainage of rain, melting snow, or ice converging to a single point, typically a river, lake, or ocean. These basins form natural boundaried bioregions, usually demarked by the crests of hills or mountains. Watersheds play a crucial ecological role and provide water for human use.</p>
            <h2>💦</h2>
        </div>
    `;
    openModal(content);
}



</script>

<?php require_once ("../scripts/app_modals.php");?>


</body>
</html>
