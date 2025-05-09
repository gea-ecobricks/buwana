<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'index';
$version = '0.778';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

$buwana_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

// 🔍 Fetch all apps
$app_query = "SELECT client_id, app_display_name, app_description, app_square_icon_url FROM apps_tb ORDER BY app_display_name ASC";
$app_results = $buwana_conn->query($app_query);

$apps = [];
if ($app_results && $app_results->num_rows > 0) {
    while ($row = $app_results->fetch_assoc()) {
        $apps[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <title>Buwana Apps</title>

<?php require_once("../meta/$page-$lang.php"); ?>

<style>
.form-container {
  padding-top: 30px !important;
}

.app-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  margin: 0 auto 30px auto;
  max-width: 600px;
  padding: 10px;
}

@media (min-width: 600px) {
  .app-grid {
    grid-template-columns: 1fr 1fr;
  }
}

.app-display-box {
  border: 1px solid var(--subdued-text);
  background-color: var(--lighter);
  border-radius: 12px;
  padding: 15px;
  text-align: center;
  transition: all 0.3s ease;
  cursor: pointer;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.app-display-box:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  background-color: var(--light);
}

.app-display-box img {
  width: 80px;
  height: 80px;
  object-fit: contain;
  margin-bottom: 10px;
}

.app-display-box h4 {
  margin: 5px 0 8px 0;
  font-size: 1.1em;
  color: var(--text);
}

.app-display-box p {
  font-size: 0.9em;
  color: var(--subdued-text);
  margin: 0;
}
</style>

<?php require_once("../header-2025.php"); ?>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <h1 style="text-align:center;" data-lang-id="1000-explore-buwana-apps">🌐 Explore Buwana Apps</h1>
    <p style="text-align:center; max-width:600px; margin:auto; margin-bottom:25px;" data-lang-id="3000-about-buwana-description">
      Buwana is an open-source login system for regenerative web applications developed by the Global Ecobrick Alliance. These apps all share a common commitment to people, planet, and privacy.
    </p>

    <div class="app-grid">
      <?php foreach ($apps as $app):
          $client_id = urlencode($app['client_id']);
          $link = "login.php?app=$client_id";
          if ($buwana_id) {
              $link .= "&id=$buwana_id";
          }
      ?>
        <a href="<?= htmlspecialchars($link) ?>" class="app-display-box">
          <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon">
          <h4><?= htmlspecialchars($app['app_display_name']) ?></h4>
          <p><?= htmlspecialchars($app['app_description']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php require_once("../footer-2025.php"); ?>
</body>
</html>
