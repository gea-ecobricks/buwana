<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'dashboard';
$version = '0.1';
$lastModified = date('Y-m-d\TH:i:s\Z', filemtime(__FILE__));

if (empty($_SESSION['buwana_id'])) {
    header('Location: login.php');
    exit();
}

$buwana_id = intval($_SESSION['buwana_id']);
$first_name = '';
$earthling_emoji = '';
$stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji FROM users_tb WHERE buwana_id = ?");
$stmt->bind_param('i', $buwana_id);
$stmt->execute();
$stmt->bind_result($first_name, $earthling_emoji);
$stmt->fetch();
$stmt->close();

$sql = "SELECT a.app_id, a.client_id, a.app_display_name, a.app_description, a.app_square_icon_url,
               (SELECT COUNT(*) FROM user_app_connections_tb u WHERE u.client_id = a.client_id) AS user_count
        FROM apps_tb a
        WHERE a.owner_buwana_id = ? ORDER BY a.app_display_name";
$stmt = $buwana_conn->prepare($sql);
$stmt->bind_param('i', $buwana_id);
$stmt->execute();
$result = $stmt->get_result();
$apps = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

$new_account_count_for_user = 0;
$stmt = $buwana_conn->prepare("SELECT COUNT(*) FROM user_app_connections_tb u JOIN apps_tb a ON u.client_id = a.client_id WHERE a.owner_buwana_id = ? AND u.connected_at >= (NOW() - INTERVAL 1 DAY)");
$stmt->bind_param('i', $buwana_id);
$stmt->execute();
$stmt->bind_result($new_account_count_for_user);
$stmt->fetch();
$stmt->close();

$user_app_count = count($apps);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/dashboard-en.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require_once("../includes/dashboard-inc.php"); ?>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div class="top-wrapper">
      <div class="login-status"><?= htmlspecialchars($earthling_emoji) ?> Logged in as <?= htmlspecialchars($first_name) ?></div>
      <div class="page-name">App Manager Dashboard</div>
    </div>
    <div class="chart-container">
      <canvas id="growthChart"></canvas>
      <p class="chart-caption">Buwana user growth over the last 30days</p>
    </div>
    <p class="welcome-msg">Welcome back <?= htmlspecialchars($first_name) ?>!  You have <?= intval($new_account_count_for_user) ?> new users in the last 24hrs.  Manage your <?= $user_app_count ?> here...</p>
    <div class="app-grid">
      <?php foreach ($apps as $app): ?>
        <a href="app-view.php?app_id=<?= intval($app['app_id']) ?>" class="app-display-box" title="<?= htmlspecialchars($app['app_display_name']) ?>">
          <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon">
          <p><?= intval($app['user_count']) ?> users</p>
        </a>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-bottom:20px;">
      <a href="app-wizard.php" class="kick-ass-submit">Create New App</a>
    </div>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('../analytics/get-user-growth.php')
    .then(r => r.json())
    .then(chartData => {
      new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: chartData,
        options: { responsive: true }
      });
    });
});
</script>
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
