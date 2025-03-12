<?php
include '../../connections/connections.php';
// require './../../assets/PHPMailer/src/Exception.php';
// require './../../assets/PHPMailer/src/PHPMailer.php';
// require './../../assets/PHPMailer/src/SMTP.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

session_start();

// PayPal Sandbox
// $paypalClientId = 'AfcJOedIT9WM3IBgUd8D4uEiAXppkMsftrR2DRtcm8CUco5sptEShId2hujHrtNd_FK7gzOyzbV53zsX';
// $paypalSecret = 'EGS6Unh1tDJqJZlDz452qIXxa6i5XbHx9ZRg0vHhI6MZWT7QWWlu70KGTWuW6TnEIXJGN01ZGPL__KwM';

// LIVE
$paypalClientId = 'AR4DFDz9j-s1s4O9bvAfIqeKsDHD8b-q-rPUW7Ay4hm5L_O9K02gyoze73IF1tEA09CF6vm6v1BCBq9D';
$paypalSecret = 'EONgTKQHhxWDbJVG3VpsHg1_L7ZMilG2tHlVkKFjvXVUwsFPmm3BRrsLOx9h-SzPktKpb3jS1UTiDwrt';

// Get PayPal API access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ':' . $paypalSecret);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/x-www-form-urlencoded',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

$response = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpStatus == 200) {
  $paypalAccessToken = json_decode($response)->access_token;
} else {
  error_log("Failed to get PayPal API token", 3, 'ipn_error.log');
  http_response_code(400);
  exit;
}

// Read the API request body
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);


$response = array('success' => false, 'message' => '');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
  $response['message'] = 'User not logged in';
  echo json_encode($response);
  exit;
}

$user_id = $_SESSION['user_id'];
$paymentMethod = $_POST['paymentCategory'];

// Start a transaction to ensure data integrity
mysqli_begin_transaction($conn);

try {
  // Retrieve cart items for stock update (only those in 'Cart' status for the logged-in user)
  $cartItemsSql = "SELECT product_id, cart_quantity FROM cart 
                     WHERE user_id = '$user_id' AND cart_status = 'Cart'";
  $result = mysqli_query($conn, $cartItemsSql);
  if (!$result || mysqli_num_rows($result) === 0) {
    // throw new Exception('No items in the cart to checkout.');
  }

  // $paypal_order_id = ($paymentMethod === 'Paypal') ? $row['paypal_order_id'] : $reference_no;

  $paypal_order_id = isset($_POST['orderID']) ? $_POST['orderID'] : null;

  // Update the cart status for all items in 'Cart' for the current user
  $updateCartSql = "UPDATE cart SET 
                      cart_status = 'Processing', 
                      payment_method = '$paymentMethod',
                      payment_status = 'Unpaid',
                      paypal_order_id = '$paypal_order_id'
                      WHERE user_id = '$user_id' AND cart_status = 'Cart'";

  // Execute the cart status update for all items
  if (!mysqli_query($conn, $updateCartSql)) {
    throw new Exception('Failed to update cart status for all items');
  }

  mysqli_commit($conn);

  $response = [
    'success' => true,
    'message' => 'Checkout successful',
    'paypal_order_id' => $paypal_order_id // Ensure this variable has a valid value
  ];

} catch (Exception $e) {
  // Rollback the transaction on error
  mysqli_rollback($conn);
  $response['message'] = $e->getMessage();
}

// Output the JSON response
echo json_encode($response);

// Function to send admin email
// function sendAdminEmail($toEmail, $subject, $paypal_order_id)
// {
//   $mail = new PHPMailer;
//   $mail->IsSMTP();
//   $mail->Host = 'smtpout.secureserver.net';
//   $mail->SMTPAuth = true;
//   $mail->Username = 'sales@hyresvard.com';
//   $mail->Password = 'Mybossrocks081677!';
//   $mail->SMTPSecure = 'ssl';
//   $mail->Port = 465;

//   $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
//   $mail->addAddress($toEmail);
//   $mail->isHTML(true);
//   $mail->Subject = $subject;

//   $mail->Body = "
//     <html>
//     <head>
//         <style>
//             body { font-family: Arial, sans-serif; }
//             .email-container {
//                 background-color: #f5f5f5;
//                 padding: 20px;
//                 text-align: center;
//                 border-radius: 5px;
//             }
//             .email-header {
//                 background-color:rgb(24, 13, 105);
//                 color: white;
//                 padding: 10px;
//                 font-size: 18px;
//                 font-weight: bold;
//             }
//             .email-content {
//                 padding: 15px;
//                 background-color: white;
//                 border-radius: 5px;
//             }
//             .email-footer {
//                 margin-top: 20px;
//                 font-size: 12px;
//                 color: #777;
//             }
//         </style>
//     </head>
//     <body>
//         <div class='email-container'>
//             <div class='email-header'>New Order Received</div>
//             <div class='email-content'>
//                 <p>You have received a new order.</p>
//                 <p><strong>Order ID:</strong> <span style='color:rgb(45, 15, 94); font-size: 18px;'>$paypal_order_id</span></p>
//                 <p>Please check the admin panel for details.</p>
//             </div>
//             <div class='email-footer'>VetAID Online - Admin Notification</div>
//         </div>
//     </body>
//     </html>";

//   $mail->send();
// }

// // Function to send user email
// function sendUserEmail($toEmail, $subject, $paypal_order_id)
// {
//   $mail = new PHPMailer;
//   $mail->IsSMTP();
//   $mail->Host = 'smtpout.secureserver.net';
//   $mail->SMTPAuth = true;
//   $mail->Username = 'sales@hyresvard.com';
//   $mail->Password = 'Mybossrocks081677!';
//   $mail->SMTPSecure = 'ssl';
//   $mail->Port = 465;

//   $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
//   $mail->addAddress($toEmail);
//   $mail->isHTML(true);
//   $mail->Subject = $subject;

//   $mail->Body = "
//     <html>
//     <head>
//         <style>
//             body { font-family: Arial, sans-serif; }
//             .email-container {
//                 background-color: #f5f5f5;
//                 padding: 20px;
//                 text-align: center;
//                 border-radius: 5px;
//             }
//             .email-header {
//                 background-color:rgb(41, 22, 100);
//                 color: white;
//                 padding: 10px;
//                 font-size: 18px;
//                 font-weight: bold;
//             }
//             .email-content {
//                 padding: 15px;
//                 background-color: white;
//                 border-radius: 5px;
//             }
//             .email-footer {
//                 margin-top: 20px;
//                 font-size: 12px;
//                 color: #777;
//             }
//         </style>
//     </head>
//     <body>
//         <div class='email-container'>
//             <div class='email-header'>Order Confirmation</div>
//             <div class='email-content'>
//                 <p>Your order has been successfully placed!</p>
//                 <p><strong>Order ID:</strong> <span style='color:rgb(17, 21, 74); font-size: 18px;'>$paypal_order_id</span></p>
//                 <p>Thank you for shopping with us.</p>
//             </div>
//             <div class='email-footer'>VetAID Online - Order Confirmation</div>
//         </div>
//     </body>
//     </html>";

//   $mail->send();
// }
