<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'app-connect';
$version = '0.7771';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'app-connect';
$version = '0.777';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// --- Validate inputs
$buwana_id = $_GET['id'] ?? null;
$client_id = $app_info['client_id'] ?? null;

// --- Conditional validation and JavaScript fallback handling
if (!$buwana_id && !$client_id) {
    echo "<script>alert('Sorry! Something went wrong. Please select your Buwana app and login again.'); window.location.href = 'index.php';</script>";
    exit();
}

if (!$buwana_id || !is_numeric($buwana_id)) {
    $safe_client_id = urlencode($client_id);
    echo "<script>alert(\"Sorry! We couldn't discern who is logging in. Please try again.\"); window.location.href = 'login.php?app=$safe_client_id';</script>";
    exit();
}

if (!$client_id) {
    $safe_buwana_id = urlencode($buwana_id);
    echo "<script>alert(\"Sorry! Seems like the target app wasn't set! Please select the app you want to use and try logging in again.\"); window.location.href = 'index.php?id=$safe_buwana_id';</script>";
    exit();
}

// 🔗 Get app info
$app_display_name = $app_info['app_display_name'] ?? 'Your App';
$redirect_url = $app_info['app_dashboard_url'] ?? '/';


// 🔍 Fetch user info
$first_name = 'User';
$earthling_emoji = '🌍';
$email = 'email';
$stmt = $buwana_conn->prepare("SELECT email, first_name, earthling_emoji FROM users_tb WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($email, $first_name, $earthling_emoji);
    $stmt->fetch();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">

<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>

.form-container {
padding-top: 10px !important;
}

.app-connect-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  margin: 30px auto;
  width: 90%;
  max-width: 500px;
}

.icon-box {
  width: 80px;
  height: 80px;
  border: 1px solid var(--subdued-text);
  background-color: var(--lighter);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.icon-box {
    width: 100px;
    height: 100px;
    border: 1px solid var(--subdued-text);
    background-color: var(--lighter);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.icon-box img {
    width: 80%;
    height: 80%;
    object-fit: contain;
}

.connect-arrow {
  position: relative;
  width: 50px;
  height: 50px;
  animation: chevronPulse 1.5s ease-in-out infinite;
  transform-origin: center;
  margin-left: -10px;
}

/* Chevron arms */
.connect-arrow::before,
.connect-arrow::after {
  content: '';
  position: absolute;
  width: 3px;
  height: 20px;
  background-color: green;
  transform-origin: bottom right;
}

.connect-arrow::before {
  top: 5px;
    left: 32px;
    transform: rotate(-55deg);
}

.connect-arrow::after {
  top: 5px;
    left: 32px;
    transform: rotate(-125deg);
}

/* Chevron pulse animation */
@keyframes chevronPulse {
  0% {
    transform: translateX(0) scaleY(1.1); /* Left + stretch */
  }
  30% {
    transform: translateX(10px) scaleY(0.9); /* Right + compress */
  }
  100% {
    transform: translateX(0) scaleY(1.1); /* Back to left + stretch */
  }
}





</STYLE>

<?php require_once ("../header-2025.php");?>  <!-- this file closes HEAD-->

<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div style="text-align:center;width:100%;margin:auto;">
        <div class="app-connect-container">
          <div class="icon-box earthling-icon">
            <?= htmlspecialchars($earthling_emoji) ?>
          </div>
          <div class="connect-arrow"></div>
          <div class="icon-box app-icon"
               title="<?= htmlspecialchars($app_info['app_display_name']) ?> <?= htmlspecialchars($app_info['app_version']) ?> | <?= htmlspecialchars($app_info['app_slogan']) ?>">
              <img src="<?= htmlspecialchars($app_info['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app_info['app_display_name']) ?> App Icon" />
          </div>

        </div>

      <h1>
        <span data-lang-id="001-first-time-to-connect">Connect to</span> <?= htmlspecialchars($app_info['app_display_name']) ?>
      </h1>
  <p style="text-align:center; margin-top: 10px;color:green;font-size:1em;">✅ <?= htmlspecialchars($app_info['app_display_name']) ?> is an authorized Buwana app</p>
      <h4>
          <?= htmlspecialchars($first_name) ?>, <span data-lang-id="002-are-you-sure"> it looks like you are trying to login to <?= htmlspecialchars($app_info['app_display_name']) ?> for the first time!  Nice.</span>
      </h4>


       <p style="margin-top:-15px;margin-bottom:20px;">
            <span data-lang-id="003-if-so">To do so, we must connect your Buwana account to </span><?= htmlspecialchars($app_info['app_display_name']) ?>. <span data-lang-id="004-will-be-granted"> In so doing you grant access to </span><?= htmlspecialchars($app_info['app_display_name']) ?> to your Buwana <?= htmlspecialchars($email) ?> credentials so that you can login and make use of the app.</span>.
       </p>



        <form id="user-signup-form" method="post" action="app-connect_process.php" novalidate>
            <input type="hidden" name="buwana_id" value="<?= htmlspecialchars($buwana_id) ?>">
            <input type="hidden" name="client_id" value="<?= htmlspecialchars($app_info['client_id']) ?>">
 <!-- Kick-Ass Submit Button -->
            <div id="submit-section" class="submit-button-wrapper">


                <button type="submit" id="submit-button" class="kick-ass-submit">
                    <span id="submit-button-text" data-lang-id="009-connect-button">Connect</span>
                    <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
                </button>
            </div>

            <div class="form-caption" >


            <p style="text-align:center; margin-top: 10px;font-size:0.9em;">By connecting you agree to the <a href="#" onclick="openTermsModal(); return false;"><span><?= htmlspecialchars($app_info['app_display_name']) ?></span> <span data-lang-id="1000-terms-of-use">Terms of Use</span></a></p>
            </div>
        </form>
    </div>
  </div>
  <div id="browser-back-link" style="font-size: small; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: -20px;">
      <p style="line-height:1.3em;"><span data-lang-id="006-no-connect"> ↩ Or... return to the </span><a href="<?= htmlspecialchars($app_info['app_url']) ?>"><?= htmlspecialchars($app_info['app_display_name']) ?> <span data-lang-id="000-home">home</span></a>
      </p>
  </div>
</div>


</div>

<?php require_once ("../footer-2025.php"); ?>

<?php require_once ("../scripts/app_modals.php");?>

</body>
</html>
