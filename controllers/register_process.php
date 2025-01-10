<?php
include '../connections/connections.php';

require '../assets/PHPMailer/src/Exception.php';
require '../assets/PHPMailer/src/PHPMailer.php';
require '../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize POST data
    $user_fullname = isset($_POST['user_fullname']) ? $conn->real_escape_string($_POST['user_fullname']) : '';
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $user_email = isset($_POST['user_email']) ? $conn->real_escape_string($_POST['user_email']) : '';
    $user_contact = isset($_POST['user_contact']) ? $conn->real_escape_string($_POST['user_contact']) : '';
    $user_address = isset($_POST['user_address']) ? $conn->real_escape_string($_POST['user_address']) : '';
    $user_password = isset($_POST['user_password']) ? $conn->real_escape_string($_POST['user_password']) : '';
    $user_confirm_password = isset($_POST['user_confirm_password']) ? $conn->real_escape_string($_POST['user_confirm_password']) : '';

    // Check if any field is empty
    if (empty($user_fullname) || empty($username) || empty($user_email) || empty($user_contact) || empty($user_address) || empty($user_password) || empty($user_confirm_password)) {
        $response = array('success' => false, 'message' => 'All fields are required.');
        echo json_encode($response);
        exit();
    }

    // Validate email format
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $response = array('success' => false, 'message' => 'Invalid email format.');
        echo json_encode($response);
        exit();
    }

    // Check if passwords match
    if ($user_password !== $user_confirm_password) {
        $response = array('success' => false, 'message' => 'Passwords do not match.');
        echo json_encode($response);
        exit();
    }

    // Check if username or email already exists
    $sql = "SELECT * FROM users WHERE username = '$username' OR user_email = '$user_email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $response = array('success' => false, 'message' => 'Username or email already exists.');
        echo json_encode($response);
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Insert user into the database
    $sql = "INSERT INTO users (user_fullname, username, user_email, user_contact, user_address, user_password, user_confirm_password, is_admin, account_status) 
            VALUES ('$user_fullname', '$username', '$user_email', '$user_contact', '$user_address', '$hashed_password', '$user_confirm_password', '0', 'Inactive')";

    if (mysqli_query($conn, $sql)) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'gajultos.garry123@gmail.com';          // SMTP username
            $mail->Password = 'dkjukjhiygnwipjc';
            // SMTP password
            $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = '465';                                  // TCP port to connect to

            //Recipients
            $mail->setFrom('gajultos.garry123@gmail.com', 'Garry Gajultos');
            $mail->addAddress($user_email, $user_fullname);             // Add a recipient

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->Subject = 'Email Verification';
            $mail->Body = "
                            <html>
                            <head>
                                <style>
                                    .email-container {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                    }
                                    .email-header {
                                        background-color: #007bff;
                                        padding: 20px;
                                        color: #fff;
                                        text-align: center;
                                    }
                                    .email-body {
                                        padding: 20px;
                                        background-color: #f9f9f9;
                                    }
                                    .email-footer {
                                        text-align: center;
                                        padding: 10px;
                                        background-color: #007bff;
                                        color: #fff;
                                    }
                                    .verify-button {
                                        color: #fff;
                                        text-decoration: none;
                                        background-color: #007bff;
                                        padding: 10px 20px;
                                        border-radius: 5px;
                                        display: inline-block;
                                        font-weight: bold;
                                    }
                                    .verify-button:hover {
                                        background-color: #0056b3;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class='email-container'>
                                    <div class='email-header'>
                                        <h2>Email Verification</h2>
                                    </div>
                                    <div class='email-body'>
                                        <p>Dear $user_fullname,</p>
                                        <p>Thank you for registering with us. To complete your registration, please verify your email address by clicking the button below:</p>
                                        <a href='http://localhost/blutmedical/controllers/verification_process.php?email=" . $user_email . "' class='verify-button'>Verify Email</a>
                                        <p>If you did not sign up for this account, please disregard this email.</p>
                                        <p>Best Regards,<br>Sterling</p>
                                    </div>
                                    <div class='email-footer'>
                                        &copy; 2024 Sterling. All rights reserved.
                                    </div>
                                </div>
                            </body>
                            </html>";

            $mail->send();
        } catch (Exception $e) {
            $response = array('success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            echo json_encode($response);
            exit();
        }

        session_start();
        $_SESSION['email_verified'] = true;

        // Redirect to verification page
        $response = array('success' => true, 'message' => 'Registration successful. Please check your email for verification.');
        echo json_encode($response);
        exit();
    } else {
        $response = array('success' => false, 'message' => 'Registration failed. Please try again.');
        echo json_encode($response);
        exit();
    }
}
