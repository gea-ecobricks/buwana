<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'app-view';
$version = '0.1';
$lastModified = date('Y-m-d\TH:i:s\Z', filemtime(__FILE__));

if (empty($_SESSION['buwana_id'])) {
    $query = [
        'status'   => 'loggedout',
        'redirect' => $page,
    ];
    if (!empty($client_id)) {
        $query['app'] = $client_id;
    } elseif (!empty($_GET['client_id'])) {
        $query['app'] = $_GET['client_id'];
    } elseif (!empty($_GET['app'])) {
        $query['app'] = $_GET['app'];
    }
    if (!empty($buwana_id)) {
        $query['id'] = $buwana_id;
    } elseif (!empty($_GET['id'])) {
        $query['id'] = $_GET['id'];
    }

    header('Location: login.php?' . http_build_query($query));
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_flags'])) {
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $allow_signup = isset($_POST['allow_signup']) ? 1 : 0;
    $sql = "UPDATE apps_tb SET is_active=?, allow_signup=? WHERE app_id=? AND owner_buwana_id=?";
    $update_stmt = $buwana_conn->prepare($sql);
    if ($update_stmt) {
        $update_stmt->bind_param('iiii', $is_active, $allow_signup, $app_id, $buwana_id);
        $update_stmt->execute();
        $update_stmt->close();
        $app['is_active'] = $is_active;
        $app['allow_signup'] = $allow_signup;
    }
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
                  <div class="page-name">Manage: <?= htmlspecialchars($app['app_display_name']) ?></div>
                  <div class="client-id">Client ID: <?= htmlspecialchars($app['client_id']) ?></div>
                </div>
                <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon" title="<?= htmlspecialchars($app['app_display_name']) ?>" width="60" height="60">
          </div>
            <div class="breadcrumb" style="margin-left:auto;">
                          <a href="dashboard.php">Dashboard</a> &gt;
                          Manage <?= htmlspecialchars($app['app_display_name']) ?>
                        </div>
      </div>

    </div>
    <div class="chart-container dashboard-module" style="margin-bottom:15px;">
      <canvas id="growthChart"></canvas>
      <div class="chart-controls">
        <select id="timeRange" style="width:auto;font-size:0.9em;color:var(--subdued-text);background:none;border:1px solid var(--subdued-text);border-radius:4px;padding:2px 4px;">
          <option value="24h">Last 24hrs</option>
          <option value="week">Last Week</option>
          <option value="month" selected>Last Month</option>
          <option value="year">Last Year</option>
        </select>
      </div>
      <p class="chart-caption">App Manager user growth. Total connections: <?= intval($total_connections) ?>.</p>
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

      <div class="edit-app-params dashboard-module" style="margin-top:20px;">
        <h4 style="text-align:center;">Edit App Parameters</h4>
        <div class="edit-button-row">
          <a href="edit-app-core.php?app_id=<?= intval($app_id) ?>" class="simple-button">Core Data</a>
          <a href="edit-app-texts.php?app_id=<?= intval($app_id) ?>" class="simple-button">App texts</a>
          <a href="edit-app-graphics.php?app_id=<?= intval($app_id) ?>" class="simple-button">Logos &amp; Icons</a>
          <a href="edit-app-signup.php?app_id=<?= intval($app_id) ?>" class="simple-button">Signup banners</a>
        </div>
      </div>

      <div class="dashboard-module" style="margin-top:20px;">
        <div class="toggle-row" style="margin-bottom:10px;">
          <span><b>Enable <?= htmlspecialchars($app['app_display_name']) ?> Signups:</b></span>
          <label class="toggle-switch">
            <input type="checkbox" id="allow_signup" <?= $app['allow_signup'] ? 'checked' : '' ?>>
            <span class="slider"></span>
          </label>
        </div>
        <p style="font-size:0.9em;color:orange;">This turns off signups on your app but it is still available to users.</p>
      </div>

      <div class="dashboard-module" style="margin-top:20px;">
        <div class="toggle-row" style="margin:10px 0;">
          <span><b>Activate <?= htmlspecialchars($app['app_display_name']) ?>:</b></span>
          <label class="toggle-switch">
            <input type="checkbox" id="is_active" <?= $app['is_active'] ? 'checked' : '' ?>>
            <span class="slider"></span>
          </label>
        </div>
        <p style="font-size:0.9em;color:red;">This turns off all logins and signups on your app</p>
      </div>
  </div>
</div>
</div> <!-- closes main -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof updateChartTextColor === 'function') {
    updateChartTextColor();
  }

  const ctx = document.getElementById('growthChart').getContext('2d');
  let growthChart;

  function loadChart(range = 'month') {
    fetch('../analytics/get-growth-data.php?app_id=<?= intval($app_id) ?>&range=' + range)
      .then(r => r.json())
      .then(chartData => {
        if (growthChart) {
          growthChart.data = chartData;
          growthChart.update();
        } else {
          growthChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
              responsive: true,
              plugins: { legend: { position: 'bottom' } }
            }
          });
        }
      });
  }

  document.getElementById('timeRange').addEventListener('change', function() {
    loadChart(this.value);
  });

  loadChart();

  var table = $('#userTable').DataTable({
    order: [[4, 'desc']]
  });
  $('#userTable_wrapper').addClass('dashboard-module');

  function updateFlag(field, val) {
    fetch('../api/update_app_flag.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({
        app_id: <?= intval($app_id) ?>,
        field: field,
        value: val
      })
    }).then(r => r.json()).then(d => {
      if (!d.success) {
        alert('Error updating ' + field);
      }
    });
  }

  document.getElementById('allow_signup').addEventListener('change', function() {
    updateFlag('allow_signup', this.checked ? 1 : 0);
  });

  document.getElementById('is_active').addEventListener('change', function() {
    updateFlag('is_active', this.checked ? 1 : 0);
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
