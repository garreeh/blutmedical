<?php
// include './../connections/connections.php';
require '../assets/PHPMailer/src/Exception.php';
require '../assets/PHPMailer/src/PHPMailer.php';
require '../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize the response array
$response = array('success' => false, 'message' => '');


$messageContent = nl2br($_POST['message']); // Converts newlines to <br> for HTML

$mail = new PHPMailer;
$mail->IsSMTP(); // Enable SMTP
// $mail->SMTPDebug = 1; // Debugging: 1 = errors and messages, 2 = messages only : FOR LIVE

$mail->Host = 'smtp.secureserver.net'; // GoDaddy SMTP server
$mail->Port = 465; // Gmail SMTP Port for SSL (or use 587 for TLS)
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl'; // Use 'ssl' for Port 465, 'tls' for Port 587
$mail->Username = 'admin@vetaidonline.info'; // Your Gmail email
$mail->Password = 'mimiRocks081677'; // Your Gmail app-specific password or regular password

// Set the sender's email address (from the form)
$mail->setFrom($senderEmail, $fullName);
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
      <p>&copy; " . date('Y') . " Blüt Medical. All Rights Reserved.</p>
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
?>