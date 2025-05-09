<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../buwanaconn_env.php';

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'landing';
$version = '0.777';
$lastModified = date("Y-m-d\TH:i:s\Z", filemtime(__FILE__));

// 🧩 Optional: Validate buwana_id
$buwana_id = $_GET['id'] ?? null;
if ($buwana_id !== null && !is_numeric($buwana_id)) {
    die("⚠️ Invalid Buwana ID.");
}

// 🔍 Fetch user info if available
$first_name = 'Guest';
$earthling_emoji = '🌍';
if ($buwana_id) {
    $stmt = $buwana_conn->prepare("SELECT first_name, earthling_emoji FROM users_tb WHERE buwana_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $buwana_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $earthling_emoji);
        $stmt->fetch();
        $stmt->close();
    }
}

// 🔍 Fetch all apps
$apps = [];
$sql = "SELECT app_display_name, app_description, client_id, app_square_icon_url FROM apps_tb ORDER BY app_display_name ASC";
$result = $buwana_conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $apps[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8">
  <title>Buwana Apps</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      padding: 0;
      background: #f9f9f9;
      color: #333;
    }

    .app-grid-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 30px;
      max-width: 1000px;
      margin: auto;
    }

    .app-display-box {
      background-color: white;
      border: 1px solid var(--subdued-text, #ccc);
      border-radius: 12px;
      width: 100%;
      max-width: 300px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }

    .app-display-box:hover {
      transform: translateY(-4px);
    }

    .app-icon-img {
      width: 80px;
      height: 80px;
      border-radius: 16px;
      margin-bottom: 15px;
      object-fit: cover;
      border: 1px solid #ddd;
    }

    @media (max-width: 600px) {
      .app-grid-container {
        flex-direction: column;
        padding: 20px;
      }

      .app-display-box {
        width: 100%;
        max-width: none;
      }
    }
  </style>
</head>
<body>

<div style="text-align:center; padding: 30px 20px;">
  <div style="font-size:4em;"><?= htmlspecialchars($earthling_emoji) ?></div>
  <h1>Welcome <?= htmlspecialchars($first_name) ?>!</h1>
  <p>Select a regenerative app to log into with your Buwana account:</p>
</div>

<div class="app-grid-container">
  <?php foreach ($apps as $app): ?>
    <?php
      $loginUrl = "login.php?app=" . urlencode($app['client_id']);
      if ($buwana_id) {
          $loginUrl .= "&id=" . urlencode($buwana_id);
      }
    ?>
    <a class="app-display-box" href="<?= htmlspecialchars($loginUrl) ?>">
      <img src="<?= htmlspecialchars($app['app_square_icon_url']) ?>" alt="<?= htmlspecialchars($app['app_display_name']) ?> Icon" class="app-icon-img">
      <h4><?= htmlspecialchars($app['app_display_name']) ?></h4>
      <p><?= htmlspecialchars($app['app_description'] ?? 'No description available.') ?></p>
    </a>
  <?php endforeach; ?>
</div>

<?php require_once ("../footer-2025.php"); ?>
</body>
</html>
