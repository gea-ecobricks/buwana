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
    $app_display_name = $_POST['app_display_name'] ?? '';
    $app_description  = $_POST['app_description'] ?? '';
    $contact_email    = $_POST['contact_email'] ?? '';
    $app_logo_url     = $_POST['app_logo_url'] ?? '';

    $stmt = $buwana_conn->prepare("UPDATE apps_tb SET app_display_name=?, app_description=?, contact_email=?, app_logo_url=? WHERE app_id=? AND owner_buwana_id=?");
    if ($stmt) {
        $stmt->bind_param('ssssii', $app_display_name, $app_description, $contact_email, $app_logo_url, $app_id, $buwana_id);
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
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/app-view-en.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require_once("../includes/buwana-index-inc.php"); ?>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <h1 style="text-align:center;">Manage App: <?= htmlspecialchars($app['app_display_name']) ?></h1>
    <div style="text-align:center;margin-bottom:20px;">
        <img src="<?= htmlspecialchars($app['app_logo_url']) ?>" alt="Logo" style="max-width:120px;">
    </div>
    <p><strong>Client ID:</strong> <?= htmlspecialchars($app['client_id']) ?></p>
    <p><strong>Total Connections:</strong> <?= intval($total_connections) ?></p>
    <form method="post" style="margin-bottom:20px;">
        <button type="submit" name="regenerate_client_id">Regenerate Client ID</button>
    </form>
    <form method="post">
        <label>Display Name<br>
            <input type="text" name="app_display_name" value="<?= htmlspecialchars($app['app_display_name']) ?>">
        </label><br><br>
        <label>Description<br>
            <textarea name="app_description" rows="3" cols="40"><?= htmlspecialchars($app['app_description']) ?></textarea>
        </label><br><br>
        <label>Contact Email<br>
            <input type="email" name="contact_email" value="<?= htmlspecialchars($app['contact_email']) ?>">
        </label><br><br>
        <label>Logo URL<br>
            <input type="text" name="app_logo_url" value="<?= htmlspecialchars($app['app_logo_url']) ?>">
        </label><br><br>
        <button type="submit" name="update_app">Save Changes</button>
    </form>
    <div style="max-width:600px;margin:auto;">
      <canvas id="growthChart"></canvas>
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
});
</script>
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
