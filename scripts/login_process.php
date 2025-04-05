<?php
session_start();
include '../buwana_env.php'; // Adjust path as needed

// Retrieve form data
$credential_value = $_POST['credential_value'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($credential_value) || empty($password)) {
    header("Location: ../$lang/login.php?error=empty_fields");
    exit();
}

// Prepare and execute query to check if the email exists and if legacy_unactivated is set to yes
$sql_check_email = "SELECT user_id, legacy_unactivated FROM users_tb WHERE email = ?";
$stmt_check_email = $conn->prepare($sql_check_email);

if ($stmt_check_email) {
    $stmt_check_email->bind_param('s', $credential_value);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows === 1) {
        $stmt_check_email->bind_result($user_id, $legacy_unactivated);
        $stmt_check_email->fetch();

        if ($legacy_unactivated === 'yes') {
            header("Location: ../$lang/activate.php?user_id=$user_id");
            exit();
        }

        $stmt_check_email->close();
    } else {
        // If email does not exist, continue with credential validation
        $stmt_check_email->close();
    }
} else {
    die('Error preparing statement for checking email: ' . $conn->error);
}

// Prepare and execute query to check credentials
$sql_credential = "SELECT user_id FROM credentials_tb WHERE credential_key = ?";
$stmt_credential = $conn->prepare($sql_credential);

if ($stmt_credential) {
    $stmt_credential->bind_param('s', $credential_value);
    $stmt_credential->execute();
    $stmt_credential->store_result();

    if ($stmt_credential->num_rows === 1) {
        $stmt_credential->bind_result($user_id);
        $stmt_credential->fetch();
        $stmt_credential->close();

        // Now retrieve the password hash from users_tb
        $sql_user = "SELECT password_hash FROM users_tb WHERE user_id = ?";
        $stmt_user = $conn->prepare($sql_user);

        if ($stmt_user) {
            $stmt_user->bind_param('i', $user_id);
            $stmt_user->execute();
            $stmt_user->store_result();

            if ($stmt_user->num_rows === 1) {
                $stmt_user->bind_result($password_hash);
                $stmt_user->fetch();

                // Verify password
                if (password_verify($password, $password_hash)) {
                    $_SESSION['user_id'] = $user_id;
                    header("Location: ../$lang/dashboard.php");
                    exit();
                } else {
                    header("Location: ../$lang/login.php?error=invalid_password");
                    exit();
                }
            } else {
                header("Location: ../$lang/login.php?error=invalid_user");
                exit();
            }
            $stmt_user->close();
        } else {
            die('Error preparing statement for users_tb: ' . $conn->error);
        }
    } else {
        header("Location: ../$lang/login.php?error=invalid_credential");
        exit();
    }
} else {
    die('Error preparing statement for credentials_tb: ' . $conn->error);
}

$conn->close();
?>
