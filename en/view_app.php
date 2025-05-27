<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'view-app';
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

$stmt = $buwana_conn->prepare("SELECT COUNT(*) FROM user_app_connections_tb WHERE client_id = ?");
$stmt->bind_param('s', $app['client_id']);
$stmt->execute();
$stmt->bind_result($total_connections);
$stmt->fetch();
$stmt->close();

$recent_users = [];
$stmt = $buwana_conn->prepare("SELECT u.*, cn.country_name FROM users_tb u JOIN user_app_connections_tb uc ON u.buwana_id = uc.buwana_id LEFT JOIN countries_tb cn ON u.country_id = cn.country_id WHERE uc.client_id = ? AND uc.status = 'registered' ORDER BY u.created_at DESC LIMIT 100");
if ($stmt) {
    $stmt->bind_param('s', $app['client_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/app-view-en.php"); ?>
    <link rel="stylesheet" href="../styles/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../scripts/jquery.dataTables.js"></script>
    <?php require_once("../includes/dashboard-inc.php"); ?>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <div class="top-wrapper">
      <div class="login-status"><?= htmlspecialchars($earthling_emoji) ?> Logged in as <?= htmlspecialchars($first_name) ?></div>
      <div>
        <div class="page-name">Manage: <?= htmlspecialchars($app['app_display_name']) ?></div>
        <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="growthChart"></canvas>
      <p class="chart-caption">App Manager user growth over the last 30 days. Total connections: <?= intval($total_connections) ?>.</p>
    </div>


      <table id="userTable" class="display" style="width:100%">
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Country</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Emoji</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_users as $u): ?>
            <tr data-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>'>
              <td><?= htmlspecialchars($u['full_name']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['country_name']) ?></td>
              <td><?= htmlspecialchars($u['account_status']) ?></td>
              <td><?= htmlspecialchars($u['created_at']) ?></td>
              <td><?= htmlspecialchars($u['earthling_emoji']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="margin-top:20px;text-align:center;">
        <a href="edit_app_core.php?app_id=<?= intval($app_id) ?>" class="kick-ass-submit">Edit Core App Data</a>
      </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  fetch('../analytics/get-growth-data.php?app_id=<?= intval($app_id) ?>')
    .then(r => r.json())
    .then(chartData => {
      new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: chartData,
        options: { responsive: true }
      });
    });

  var table = $('#userTable').DataTable({
    order: [[4, 'desc']]
  });

  $('#userTable tbody').on('click', 'tr', function() {
    var user = $(this).data('user');
    if (!user) return;
    var html = '<table class="basic-table">';
    for (var k in user) {
      if (Object.prototype.hasOwnProperty.call(user, k)) {
        html += '<tr><th>' + k + '</th><td>' + user[k] + '</td></tr>';
      }
    }
    html += '</table>';
    openModal(html);
  });
});
</script>
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
