<?php
include '../../connections/connections.php';
require './../../assets/PHPMailer/src/Exception.php';
require './../../assets/PHPMailer/src/PHPMailer.php';
require './../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$response = array('success' => false, 'message' => '');

// Decode JSON input
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// Extract $order_id from JSON payload
$order_id = isset($input['order_id']) ? $input['order_id'] : null;
$guest_email = isset($input['email']) ? $input['email'] : null;


mysqli_commit($conn);

// Send email notifications
$adminEmail = "admin@vetaidonline.info";
sendAdminEmail($adminEmail, "New Order Received", $order_id);
sendUserEmail($guest_email, "Order Confirmation", $order_id);

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
