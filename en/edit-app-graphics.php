<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'edit-app-graphics';
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
    $app_logo_url        = $_POST['app_logo_url'] ?? '';
    $app_logo_dark_url   = $_POST['app_logo_dark_url'] ?? '';
    $app_square_icon_url = $_POST['app_square_icon_url'] ?? '';
    $app_wordmark_url    = $_POST['app_wordmark_url'] ?? '';
    $app_wordmark_dark_url = $_POST['app_wordmark_dark_url'] ?? '';

    $sql = "UPDATE apps_tb SET app_logo_url=?, app_logo_dark_url=?, app_square_icon_url=?, app_wordmark_url=?, app_wordmark_dark_url=? WHERE app_id=? AND owner_buwana_id=?";
    $stmt = $buwana_conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssi', $app_logo_url, $app_logo_dark_url, $app_square_icon_url, $app_wordmark_url, $app_wordmark_dark_url, $app_id, $buwana_id);
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
        <div class="page-name">Edit App Graphics: <?= htmlspecialchars($app['app_display_name']) ?></div>
        <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
      </div>
    </div>
    <form method="post" style="margin-top:20px;">
      <label>Logo URL (light)<br>
        <input type="text" name="app_logo_url" value="<?= htmlspecialchars($app['app_logo_url']) ?>">
      </label><br><br>
      <label>Logo URL (dark)<br>
        <input type="text" name="app_logo_dark_url" value="<?= htmlspecialchars($app['app_logo_dark_url']) ?>">
      </label><br><br>
      <label>Square Icon URL<br>
        <input type="text" name="app_square_icon_url" value="<?= htmlspecialchars($app['app_square_icon_url']) ?>">
      </label><br><br>
      <label>Wordmark URL (light)<br>
        <input type="text" name="app_wordmark_url" value="<?= htmlspecialchars($app['app_wordmark_url']) ?>">
      </label><br><br>
      <label>Wordmark URL (dark)<br>
        <input type="text" name="app_wordmark_dark_url" value="<?= htmlspecialchars($app['app_wordmark_dark_url']) ?>">
      </label><br><br>
      <button type="submit" name="update_app">Save Changes</button>
    </form>
  </div>
</div>
</div> <!-- closes main -->
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
