<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

// Page setup
$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'signup-7';
$version = '0.776';
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
$redirect_url = $app_info['app_login_url'] ?? '/';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">

  <!--
  GoBrik.com site version 3.0
  Developed and made open source by the Global Ecobrick Alliance
  See our git hub repository for the full code and to help out:
  https://github.com/gea-ecobricks/gobrik-3.0/tree/main/en-->

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
      <h2 data-lang-id="001-your-account-created">
        Hurray <?= htmlspecialchars($first_name) ?>!
      </h2>
      <h4>Your Buwana account has been created.</h4>
      <p data-lang-id="002-redirecting-msg">
        In <span id="countdown">3</span> seconds you'll be redirected to <strong><?= htmlspecialchars($app_display_name) ?></strong> to log in.
      </p>


    </div>
  </div>
  <div id="browser-back-link" style="font-size: small; text-align: center; margin: auto; align-self: center; padding-top: 40px; padding-bottom: 40px; margin-top: 0px;">
      <p data-lang-id="003-manual-redirect">
                If you're not redirected automatically,
                <a href="<?= htmlspecialchars($redirect_url) ?>">click here</a>.
      </p>
  </div>
</div>


</div>

<?php require_once ("../footer-2025.php"); ?>

<script>
  setTimeout(() => {
    window.location.href = <?= json_encode($redirect_url) ?>;
  }, 25000);
</script>


<script>
  let seconds = 25;
  const countdownEl = document.getElementById('countdown');

  const countdown = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
      clearInterval(countdown);
    } else {
      countdownEl.textContent = seconds;
    }
  }, 1000);

  setTimeout(() => {
    window.location.href = <?= json_encode($redirect_url) ?>;
  }, 5000);




  // 👀 Injected emoji array for the app
  window.appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]'), JSON_UNESCAPED_UNICODE) ?>;

  // 🔁 Continuous emoji spinner for signup-7.php
  function runSignup7EmojiSpinner(selector) {
    const emojiContainer = document.querySelector(selector);
    if (!emojiContainer || !window.appEmojis || !window.appEmojis.length) return;

    const emojis = window.appEmojis;
    let index = 0;

    setTimeout(() => {
      setInterval(() => {
        emojiContainer.textContent = emojis[index];
        emojiContainer.style.opacity = 1;

        // Optional: fade-out effect
        setTimeout(() => {
          emojiContainer.style.opacity = 0.7;
        }, 200); // fade slightly after 200ms

        index = (index + 1) % emojis.length; // loop back
      }, 280); // ⏱ 30% faster (was 400ms)
    }, 500); // Initial delay
  }

  runSignup7EmojiSpinner('.emoji-banner');
</script>


</body>
</html>
