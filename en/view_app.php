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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regenerate_client_id'])) {
    $new_client_id = 'app_' . bin2hex(random_bytes(8));
    $stmt = $buwana_conn->prepare("UPDATE apps_tb SET client_id = ? WHERE app_id = ? AND owner_buwana_id = ?");
    if ($stmt) {
        $stmt->bind_param('sii', $new_client_id, $app_id, $buwana_id);
        $stmt->execute();
        $stmt->close();
    }
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

$stmt = $buwana_conn->prepare("SELECT COUNT(*) FROM user_app_connections_tb WHERE client_id = ?");
$stmt->bind_param('s', $app['client_id']);
$stmt->execute();
$stmt->bind_result($total_connections);
$stmt->fetch();
$stmt->close();

$recent_users = [];
$stmt = $buwana_conn->prepare("SELECT u.* FROM users_tb u JOIN user_app_connections_tb c ON u.buwana_id = c.buwana_id WHERE c.client_id = ? AND c.status = 'registered' ORDER BY u.created_at DESC LIMIT 100");
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
      <div class="page-name">Manage: <?= htmlspecialchars($app['app_display_name']) ?></div>
    </div>
    <div class="chart-container">
      <canvas id="growthChart"></canvas>
      <p class="chart-caption"><?= htmlspecialchars($app['app_display_name']) ?> user growth over the last 30days</p>
    </div>

    <p><strong>Client ID:</strong> <?= htmlspecialchars($app['client_id']) ?></p>
    <p><strong>Total Connections:</strong> <?= intval($total_connections) ?></p>
    <form method="post" style="margin-bottom:20px;">
      <button type="submit" name="regenerate_client_id">Regenerate Client ID</button>
    </form>

    <table id="userTable" class="display" style="width:100%">
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Email</th>
          <th>Location</th>
          <th>Notes</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Bot Score</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent_users as $u): ?>
          <tr data-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>'>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['location_full']) ?></td>
            <td><?= htmlspecialchars($u['notes']) ?></td>
            <td><?= htmlspecialchars($u['account_status']) ?></td>
            <td><?= htmlspecialchars($u['created_at']) ?></td>
            <td><?= htmlspecialchars($u['bot_score']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <form method="post" style="margin-top:20px;">
      <label>Redirect URIs<br>
        <textarea name="redirect_uris" rows="2" cols="40"><?= htmlspecialchars($app['redirect_uris']) ?></textarea>
      </label><br><br>
      <label>App Login URL<br>
        <input type="text" name="app_login_url" value="<?= htmlspecialchars($app['app_login_url']) ?>">
      </label><br><br>
      <label>Scopes<br>
        <input type="text" name="scopes" value="<?= htmlspecialchars($app['scopes']) ?>">
      </label><br><br>
      <label>App Domain<br>
        <input type="text" name="app_domain" value="<?= htmlspecialchars($app['app_domain']) ?>">
      </label><br><br>
      <label>App URL<br>
        <input type="text" name="app_url" value="<?= htmlspecialchars($app['app_url']) ?>">
      </label><br><br>
      <label>App Dashboard URL<br>
        <input type="text" name="app_dashboard_url" value="<?= htmlspecialchars($app['app_dashboard_url']) ?>">
      </label><br><br>
      <label>Description<br>
        <textarea name="app_description" rows="3" cols="40"><?= htmlspecialchars($app['app_description']) ?></textarea>
      </label><br><br>
      <label>Version<br>
        <input type="text" name="app_version" value="<?= htmlspecialchars($app['app_version']) ?>">
      </label><br><br>
      <label>Display Name<br>
        <input type="text" name="app_display_name" value="<?= htmlspecialchars($app['app_display_name']) ?>">
      </label><br><br>
      <label>Contact Email<br>
        <input type="email" name="contact_email" value="<?= htmlspecialchars($app['contact_email']) ?>">
      </label><br><br>
      <button type="submit" name="update_app">Save Changes</button>
    </form>
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
    order: [[5, 'desc']]
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
