<?php
require_once '../earthenAuth_helper.php';
require_once '../vendor/autoload.php';
require_once '../gobrikconn_env.php';
require_once '../buwanaconn_env.php';

// Logging helper for authentication flow
$authLogFile = dirname(__DIR__) . '/logs/auth.log';
function auth_log($message) {
    global $authLogFile;
    if (!file_exists(dirname($authLogFile))) {
        mkdir(dirname($authLogFile), 0777, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . "] PROCESS: " . $message . PHP_EOL, 3, $authLogFile);
}

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

startSecureSession();
auth_log('Login process started from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));

$credential_key = $_POST['credential_key'] ?? '';
$password = $_POST['password'] ?? '';
auth_log("Credentials received for key: $credential_key");
$lang = $_POST['lang'] ?? '';
if (empty($lang) && isset($_SERVER['HTTP_REFERER'])) {
    $refPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
    $parts = explode('/', trim($refPath, '/'));
    if (!empty($parts[0]) && strlen($parts[0]) === 2) {
        $lang = $parts[0];
    }
}
if (empty($lang)) {
    $lang = 'en';
}
$redirect = filter_var($_POST['redirect'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
$client_id = $_POST['client_id'] ?? ($_SESSION['client_id'] ?? null);
$csrf_token = $_POST['csrf_token'] ?? '';

if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    auth_log('CSRF token validation failed');
    header("Location: ../$lang/login.php?status=invalid_csrf");
    exit();
} else {
    auth_log('CSRF token validated');
}

if (empty($credential_key) || empty($password)) {
    auth_log('Empty credential key or password');
    header("Location: ../$lang/login.php?status=empty_fields&key=" . urlencode($credential_key));
    exit();
}

$sql_check_email = "SELECT ecobricker_id, buwana_activated FROM tb_ecobrickers WHERE email_addr = ?";
auth_log('Checking GoBrik account for ' . $credential_key);
$stmt_check_email = $gobrik_conn->prepare($sql_check_email);

if ($stmt_check_email) {
    $stmt_check_email->bind_param('s', $credential_key);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows === 1) {
        $stmt_check_email->bind_result($ecobricker_id, $buwana_activated);
        $stmt_check_email->fetch();
        if ($buwana_activated == '0') {
            auth_log('GoBrik account found but not activated');
            header("Location: https://gobrik.com/$lang/activate.php?id=$ecobricker_id");
            exit();
        }
    }
    $stmt_check_email->close();
} else {
    auth_log('Error preparing statement for checking email: ' . $gobrik_conn->error);
    die('Database query failed.');
}

$sql_credential = "SELECT buwana_id FROM credentials_tb WHERE credential_key = ?";
$stmt_credential = $buwana_conn->prepare($sql_credential);
auth_log('Looking up credential key in credentials_tb');

if ($stmt_credential) {
    $stmt_credential->bind_param('s', $credential_key);
    $stmt_credential->execute();
    $stmt_credential->store_result();

    if ($stmt_credential->num_rows === 1) {
        $stmt_credential->bind_result($buwana_id);
        $stmt_credential->fetch();
        auth_log("Credential key found for buwana_id $buwana_id");
        $stmt_credential->close();

        $sql_user = "SELECT password_hash, first_name, email, earthling_emoji, community_id, continent_code, open_id FROM users_tb WHERE buwana_id = ?";
        $stmt_user = $buwana_conn->prepare($sql_user);

        if ($stmt_user) {
            $stmt_user->bind_param('i', $buwana_id);
            $stmt_user->execute();
            $stmt_user->store_result();

            if ($stmt_user->num_rows === 1) {
                $stmt_user->bind_result($password_hash, $first_name, $email, $earthling_emoji, $community_id, $continent_code, $open_id);
                $stmt_user->fetch();

                if (password_verify($password, $password_hash)) {
                    $buwana_conn->query("UPDATE users_tb SET last_login = NOW(), login_count = login_count + 1 WHERE buwana_id = $buwana_id");
                    $buwana_conn->query("UPDATE credentials_tb SET last_login = NOW(), times_used = times_used + 1 WHERE buwana_id = $buwana_id");

                    $community_name = getCommunityName($buwana_conn, $buwana_id);

                    $_SESSION['buwana_id'] = $buwana_id;

                    if ($client_id) {
                        $sql_app = "SELECT app_dashboard_url, scopes, jwt_private_key FROM apps_tb WHERE client_id = ?";
                        $stmt = $buwana_conn->prepare($sql_app);
                        $stmt->bind_param('s', $client_id);
                        $stmt->execute();
                        $stmt->bind_result($app_dashboard_url, $scopes, $jwt_private_key);
                        $stmt->fetch();
                        $stmt->close();

                        $scope_list = array_map('trim', explode(',', $scopes));
                        $payload = [
                            'iss' => 'https://buwana.ecobricks.org',
                            'aud' => $client_id,
                            'iat' => time(),
                            'exp' => time() + 3600,
                            'sub' => $open_id ?? "buwana_$buwana_id"
                        ];

                        foreach ($scope_list as $scope) {
                            switch ($scope) {
                                case 'email':
                                    $payload['email'] = $email;
                                    break;
                                case 'profile':
                                    $payload['given_name'] = $first_name;
                                    break;
                                case 'buwana:earthlingEmoji':
                                    $payload['buwana:earthlingEmoji'] = $earthling_emoji;
                                    break;
                                case 'buwana:community':
                                    $payload['buwana:community'] = $community_name;
                                    break;
                                case 'buwana:location.continent':
                                    $payload['buwana:location.continent'] = $continent_code;
                                    break;
                            }
                        }

                        $jwt = JWT::encode($payload, $jwt_private_key, 'RS256', $client_id);
                        auth_log('JWT generated for buwana_id ' . $buwana_id . ' client ' . $client_id);

                        // One JWT per app session
                        $_SESSION['jwt'] = $jwt;

                        $check_sql = "SELECT id FROM user_app_connections_tb WHERE buwana_id = ? AND client_id = ? LIMIT 1";
                        $check_stmt = $buwana_conn->prepare($check_sql);
                        $check_stmt->bind_param('is', $buwana_id, $client_id);
                        $check_stmt->execute();
                        $check_stmt->bind_result($connection_id);
                        $check_stmt->fetch();
                        $check_stmt->close();

                        if (!$connection_id) {
                            auth_log('No connection_id found; redirecting to app connect');
                            header("Location: ../$lang/app-connect.php?id=$buwana_id&client_id=$client_id");
                            exit();
                        } else {
                            auth_log('Existing connection found: ' . $connection_id);
                            $_SESSION['connection_id'] = $connection_id;
                        }
                    }

                    $default_dashboard = "../$lang/dashboard.php";
                    $redirect_url = !empty($redirect) ? $redirect : ($app_dashboard_url ?? $default_dashboard);
                    auth_log('Password verified for buwana_id ' . $buwana_id);
                    header("Location: $redirect_url");
                    exit();
                } else {
                    auth_log('Invalid password for buwana_id ' . $buwana_id);
                    header("Location: ../$lang/login.php?status=invalid_password&key=" . urlencode($credential_key));
                    exit();
                }
            } else {
                auth_log('User record not found for buwana_id ' . $buwana_id);
                header("Location: ../$lang/login.php?status=invalid_user&key=" . urlencode($credential_key));
                exit();
            }
            $stmt_user->close();
        }
    } else {
        auth_log('Credential key not found');
        header("Location: ../$lang/login.php?status=invalid_credential&key=" . urlencode($credential_key));
        exit();
    }
} else {
    auth_log('Error preparing statement for credentials_tb: ' . $buwana_conn->error);
    die('Database query failed.');
}

$buwana_conn->close();
$gobrik_conn->close();
auth_log('Database connections closed');
?>
