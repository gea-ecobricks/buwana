<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'edit-app-core';
$version = '0.1';
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
    $redirect_uris     = $_POST['redirect_uris'] ?? '';
    $app_login_url     = $_POST['app_login_url'] ?? '';
    $scopes            = $_POST['scopes'] ?? '';
    $app_domain        = $_POST['app_domain'] ?? '';
    $app_url           = $_POST['app_url'] ?? '';
    $app_dashboard_url = $_POST['app_dashboard_url'] ?? '';
    $app_description   = $_POST['app_description'] ?? '';
    $app_version       = $_POST['app_version'] ?? '';
    $app_display_name  = $_POST['app_display_name'] ?? '';
    $contact_email     = $_POST['contact_email'] ?? '';

    $sql = "UPDATE apps_tb SET redirect_uris=?, app_login_url=?, scopes=?, app_domain=?, app_url=?, app_dashboard_url=?, app_description=?, app_version=?, app_display_name=?, contact_email=? WHERE app_id=? AND owner_buwana_id=?";
    $stmt = $buwana_conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssssssii', $redirect_uris, $app_login_url, $scopes, $app_domain, $app_url, $app_dashboard_url, $app_description, $app_version, $app_display_name, $contact_email, $app_id, $buwana_id);
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
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div class="top-wrapper">
      <div>
        <div class="login-status"><?= htmlspecialchars($earthling_emoji) ?> Logged in as <?= htmlspecialchars($first_name) ?></div>
        <div style="font-size:0.9em;color:grey;">
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
            <?= htmlspecialchars($app['app_display_name']) ?> ⚪ Signups Off
          <?php endif; ?>
        </div>
      </div>
      <div style="display:flex;align-items:center;margin-left:auto;">
        <div style="text-align:right;margin-right:10px;">
          <div class="page-name"><?= htmlspecialchars($app['app_display_name']) ?></div>
          <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
        </div>
        <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon" title="<?= htmlspecialchars($app['app_display_name']) ?>" width="50" height="50">
      </div>
    </div>
    <h1 data-lang-id="000-edit-core-date">ℹ️ Edit Core Data</h1>
    <p>Set the core parameters for your <?= htmlspecialchars($app['app_display_name']) ?> app.  These will set the base display and functionality for signing up, logins, redirects and log outs.</p>
    <form id="edit-core-form" method="post" style="margin-top:20px;">
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <textarea id="redirect_uris" name="redirect_uris" aria-label="Redirect URIs" maxlength="255" required placeholder=" " rows="2"><?= htmlspecialchars($app['redirect_uris']) ?></textarea>
        <label for="redirect_uris">Redirect URIs</label>
        <p class="form-caption" data-lang-id="011c-redirect">Allowed OAuth redirect URIs, comma separated</p>
        <div id="redirect_uris-error-required" class="form-field-error">This field is required.</div>
        <div id="redirect_uris-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="redirect_uris-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_login_url" name="app_login_url" aria-label="App Login URL" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_login_url']) ?>">
        <label for="app_login_url">App Login URL</label>
        <p class="form-caption" data-lang-id="011b-required">This is where we'll direct users to login to your app (i.e. after signup)</p>

        <div id="app_login_url-error-required" class="form-field-error">This field is required.</div>
        <div id="app_login_url-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_login_url-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="scopes" name="scopes" aria-label="Scopes" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['scopes']) ?>">
        <label for="scopes">Scopes</label>
        <p class="form-caption" data-lang-id="011c-scopes">OAuth scopes requested by your app</p>
        <div id="scopes-error-required" class="form-field-error">This field is required.</div>
        <div id="scopes-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="scopes-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_domain" name="app_domain" aria-label="App Domain" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_domain']) ?>">
        <label for="app_domain">App Domain</label>
        <p class="form-caption" data-lang-id="011c-domain">Your primary domain name</p>
        <div id="app_domain-error-required" class="form-field-error">This field is required.</div>
        <div id="app_domain-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_domain-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_url" name="app_url" aria-label="App URL" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_url']) ?>">
        <label for="app_url">App URL</label>
        <p class="form-caption" data-lang-id="011c-app-url">Public homepage of your app</p>
        <div id="app_url-error-required" class="form-field-error">This field is required.</div>
        <div id="app_url-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_url-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_dashboard_url" name="app_dashboard_url" aria-label="App Dashboard URL" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_dashboard_url']) ?>">
        <label for="app_dashboard_url">App Dashboard URL</label>
        <p class="form-caption" data-lang-id="011c-dashboard">Where users manage their account</p>
        <div id="app_dashboard_url-error-required" class="form-field-error">This field is required.</div>
        <div id="app_dashboard_url-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_dashboard_url-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <textarea id="app_description" name="app_description" aria-label="Description" maxlength="255" required placeholder=" " rows="3"><?= htmlspecialchars($app['app_description']) ?></textarea>
        <label for="app_description">Description</label>
        <p class="form-caption" data-lang-id="011c-description">Short summary of your app</p>
        <div id="app_description-error-required" class="form-field-error">This field is required.</div>
        <div id="app_description-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_description-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_version" name="app_version" aria-label="Version" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_version']) ?>">
        <label for="app_version">Version</label>
        <p class="form-caption" data-lang-id="011c-version">Current version of the app</p>
        <div id="app_version-error-required" class="form-field-error">This field is required.</div>
        <div id="app_version-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_version-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="text" id="app_display_name" name="app_display_name" aria-label="Display Name" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['app_display_name']) ?>">
        <label for="app_display_name">Display Name</label>
        <p class="form-caption" data-lang-id="011c-display">Name shown to users</p>
        <div id="app_display_name-error-required" class="form-field-error">This field is required.</div>
        <div id="app_display_name-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="app_display_name-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
      </div>
      <div class="form-item float-label-group" style="border-radius:10px 10px 5px 5px;padding-bottom: 10px;">
        <input type="email" id="contact_email" name="contact_email" aria-label="Contact Email" maxlength="255" required placeholder=" " value="<?= htmlspecialchars($app['contact_email']) ?>">
        <label for="contact_email">Contact Email</label>
        <p class="form-caption" data-lang-id="011c-contact">Where we can reach you</p>
        <div id="contact_email-error-required" class="form-field-error">This field is required.</div>
        <div id="contact_email-error-long" class="form-field-error">The entry is too long. Max 255 characters.</div>
        <div id="contact_email-error-invalid" class="form-field-error">The entry contains invalid characters. Avoid quotes, slashes, and greater-than signs please.</div>
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
  const form = document.getElementById('edit-core-form');
  const fields = ['redirect_uris','app_login_url','scopes','app_domain','app_url','app_dashboard_url','app_description','app_version','app_display_name','contact_email'];

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
