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
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/dashboard-en.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require_once("../includes/buwana-index-inc.php"); ?>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <h1 style="text-align:center;">Buwana App Manager Dashboard</h1>
    <div class="app-grid">
      <?php foreach ($apps as $app): ?>
        <div class="app-display-box">
          <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon">
          <h4><?= htmlspecialchars($app['app_display_name']) ?></h4>
          <p><?= htmlspecialchars($app['app_description']) ?></p>
          <p>Total Users: <?= intval($app['user_count']) ?></p>
          <a href="view_app.php?app_id=<?= intval($app['app_id']) ?>">View Details ↗</a>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="max-width:600px;margin:auto;">
      <canvas id="growthChart"></canvas>
    </div>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('../analytics/get-growth-data.php')
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
