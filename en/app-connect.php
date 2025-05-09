<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'app-connect';
$version = '0.777';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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
$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji FROM users_tb WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $earthling_emoji);
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

#right-arrow-connect-icon::before {
  content: '➤';
  font-size: 3rem;
  color: limegreen;
  animation: pulseArrow 1.8s ease-in-out infinite;
  display: inline-block;
  padding: 0 20px;
}

@keyframes pulseArrow {
  0%, 100% {
    transform: scale(1);
    opacity: 0.8;
  }
  50% {
    transform: scale(1.25);
    opacity: 1;
  }
}


.the-app-logo {
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  width: 175px;
  height: 100px;

}

</STYLE>

<?php require_once ("../header-2025.php");?>  <!-- this file closes HEAD-->

<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div style="text-align:center;width:100%;margin:auto;">
      <h1>
        <span data-lang-id="001-first-time-to-connect">Connect to</span> <?= htmlspecialchars($app_info['app_display_name']) ?>
      </h1>
      <p>
          <?= htmlspecialchars($first_name) ?>, <span data-lang-id="002-are-you-sure"> it looks like you are trying to login to <?= htmlspecialchars($app_info['app_display_name']) ?> for the first time!  Nice.</span>
      </p>
      <div id="app-connect-relationship" style="display:flex; flex-direction: row; align-items: center; justify-content: center; gap: 20px; margin: 20px auto;">
          <div class="emoji-banner" style="text-align:center;font-size:5em;">
              <?= htmlspecialchars($earthling_emoji) ?>
          </div>
          <div class="right-arrow-connect-icon"></div>
             <div class="the-app-logo"
                  alt="<?= htmlspecialchars($app_info['app_display_name']) ?> App Logo"
                  title="<?= htmlspecialchars($app_info['app_display_name']) ?> <?= htmlspecialchars($app_info['app_version']) ?> | <?= htmlspecialchars($app_info['app_slogan']) ?>"
                  data-light-logo="<?= htmlspecialchars($app_info['app_logo_url']) ?>"
                  data-dark-logo="<?= htmlspecialchars($app_info['app_logo_dark_url']) ?>">
             </div>
      </div>

       <p>
            <span data-lang-id="003-if-so">If so, the </span><?= htmlspecialchars($app_info['app_display_name']) ?><span data-lang-id="004-will-be-granted"> will be granted access to your Buwana account so that you can login in make use of its regenerative functionality.</span>.
       </p>

        <form id="user-signup-form" method="post" action="app-connect_process.php" novalidate>
            <input type="hidden" name="buwana_id" value="<?= htmlspecialchars($buwana_id) ?>">
            <input type="hidden" name="client_id" value="<?= htmlspecialchars($app_info['client_id']) ?>">
 <!-- Kick-Ass Submit Button -->
            <div id="submit-section" class="submit-button-wrapper">


                <button type="submit" id="submit-button" class="kick-ass-submit">
                    <span id="submit-button-text" data-lang-id="009-connect-button">Connect ↔</span>
                    <span id="submit-emoji" class="submit-emoji" style="display: none;"></span>
                </button>
            </div>

            <p class="form-caption" style="text-align:center; margin-top: 10px;font-size:0.9em;">By connecting you agree to the <span  data-lang-id="010-terms"></span><a href="#" onclick="openTermsModal(); return false;"><span><?= htmlspecialchars($app_info['app_display_name']) ?></span><span data-lang-id="1000-terms-of-use" style="margin-left: 6px;margin-right:auto;text-align:left !important">Terms of Use</span></a></p>
        </form>
    </div>
  </div>
  <div id="browser-back-link" style="font-size: small; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;">
      <p><span data-lang-id="006-manual-redirect">If you don't want to connect, no problem!  Return to the </span><a href="<?= htmlspecialchars($app_info['app_url']) ?>"><?= htmlspecialchars($app_info['app_display_name']) ?><span data-lang-id="000-home">home</span></a>.
      </p>
  </div>
</div>


</div>

<?php require_once ("../footer-2025.php"); ?>

</body>
</html>
