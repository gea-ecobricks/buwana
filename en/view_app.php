<?php
session_start();
require_once '../fetch_app_info.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'view-app';
$version = '0.1';
$lastModified = date('Y-m-d\TH:i:s\Z', filemtime(__FILE__));

$app_id = intval($_GET['app_id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <?php require_once("../meta/dashboard-en.php"); ?>
  <?php require_once("../includes/buwana-index-inc.php"); ?>
</head>
<body>
<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <h1 style="text-align:center;">App Overview</h1>
    <p>This page will display details for app ID <?= intval($app_id) ?>.</p>
  </div>
</div>
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
