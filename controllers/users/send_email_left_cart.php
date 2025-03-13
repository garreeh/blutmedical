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

// Fetch user email
$userEmailSql = "SELECT user_email FROM users WHERE user_id = '$user_id'";
$userEmailResult = mysqli_query($conn, $userEmailSql);

if (!$userEmailResult || mysqli_num_rows($userEmailResult) === 0) {
  throw new Exception('Failed to fetch user email');
}

$userRow = mysqli_fetch_assoc($userEmailResult);
$userEmail = $userRow['user_email'];
// $userEmail = "gajultos.garrydev@gmail.com";;

mysqli_commit($conn);


sendUserEmail($userEmail, "Blut Medical Shopping", $order_id);

$response['success'] = true;
echo json_encode($response);

// Function to send user email
function sendUserEmail($toEmail, $subject, $order_id)
{
  $mail = new PHPMailer;
  $mail->IsSMTP();
  // Godaddy Live settings (commented out)
  // $mail->Host = 'relay-hosting.secureserver.net';
  // $mail->SMTPAuth = false;
  // $mail->Username = 'admin@vetaidonline.info';
  // $mail->Password = 'Mybossrocks081677!';
  // $mail->SMTPSecure = false;
  // $mail->Port = 25;

  // Local testing settings
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

  // Construct the email body
  $mail->Body = "
    <html>
    <head>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                background-color: #f4f4f4;
                padding: 20px;
            }
            .email-container {
                max-width: 600px;
                margin: auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            .email-header {
                background-color: #291664;
                color: white;
                padding: 15px;
                font-size: 22px;
                font-weight: bold;
                border-radius: 8px 8px 0 0;
            }
            .email-content {
                padding: 20px;
                color: #333;
                font-size: 16px;
                line-height: 1.6;
            }
            .email-button {
                display: inline-block;
                padding: 12px 20px;
                margin: 20px 0;
                font-size: 16px;
                color: white !important;
                background-color: #007bff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .email-button:hover {
                background-color: #0056b3;
            }
            .email-footer {
                margin-top: 20px;
                font-size: 14px;
                color: #777;
                padding-top: 15px;
                border-top: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>🛒 Don't Forget Your Items!</div>
            <div class='email-content'>
                <p><strong>You left something in your cart!</strong></p>
                <p>Great deals await you—your selected items are still available.</p>
                <p>Complete your purchase before they're gone!</p>
                <a href='https://blutmedical.com/' class='email-button'>Return to Your Cart</a>
            </div>
            <div class='email-footer'>
                <p>Need help? <a href='mailto:support@blutmedical.com' style='color: #007bff; text-decoration: none;'>Contact Support</a></p>
                <p>© " . date('Y') . " BlutMedical. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>";


  $mail->send();
}
