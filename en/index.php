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
$app_query = "SELECT client_id, app_display_name, app_slogan, app_square_icon_url FROM apps_tb ORDER BY app_display_name ASC";
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


<?php require_once ("../includes/buwana-index-inc.php");?>



<div id="form-submission-box" class="landing-page-form">

  <div class="form-container">
      <div id="top-page-image"
                  style="width:100%;height:350px;background:no-repeat center;background-size:contain;margin-top:-70px,margin-bottom:20px;"
                  data-light-img="../webps/top-buwana-landing-banner.webp"
                  data-dark-img="../webps/top-buwana-landing-banner.webp">
             </div>
<h4  data-lang-id="001-about-buwana-description" style="text-align:center;">
      Buwana is an open-source login system for regenerative web applications developed by the Global Ecobrick Alliance. The Buwana protocol provides the a user authentication alternative for apps that want to escape corporate logins for an ecoystem of resonant, green for-Earth enterprises.
    </h4>
    <p data-lang-id="002-just-starting" style="text-align:center;">The Buwana protocol provides the a user authentication alternative for apps that want to escape corporate logins for an ecoystem of resonant, green for-Earth enterprises. The Buwana protocol has only just launched as of June 2025.  Here's the apps that are using it so far...</p>
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
          <p><?= htmlspecialchars($app['app_slogan']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>

<div style="text-align:center; max-width:600px; margin:auto; margin-bottom:25px;">


    <p>The Buwana code-base and documention Wiki is on Github</p>
    <a href="https://github.com/gea-ecobricks/Buwana/tree/main">Check it out ↗</a>
 </div>

  </div>
</div>
</div>

<?php require_once("../footer-2025.php"); ?>
</body>
</html>
