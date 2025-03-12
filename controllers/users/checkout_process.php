<?php
include './../../connections/connections.php';
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
$paymentMethod = $_POST['paymentCategory'];

// Generate a random reference number (for COD and GCash)
function generateReferenceNo($length = 10)
{
    return strtoupper(bin2hex(random_bytes($length / 2)));
}

$reference_no = ($paymentMethod !== 'Paypal') ? generateReferenceNo() : null;

// Start transaction
mysqli_begin_transaction($conn);

try {
    $cartItemsSql = "SELECT cart_id, product_id, cart_quantity, paypal_order_id FROM cart 
                     WHERE user_id = '$user_id' AND cart_status = 'Cart'";
    $result = mysqli_query($conn, $cartItemsSql);

    if (!$result || mysqli_num_rows($result) === 0) {
        throw new Exception('No items in the cart to checkout.');
    }

    $row = mysqli_fetch_assoc($result);
    $order_id = ($paymentMethod === 'Paypal') ? $row['paypal_order_id'] : $reference_no;

    $updateCartSql = "UPDATE cart SET 
                      cart_status = 'Processing', 
                      payment_method = '$paymentMethod',
                      payment_status = 'Unpaid',
                      reference_no = '$order_id'
                      WHERE user_id = '$user_id' AND cart_status = 'Cart'";

    if (!mysqli_query($conn, $updateCartSql)) {
        throw new Exception('Failed to update cart status');
    }


    mysqli_commit($conn);


    $response['success'] = true;
    $response = [
        'success' => true,
        'message' => 'Checkout successful',
        'order_id' => $order_id // Ensure this variable has a valid value
    ];
} catch (Exception $e) {
    mysqli_rollback($conn);
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// Function to send admin email
// function sendAdminEmail($toEmail, $subject, $order_id)
// {
//     $mail = new PHPMailer;
//     $mail->IsSMTP();
//     $mail->Host = 'smtpout.secureserver.net';
//     $mail->SMTPAuth = true;
//     $mail->Username = 'sales@hyresvard.com';
//     $mail->Password = 'Mybossrocks081677!';
//     $mail->SMTPSecure = 'ssl';
//     $mail->Port = 465;

//     $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
//     $mail->addAddress($toEmail);
//     $mail->isHTML(true);
//     $mail->Subject = $subject;

//     $mail->Body = "
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
//                 <p><strong>Order ID:</strong> <span style='color:rgb(45, 15, 94); font-size: 18px;'>$order_id</span></p>
//                 <p>Please check the admin panel for details.</p>
//             </div>
//             <div class='email-footer'>VetAID Online - Admin Notification</div>
//         </div>
//     </body>
//     </html>";

//     $mail->send();
// }

// // Function to send user email
// function sendUserEmail($toEmail, $subject, $order_id)
// {
//     $mail = new PHPMailer;
//     $mail->IsSMTP();
//     $mail->Host = 'smtpout.secureserver.net';
//     $mail->SMTPAuth = true;
//     $mail->Username = 'sales@hyresvard.com';
//     $mail->Password = 'Mybossrocks081677!';
//     $mail->SMTPSecure = 'ssl';
//     $mail->Port = 465;

//     $mail->setFrom('admin@vetaidonline.info', 'VetAID Online');
//     $mail->addAddress($toEmail);
//     $mail->isHTML(true);
//     $mail->Subject = $subject;

//     $mail->Body = "
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
//                 <p><strong>Order ID:</strong> <span style='color:rgb(17, 21, 74); font-size: 18px;'>$order_id</span></p>
//                 <p>Thank you for shopping with us.</p>
//             </div>
//             <div class='email-footer'>VetAID Online - Order Confirmation</div>
//         </div>
//     </body>
//     </html>";

//     $mail->send();
// }
