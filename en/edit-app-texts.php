<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'edit-app-texts';
$version = '0.11';
$lastModified = date('Y-m-d\TH:i:s\Z', filemtime(__FILE__));

if (empty($_SESSION['buwana_id'])) {
    header('Location: login.php');
    exit();
}

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
$buwana_id = intval($_SESSION['buwana_id']);

$first_name = '';
$earthling_emoji = '';
$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji FROM users_tb WHERE buwana_id = ?");
if ($stmt) {
    $stmt->bind_param('i', $buwana_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $earthling_emoji);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_app'])) {
    $app_slogan       = $_POST['app_slogan'] ?? '';
    $app_terms_txt    = $_POST['app_terms_txt'] ?? '';
    $app_privacy_txt  = $_POST['app_privacy_txt'] ?? '';
    $app_emojis_array = $_POST['app_emojis_array'] ?? '';

    $sql = "UPDATE apps_tb SET app_slogan=?, app_terms_txt=?, app_privacy_txt=?, app_emojis_array=? WHERE app_id=? AND owner_buwana_id=?";
    $stmt = $buwana_conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssii', $app_slogan, $app_terms_txt, $app_privacy_txt, $app_emojis_array, $app_id, $buwana_id);
        $stmt->execute();
        $stmt->close();
    }
}

$stmt = $buwana_conn->prepare("SELECT * FROM apps_tb WHERE app_id = ? AND owner_buwana_id = ?");
$stmt->bind_param('ii', $app_id, $buwana_id);
$stmt->execute();
$result = $stmt->get_result();
$app = $result ? $result->fetch_assoc() : [];
$stmt->close();

if (!$app) {
    echo "<p>App not found or access denied.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/app-view-en.php"); ?>
    <?php require_once("../includes/dashboard-inc.php"); ?>
    <style>
      .top-wrapper {
        background: var(--darker-lighter);
      }
    </style>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div class="top-wrapper">
      <div>
        <div class="login-status"><?= htmlspecialchars($earthling_emoji) ?> Logged in as <?= htmlspecialchars($first_name) ?></div>
        <div style="font-size:0.9em;color:grey;margin-bottom: auto;">
          <?php if($app['is_active']): ?>
            🟢 <?= htmlspecialchars($app['app_display_name']) ?> is active
          <?php else: ?>
            ⚪ <?= htmlspecialchars($app['app_display_name']) ?> is not active
          <?php endif; ?>
        </div>
        <div style="font-size:0.9em;color:grey;">
          <?php if($app['allow_signup']): ?>
            🟢 <?= htmlspecialchars($app['app_display_name']) ?> signups enabled
          <?php else: ?>
            ⚪ <?= htmlspecialchars($app['app_display_name']) ?> signups off
          <?php endif; ?>
        </div>
      </div>
      <div style="display:flex;flex-flow:column;margin-left:auto;">
          <div style="display:flex;align-items:center;margin-left:auto;">

                <div style="text-align:right;margin-right:10px;">
                  <div class="page-name">Edit App Texts: <?= htmlspecialchars($app['app_display_name']) ?></div>
                  <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
                </div>
                <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon" title="<?= htmlspecialchars($app['app_display_name']) ?>" width="60" height="60">
          </div>
            <div class="breadcrumb" style="margin-left:auto;">
                          <a href="dashboard.php">Dashboard</a> &gt;
                          <a href="app-view.php?app_id=<?= intval($app_id) ?>">Manage <?= htmlspecialchars($app['app_display_name']) ?></a> &gt;
                          Edit Texts
                        </div>
      </div>

    </div>
    <form id="edit-texts-form" method="post" style="margin-top:20px;">
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_slogan" name="app_slogan" aria-label="App Slogan" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_slogan']) ?>">
        <label for="app_slogan">App Slogan</label>
        <p class="form-caption">Short tagline for your app</p>
        <div id="app_slogan-error-required" class="form-field-error">This field is required.</div>
        <div id="app_slogan-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_slogan-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <textarea id="app_terms_txt" name="app_terms_txt" aria-label="Terms Text" maxlength="255" required placeholder=" " rows="12"><?= htmlspecialchars($app['app_terms_txt']) ?></textarea>
        <label for="app_terms_txt">Terms Text</label>
        <p class="form-caption">Short version of your terms</p>
        <div id="app_terms_txt-error-required" class="form-field-error">This field is required.</div>
        <div id="app_terms_txt-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_terms_txt-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <textarea id="app_privacy_txt" name="app_privacy_txt" aria-label="Privacy Text" maxlength="255" required placeholder=" " rows="12"><?= htmlspecialchars($app['app_privacy_txt']) ?></textarea>
        <label for="app_privacy_txt">Privacy Text</label>
        <p class="form-caption">Short privacy notice</p>
        <div id="app_privacy_txt-error-required" class="form-field-error">This field is required.</div>
        <div id="app_privacy_txt-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_privacy_txt-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="textarea" id="app_emojis_array" name="app_emojis_array" aria-label="Emojis Array" maxlength="255" rows="6"required placeholder=" " value="<?= htmlspecialchars($app['app_emojis_array']) ?>">
        <label for="app_emojis_array">Emojis Array</label>
        <p class="form-caption">Emoji list for your app</p>
        <div id="app_emojis_array-error-required" class="form-field-error">This field is required.</div>
        <div id="app_emojis_array-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_emojis_array-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="submit-button-wrapper">
        <button type="submit" id="submit-button" name="update_app" class="kick-ass-submit">
          <span id="submit-button-text">Save Changes</span>
          <span id="submit-emoji" class="submit-emoji" style="display:none;"></span>
        </button>
      </div>
    </form>
  </div>
</div>
</div> <!-- closes main -->
<?php require_once("../footer-2025.php"); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('edit-texts-form');
  const fields = ['app_slogan','app_terms_txt','app_privacy_txt','app_emojis_array'];

  function hasInvalidChars(value) {
    return /[\'"<>]/.test(value);
  }

  function toggleError(id, show) {
    const el = document.getElementById(id);
    if (el) el.style.display = show ? 'block' : 'none';
  }

  function validateField(name) {
    const value = document.getElementById(name).value.trim();
    let valid = true;
    toggleError(name + '-error-required', value === '');
    toggleError(name + '-error-long', value.length > 255);
    toggleError(name + '-error-invalid', hasInvalidChars(value));
    if (value === '' || value.length > 255 || hasInvalidChars(value)) {
      valid = false;
    }
    return valid;
  }

  fields.forEach(f => {
    const el = document.getElementById(f);
    if (el) {
      el.addEventListener('input', () => validateField(f));
    }
  });

  form.addEventListener('submit', function (e) {
    let allValid = true;
    fields.forEach(f => { if (!validateField(f)) allValid = false; });
    if (!allValid) {
      e.preventDefault();
    }
  });
});
</script>
</body>
</html>
