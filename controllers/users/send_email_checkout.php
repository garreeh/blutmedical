<?php
include '../../connections/connections.php';
require './../../assets/PHPMailer/src/Exception.php';
require './../../assets/PHPMailer/src/PHPMailer.php';
require './../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$response = array('success' => false, 'message' => '');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

// Decode JSON input
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// Extract orderID from JSON payload
$order_id = isset($input['order_id']) ? $input['order_id'] : null;


$userEmailSql = "SELECT user_email FROM users WHERE user_id = '$user_id'";
$userEmailResult = mysqli_query($conn, $userEmailSql);

if (!$userEmailResult || mysqli_num_rows($userEmailResult) === 0) {
    throw new Exception('Failed to fetch user email');
}

$userRow = mysqli_fetch_assoc($userEmailResult);
$userEmail = $userRow['user_email'];

mysqli_commit($conn);

// Send email notifications
$adminEmail = "admin@vetaidonline.info";
sendAdminEmail($adminEmail, "New Order Received", $order_id);
sendUserEmail($userEmail, "Order Confirmation", $order_id);

$response['success'] = true;

// Function to send admin email
function sendAdminEmail($toEmail, $subject, $order_id)
{
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = 'smtpout.secureserver.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'sales@hyresvard.com';
    $mail->Password = 'Mybossrocks081677!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
    $mail->addAddress($toEmail);
    $mail->isHTML(true);
    $mail->Subject = $subject;

    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .email-container {
                background-color: #f5f5f5;
                padding: 20px;
                text-align: center;
                border-radius: 5px;
            }
            .email-header {
                background-color:rgb(24, 13, 105);
                color: white;
                padding: 10px;
                font-size: 18px;
                font-weight: bold;
            }
            .email-content {
                padding: 15px;
                background-color: white;
                border-radius: 5px;
            }
            .email-footer {
                margin-top: 20px;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>New Order Received</div>
            <div class='email-content'>
                <p>You have received a new order.</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(45, 15, 94); font-size: 18px;'>$order_id</span></p>
                <p>Please check the admin panel for details.</p>
            </div>
            <div class='email-footer'>VetAID Online - Admin Notification</div>
        </div>
    </body>
    </html>";

    $mail->send();
}

// Function to send user email
function sendUserEmail($toEmail, $subject, $order_id)
{
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = 'smtpout.secureserver.net';
    $mail->SMTPAuth = true;
    $mail->Username = 'sales@hyresvard.com';
    $mail->Password = 'Mybossrocks081677!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
    $mail->addAddress($toEmail);
    $mail->isHTML(true);
    $mail->Subject = $subject;

    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .email-container {
                background-color: #f5f5f5;
                padding: 20px;
                text-align: center;
                border-radius: 5px;
            }
            .email-header {
                background-color:rgb(41, 22, 100);
                color: white;
                padding: 10px;
                font-size: 18px;
                font-weight: bold;
            }
            .email-content {
                padding: 15px;
                background-color: white;
                border-radius: 5px;
            }
            .email-footer {
                margin-top: 20px;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>Order Confirmation</div>
            <div class='email-content'>
                <p>Your order has been successfully placed!</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(17, 21, 74); font-size: 18px;'>$order_id</span></p>
                <p>Thank you for shopping with us.</p>
            </div>
            <div class='email-footer'>VetAID Online - Order Confirmation</div>
        </div>
    </body>
    </html>";

    $mail->send();
}
