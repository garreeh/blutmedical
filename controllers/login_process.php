<?php
session_start();
include './../connections/connections.php';

if (isset($_POST['username_or_email'], $_POST['user_password'])) {
    $usernameOrEmail = $conn->real_escape_string($_POST['username_or_email']);
    $user_password = $_POST['user_password'];
    $rememberMe = $_POST['remember_me'];

    if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
        $condition = "`user_email`='$usernameOrEmail'";
    } else {
        $condition = "`username`='$usernameOrEmail'";
    }

    $query = "SELECT * FROM `users` WHERE $condition";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            // Check if account is active
            if ($row["account_status"] != 'Active') {
                echo json_encode(['success' => false, 'message' => 'Account is not active. Please verify your email.']);
                exit();
            }

            if ($user_password == $row["user_confirm_password"]) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_fullname'] = $row['user_fullname'];
                $_SESSION['user_email'] = $row['user_email'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['is_admin'] = $row['is_admin'];
                $_SESSION['user_type_id'] = $row['user_type_id'];


                if ($rememberMe == "1") {
                    $token = bin2hex(random_bytes(32));
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE `users` SET `remember_me`='$hashedToken' WHERE `user_id`={$row['user_id']}";
                    $updateResult = mysqli_query($conn, $updateQuery);

                    if (!$updateResult) {
                        echo json_encode(['success' => false, 'message' => 'Error updating remember token: ' . mysqli_error($conn)]);
                        exit();
                    }

                    setcookie('remember_me', $token, time() + (86400 * 30), "/");
                }

                // Send the success response with is_admin
                echo json_encode([
                    'success' => true,
                    'is_admin' => $row['is_admin']
                ]);

                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Incorrect password. Please try again.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect username/email. Please try again.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing query: ' . mysqli_error($conn)]);
        exit();
    }
}
