<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'landing';
$version = '0.777';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// 🧩 Validate buwana_id
$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

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

// 🔗 Get app info
$app_display_name = $app_info['app_display_name'] ?? 'Your App';
$client_id = $app_info['client_id'] ?? null;
$client_id = $app_info['client_id'] ?? null;
$redirect_url = $client_id
    ? "login.php?id=" . urlencode($buwana_id) . "&app=" . urlencode($client_id) . "&status=firsttime"
    : '/';

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">

  <?php require_once ("../includes/signup-7-inc.php");?>

<div class="splash-title-block"></div>
<div id="splash-bar"></div>
<div id="top-page-image" class="top-page-image"></div>

<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div style="text-align:center;width:100%;margin:auto;">
      <div class="emoji-banner" style="text-align:center;font-size:5em;">
        <?= htmlspecialchars($earthling_emoji) ?>
      </div>
      <h1>
        <span data-lang-id="001-hurray">Hurray</span> <?= htmlspecialchars($first_name) ?>!
      </h1>
      <h4 data-lang-id="002-your-buwana-create">Your Buwana account has been created.</h4>
      <p>
        <span data-lang-id="003-you-will-be">You'll be redirected to login to </span><?= htmlspecialchars($app_display_name) ?><span data-lang-id="004-after">after</span><span id="countdown">3</span><span data-lang-id="005-seconds">seconds</span>.
      </p>


    </div>
  </div>
  <div id="browser-back-link" style="font-size: small; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;">
      <p><span data-lang-id="006-manual-redirect">If you're not redirected automatically,</span><a href="<?= htmlspecialchars($redirect_url) ?>"><span data-lang-id="007-click-here">click here</span></a>.
      </p>
  </div>
</div>


</div>

<?php require_once ("../footer-2025.php"); ?>

</body>
</html>
