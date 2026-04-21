<?php
include '../connections/connections.php';

require '../assets/PHPMailer/src/Exception.php';
require '../assets/PHPMailer/src/PHPMailer.php';
require '../assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_email = isset($_POST['user_email']) ? $conn->real_escape_string($_POST['user_email']) : '';

  if (empty($user_email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required.']);
    exit();
  }

  if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit();
  }

  // Check if email exists
  $sql = "SELECT user_fullname FROM users WHERE user_email = '$user_email'";
  $result = mysqli_query($conn, $sql);

  if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Email not found.']);
    exit();
  }

  $row = mysqli_fetch_assoc($result);
  $user_fullname = $row['user_fullname'];

  // Generate temporary password
  $temp_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
  $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

  // Update password in database
  $update_sql = "UPDATE users 
               SET user_password = '$hashed_password', user_confirm_password = '$temp_password' 
               WHERE user_email = '$user_email'";

  if (!mysqli_query($conn, $update_sql)) {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
    exit();
  }

  // Send temporary password via email
  $mail = new PHPMailer(true);
  try {
    // SMTP settings (GoDaddy example)
    $mail->isSMTP();
    // $mail->Host = 'smtpout.secureserver.net'; // Use your SMTP host
    // $mail->SMTPAuth = true;
    // $mail->Username = 'admin@vetaidonline.info';
    // $mail->Password = 'Mybossrocks081677!';
    // $mail->SMTPSecure = 'tls'; // or 'ssl'
    // $mail->Port = 587; // or 465 for SSL

    $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = 'admin@blutmedical.com';          // SMTP username
    $mail->Password = 'cmoagffhceslpzsz';
    // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = '465';

    // Recipient
    $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
    $mail->addAddress($user_email, $user_fullname);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Your Temporary Password';
    $mail->Body = "
            <p>Hi <b>{$user_fullname}</b>,</p>
            <p>You requested a password reset. Here is your temporary password:</p>
            <h3>{$temp_password}</h3>
            <p>Please login and change your password immediately for security.</p>
        ";

    $mail->send();
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Failed to send email: {$mail->ErrorInfo}"]);
    exit();
  }

  // Success
  echo json_encode(['success' => true, 'message' => 'Temporary password sent to your email.']);
  exit();
}
