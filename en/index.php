<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'buwana-index';
$version = '0.7781';
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


<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">


<?php require_once ("../includes/buwana-index-inc.php");?>

<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
<!--    <h1 style="text-align:center;" data-lang-id="1000-explore-buwana-apps">Explore Buwana Apps</h1>
-->

    <div class="app-grid">
      <?php foreach ($apps as $app):
          $client_id = urlencode($app['client_id']);
          $link = "signup-1.php?app=$client_id";
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

<div style="text-align:center; max-width:600px; margin:auto; margin-bottom:25px;">
    <p  data-lang-id="3000-about-buwana-description">
      Buwana is an open-source login system for regenerative web applications developed by the Global Ecobrick Alliance. These apps all share a common commitment to people, planet, and privacy.
    </p>

    <p>The Buwana code-base and documention Wiki is on Github</p>
    <a href="https://github.com/gea-ecobricks/Buwana/tree/main">Check it out ↗</a>
 </div>

  </div>
</div>
</div>

<?php require_once("../footer-2025.php"); ?>
</body>
</html>
