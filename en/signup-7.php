<?php
session_start();
require_once '../fetch_app_info.php'; // Provides $app_info

$buwana_id = $_GET['id'] ?? null;
if (!$buwana_id || !is_numeric($buwana_id)) {
    die("⚠️ Invalid or missing Buwana ID.");
}

$app_login_url = $app_info['app_login_url'] ?? '/';
$redirect_url = $app_login_url . "?status=firsttime&id=" . urlencode($buwana_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Redirecting...</title>
  <meta http-equiv="refresh" content="5;url=<?= htmlspecialchars($redirect_url) ?>">
  <style>
    body {
      font-family: sans-serif;
      text-align: center;
      padding-top: 100px;
    }
  </style>
</head>
<body>
  <h2>🎉 Your account has been created!</h2>
  <p>You're being redirected to your new app dashboard...</p>
  <p>If you're not redirected automatically, <a href="<?= htmlspecialchars($redirect_url) ?>">click here</a>.</p>

  <script>
    setTimeout(() => {
      window.location.href = <?= json_encode($redirect_url) ?>;
    }, 5000);
  </script>
</body>
</html>
