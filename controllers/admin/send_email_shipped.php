<?php
include '../../connections/connections.php';
require './../../assets/PHPMailer/src/Exception.php';
require './../../assets/PHPMailer/src/PHPMailer.php';
require './../../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$response = array('success' => false, 'message' => '');

// Decode JSON input
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// Extract cart_id from JSON payload
$cart_id = isset($input['cart_id']) ? $input['cart_id'] : null;

if (!$cart_id) {
    $response['message'] = 'Cart ID is missing';
    echo json_encode($response);
    exit;
}

// Query to get user email or guest email and reference_no
$sql = "
    SELECT 
        CASE 
            WHEN c.payment_method = 'Paypal' THEN c.paypal_order_id 
            ELSE c.reference_no 
        END AS order_id,
        COALESCE(u.user_email, c.delivery_guest_email) AS user_email
    FROM cart c
    LEFT JOIN users u ON c.user_id = u.user_id
    WHERE c.cart_id = '$cart_id'
    LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    $response['message'] = 'Failed to fetch email';
    echo json_encode($response);
    exit;
}

$row = mysqli_fetch_assoc($result);
$order_id = $row['order_id']; // Get the reference_no
$userEmail = $row['user_email']; // Get either user_email or delivery_guest_email

// Send email notifications
$adminEmail = "gajultos.garry123@gmail.com";
sendAdminEmail($adminEmail, "Order Shipped Notification", $order_id);
sendUserEmail($userEmail, "Your Order Has Been Shipped!", $order_id);

$response['success'] = true;

// Function to send admin email
function sendAdminEmail($toEmail, $subject, $order_id)
{
    $mail = new PHPMailer;
    $mail->IsSMTP();

    // This settings is for Godaddy Live
    $mail->Host = 'relay-hosting.secureserver.net';
    $mail->SMTPAuth = false;                                      // Enable SMTP authentication
    $mail->Username = 'admin@vetaidonline.info';             // SMTP username
    $mail->Password = 'Mybossrocks081677!';                        // SMTP password
    $mail->SMTPSecure = false;
    $mail->Port = 25;

    // This Setting is for testing it locally
    // $mail->Host = 'smtpout.secureserver.net';
    // $mail->SMTPAuth = true;
    // $mail->Username = 'sales@hyresvard.com';
    // $mail->Password = 'Mybossrocks081677!';
    // $mail->SMTPSecure = 'ssl';
    // $mail->Port = 465;

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
            <div class='email-header'>New Order Shipped</div>
            <div class='email-content'>
                <p>An order has been successfully shipped.</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(45, 15, 94); font-size: 18px;'>$order_id</span></p>
                <p>Please log in to the admin panel to view order details.</p>
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
            <div class='email-header'>Your Order Has Been Shipped!</div>
            <div class='email-content'>
                <p>We’re happy to inform you that your order has been shipped.</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(17, 21, 74); font-size: 18px;'>$order_id</span></p>
                <p>You can track your order status in your account.</p>
                <p>Thank you for shopping with us!</p>
            </div>
            <div class='email-footer'>VetAID Online - Order Shipped Confirmation</div>
        </div>

    </body>
    </html>";

    $mail->send();
}
