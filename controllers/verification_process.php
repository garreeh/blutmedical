<?php
include '../connections/connections.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve and sanitize email parameter
    $user_email = isset($_GET['email']) ? $conn->real_escape_string($_GET['email']) : '';

    // Check if the email is empty
    if (empty($user_email)) {
        header("Location: ../views/login.php");
        exit();
    }

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Email exists, update the user's status
        $sql_update = "UPDATE users SET account_status = 'Active' WHERE user_email = '$user_email'";
        if (mysqli_query($conn, $sql_update)) {
            session_start();
            $_SESSION['email_verified'] = true;

            header("Location: ../views/success_verification.php");
            exit();
        } else {
            header("Location: ../views/login.php");
        }
    } else {
        echo "Invalid email address.";
    }
}
