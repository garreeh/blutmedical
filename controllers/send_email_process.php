<?php
// include './../connections/connections.php';
require '../assets/PHPMailer/src/Exception.php';
require '../assets/PHPMailer/src/PHPMailer.php';
require '../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize the response array
$response = array('success' => false, 'message' => '');

// Retrieve form data
$fname = $_POST['fname'];
$lname = $_POST['lname'];

$senderEmail = $_POST['email']; // The email entered by the user in the form
$contact = $_POST['contact'];
$fullName = $fname . ' ' . $lname;
$messageContent = nl2br($_POST['message']); // Converts newlines to <br> for HTML

$mail = new PHPMailer;
$mail->IsSMTP(); // Enable SMTP
// $mail->SMTPDebug = 1; // Debugging: 1 = errors and messages, 2 = messages only : FOR LIVE

// This settings is for Godaddy Live
// $mail->Host = 'relay-hosting.secureserver.net';
// $mail->SMTPAuth = false;                                      // Enable SMTP authentication
// $mail->Username = 'admin@vetaidonline.info';             // SMTP username
// $mail->Password = 'Mybossrocks081677!';                        // SMTP password
// $mail->SMTPSecure = false;
// $mail->Port = 25;

// This Setting is for testing it locally

$mail->Host = 'smtp.office365.com';
$mail->SMTPAuth = true;                                      // Enable SMTP authentication
$mail->Username = 'admin@vetaidonline.info';             // SMTP username
$mail->Password = 'Mybossrocks081677!';                        // SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Set the sender's email address (from the form)
$mail->setFrom('admin@vetaidonline.info', 'Blut Medical');

$mail->addAddress('admin@vetaidonline.info'); // The GoDaddy email you're sending to

$mail->isHTML(true);
$mail->Subject = "Customer Query";

// Email content with header, body, and footer
$message = "
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333333;
    }
    .email-container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      border: 1px solid #dddddd;
      border-radius: 8px;
      overflow: hidden;
    }
    .email-header {
      background-color:rgb(22, 0, 150);
      padding: 20px;
      text-align: center;
      color: white;
    }
    .email-header img {
      max-width: 100%;
      height: auto;
    }
    .email-body {
      padding: 20px;
      background-color: #f9f9f9;
    }
    .email-footer {
      background-color:rgb(4, 6, 135);
      padding: 10px;
      text-align: center;
      color: white;
    }
    .email-footer p {
      margin: 0;
      font-size: 12px;
    }
  </style>
</head>
<body>
  <div class='email-container'>
    <div class='email-body'>
      <p><strong>Fullname:</strong> $fullName</p>
      <p><strong>Email:</strong> $senderEmail</p>
      <p><strong>Contact:</strong> $contact</p>
      <p><strong>Message:</strong></p>
      <p>$messageContent</p>
    </div>
    <div class='email-footer'>
      <p>&copy; " . date('Y') . " Blut Medical. All Rights Reserved.</p>
    </div>
  </div>
</body>
</html>
";

$mail->Body = $message;

// Send the email
if ($mail->send()) {
  $response['success'] = true;
  $response['message'] = 'Message sent successfully!';
} else {
  $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
}

// Return JSON response
echo json_encode($response);
