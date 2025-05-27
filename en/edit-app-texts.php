<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'edit-app-texts';
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
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div class="top-wrapper">
      <div class="login-status"><?= htmlspecialchars($earthling_emoji) ?> Logged in as <?= htmlspecialchars($first_name) ?></div>
      <div>
        <div class="page-name">Edit App Texts: <?= htmlspecialchars($app['app_display_name']) ?></div>
        <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
      </div>
    </div>
    <form method="post" style="margin-top:20px;">
      <label>App Slogan<br>
        <input type="text" name="app_slogan" value="<?= htmlspecialchars($app['app_slogan']) ?>">
      </label><br><br>
      <label>Terms Text<br>
        <textarea name="app_terms_txt" rows="3" cols="40"><?= htmlspecialchars($app['app_terms_txt']) ?></textarea>
      </label><br><br>
      <label>Privacy Text<br>
        <textarea name="app_privacy_txt" rows="3" cols="40"><?= htmlspecialchars($app['app_privacy_txt']) ?></textarea>
      </label><br><br>
      <label>Emojis Array<br>
        <input type="text" name="app_emojis_array" value="<?= htmlspecialchars($app['app_emojis_array']) ?>">
      </label><br><br>
      <button type="submit" name="update_app">Save Changes</button>
    </form>
  </div>
</div>
</div> <!-- closes main -->
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
