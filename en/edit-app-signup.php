<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'edit-app-signup';
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
    $signup_top_img_url       = $_POST['signup_top_img_url'] ?? '';
    $signup_top_img_dark_url  = $_POST['signup_top_img_dark_url'] ?? '';
    $signup_1_top_img_light   = $_POST['signup_1_top_img_light'] ?? '';
    $signup_1_top_img_dark    = $_POST['signup_1_top_img_dark'] ?? '';
    $signup_2_top_img_light   = $_POST['signup_2_top_img_light'] ?? '';
    $signup_2_top_img_dark    = $_POST['signup_2_top_img_dark'] ?? '';
    $signup_3_top_img_light   = $_POST['signup_3_top_img_light'] ?? '';
    $signup_3_top_img_dark    = $_POST['signup_3_top_img_dark'] ?? '';
    $signup_4_top_img_light   = $_POST['signup_4_top_img_light'] ?? '';
    $signup_4_top_img_dark    = $_POST['signup_4_top_img_dark'] ?? '';
    $signup_5_top_img_light   = $_POST['signup_5_top_img_light'] ?? '';
    $signup_5_top_img_dark    = $_POST['signup_5_top_img_dark'] ?? '';
    $signup_6_top_img_light   = $_POST['signup_6_top_img_light'] ?? '';
    $signup_6_top_img_dark    = $_POST['signup_6_top_img_dark'] ?? '';
    $signup_7_top_img_light   = $_POST['signup_7_top_img_light'] ?? '';
    $signup_7_top_img_dark    = $_POST['signup_7_top_img_dark'] ?? '';
    $login_top_img_light      = $_POST['login_top_img_light'] ?? '';
    $login_top_img_dark       = $_POST['login_top_img_dark'] ?? '';

    $sql = "UPDATE apps_tb SET signup_top_img_url=?, signup_top_img_dark_url=?, signup_1_top_img_light=?, signup_1_top_img_dark=?, signup_2_top_img_light=?, signup_2_top_img_dark=?, signup_3_top_img_light=?, signup_3_top_img_dark=?, signup_4_top_img_light=?, signup_4_top_img_dark=?, signup_5_top_img_light=?, signup_5_top_img_dark=?, signup_6_top_img_light=?, signup_6_top_img_dark=?, signup_7_top_img_light=?, signup_7_top_img_dark=?, login_top_img_light=?, login_top_img_dark=? WHERE app_id=? AND owner_buwana_id=?";
    $stmt = $buwana_conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssssssssssssssssssii', $signup_top_img_url, $signup_top_img_dark_url, $signup_1_top_img_light, $signup_1_top_img_dark, $signup_2_top_img_light, $signup_2_top_img_dark, $signup_3_top_img_light, $signup_3_top_img_dark, $signup_4_top_img_light, $signup_4_top_img_dark, $signup_5_top_img_light, $signup_5_top_img_dark, $signup_6_top_img_light, $signup_6_top_img_dark, $signup_7_top_img_light, $signup_7_top_img_dark, $login_top_img_light, $login_top_img_dark, $app_id, $buwana_id);
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
        <div class="page-name">Edit Signup Graphics: <?= htmlspecialchars($app['app_display_name']) ?></div>
        <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
      </div>
    </div>
    <div class="breadcrumb">
      <a href="dashboard.php">Dashboard</a> &gt; 
      <a href="app-view.php?app_id=<?= intval($app_id) ?>">Manage <?= htmlspecialchars($app['app_display_name']) ?></a> &gt; 
      Edit Signup Graphics
    </div>
    <form method="post" style="margin-top:20px;">
      <label>Signup Banner Light<br>
        <input type="text" name="signup_top_img_url" value="<?= htmlspecialchars($app['signup_top_img_url']) ?>">
      </label><br><br>
      <label>Signup Banner Dark<br>
        <input type="text" name="signup_top_img_dark_url" value="<?= htmlspecialchars($app['signup_top_img_dark_url']) ?>">
      </label><br><br>
      <label>Signup 1 Light<br>
        <input type="text" name="signup_1_top_img_light" value="<?= htmlspecialchars($app['signup_1_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 1 Dark<br>
        <input type="text" name="signup_1_top_img_dark" value="<?= htmlspecialchars($app['signup_1_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 2 Light<br>
        <input type="text" name="signup_2_top_img_light" value="<?= htmlspecialchars($app['signup_2_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 2 Dark<br>
        <input type="text" name="signup_2_top_img_dark" value="<?= htmlspecialchars($app['signup_2_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 3 Light<br>
        <input type="text" name="signup_3_top_img_light" value="<?= htmlspecialchars($app['signup_3_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 3 Dark<br>
        <input type="text" name="signup_3_top_img_dark" value="<?= htmlspecialchars($app['signup_3_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 4 Light<br>
        <input type="text" name="signup_4_top_img_light" value="<?= htmlspecialchars($app['signup_4_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 4 Dark<br>
        <input type="text" name="signup_4_top_img_dark" value="<?= htmlspecialchars($app['signup_4_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 5 Light<br>
        <input type="text" name="signup_5_top_img_light" value="<?= htmlspecialchars($app['signup_5_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 5 Dark<br>
        <input type="text" name="signup_5_top_img_dark" value="<?= htmlspecialchars($app['signup_5_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 6 Light<br>
        <input type="text" name="signup_6_top_img_light" value="<?= htmlspecialchars($app['signup_6_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 6 Dark<br>
        <input type="text" name="signup_6_top_img_dark" value="<?= htmlspecialchars($app['signup_6_top_img_dark']) ?>">
      </label><br><br>
      <label>Signup 7 Light<br>
        <input type="text" name="signup_7_top_img_light" value="<?= htmlspecialchars($app['signup_7_top_img_light']) ?>">
      </label><br><br>
      <label>Signup 7 Dark<br>
        <input type="text" name="signup_7_top_img_dark" value="<?= htmlspecialchars($app['signup_7_top_img_dark']) ?>">
      </label><br><br>
      <label>Login Banner Light<br>
        <input type="text" name="login_top_img_light" value="<?= htmlspecialchars($app['login_top_img_light']) ?>">
      </label><br><br>
      <label>Login Banner Dark<br>
        <input type="text" name="login_top_img_dark" value="<?= htmlspecialchars($app['login_top_img_dark']) ?>">
      </label><br><br>
      <button type="submit" name="update_app">Save Changes</button>
    </form>
  </div>
</div>
</div> <!-- closes main -->
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
