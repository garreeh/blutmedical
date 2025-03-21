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

// Extract orderID from JSON payload
$paypal_order_id = isset($input['orderID']) ? $input['orderID'] : null;
$guest_email = isset($input['email']) ? $input['email'] : null;


mysqli_commit($conn);

$sql = "
    SELECT 
        cart.*, 
        product.product_name, 
        variations.value AS variation_value, 
        variations.product_code, 
        variations_colors.color
    FROM cart
    LEFT JOIN product ON cart.product_id = product.product_id
    LEFT JOIN variations ON cart.variation_id = variations.variation_id
    LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
    WHERE cart.reference_no = '$paypal_order_id'
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    $response['message'] = 'No products found in cart';
    echo json_encode($response);
    exit;
}

// Store cart details in an array
$cartItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
}

// Send email notifications
$adminEmail = "admin@vetaidonline.info";
sendAdminEmail($adminEmail, "New Order Received", $paypal_order_id);
sendUserEmail($guest_email, "Order Confirmation", $paypal_order_id);

$response['success'] = true;

// Function to send admin email
function sendAdminEmail($toEmail, $subject, $paypal_order_id, $cartItems)
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

    $totalAmount = 0;
    $productDetails = "";

    foreach ($cartItems as $item) {
        $totalAmount += $item['total_price']; // Sum the total price

        $productDetails .= "
    <tr>
        <td style='padding: 10px; border: 1px solid #ddd;'>{$item['product_name']}</td>
        <td style='padding: 10px; border: 1px solid #ddd;'>{$item['cart_quantity']}</td>";

        // Only show variation value and product code if variation_id is not null
        if (!empty($item['variation_id'])) {
            $productDetails .= "
            <td style='padding: 10px; border: 1px solid #ddd;'>{$item['variation_value']}</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>{$item['product_code']}</td>";
        } else {
            $productDetails .= "<td colspan='2' style='padding: 10px; border: 1px solid #ddd;'>No variation</td>";
        }

        // Only show color if variation_color_id is not null
        if (!empty($item['variation_color_id'])) {
            $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>{$item['color']}</td>";
        } else {
            $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>No color</td>";
        }

        // Move total price to the rightmost column
        $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>$ " . number_format($item['total_price'], 2, '.', ',') . "</td>";
        $productDetails .= "</tr>";
    }

    $productDetails .= "
<tr>
    <td colspan='5' style='padding: 10px; border: 1px solid #ddd; text-align: right; font-weight: bold;'>Total Amount:</td>
    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>
        $ " . number_format($totalAmount, 2, '.', ',') . "
    </td>
</tr>";

    // Construct the email body
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
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>New Order Received</div>
            <div class='email-content'>
                <p>You have received a new order.</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(45, 15, 94); font-size: 18px;'>$paypal_order_id</span></p>
                <p>Please check the admin panel for details.</p>

                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Variation</th>
                        <th>Product Code</th>
                        <th>Color</th>
                        <th>Price</th>

                    </tr>
                    $productDetails
                </table>
            </div>
            <div class='email-footer'>VetAID Online - Admin Notification</div>
        </div>
    </body>
    </html>";

    $mail->send();
}

// Function to send user email
function sendUserEmail($toEmail, $subject, $paypal_order_id, $cartItems)
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

    $totalAmount = 0;
    $productDetails = "";

    foreach ($cartItems as $item) {
        $totalAmount += $item['total_price']; // Sum the total price

        $productDetails .= "
    <tr>
        <td style='padding: 10px; border: 1px solid #ddd;'>{$item['product_name']}</td>
        <td style='padding: 10px; border: 1px solid #ddd;'>{$item['cart_quantity']}</td>";

        // Only show variation value and product code if variation_id is not null
        if (!empty($item['variation_id'])) {
            $productDetails .= "
            <td style='padding: 10px; border: 1px solid #ddd;'>{$item['variation_value']}</td>
            <td style='padding: 10px; border: 1px solid #ddd;'>{$item['product_code']}</td>";
        } else {
            $productDetails .= "<td colspan='2' style='padding: 10px; border: 1px solid #ddd;'>No variation</td>";
        }

        // Only show color if variation_color_id is not null
        if (!empty($item['variation_color_id'])) {
            $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>{$item['color']}</td>";
        } else {
            $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>No color</td>";
        }

        // Move total price to the rightmost column
        $productDetails .= "<td style='padding: 10px; border: 1px solid #ddd;'>$ " . number_format($item['total_price'], 2, '.', ',') . "</td>";
        $productDetails .= "</tr>";
    }

    $productDetails .= "
<tr>
    <td colspan='5' style='padding: 10px; border: 1px solid #ddd; text-align: right; font-weight: bold;'>Total Amount:</td>
    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>
        $ " . number_format($totalAmount, 2, '.', ',') . "
    </td>
</tr>";

    // Construct the email body
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
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>Order Confirmation</div>
            <div class='email-content'>
                <p>Your order has been successfully placed!</p>
                <p><strong>Order ID:</strong> <span style='color:rgb(17, 21, 74); font-size: 18px;'>$paypal_order_id</span></p>
                <p>Thank you for shopping with us.</p>
                
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Variation</th>
                        <th>Product Code</th>
                        <th>Color</th>
                        <th>Price</th>

                    </tr>
                    $productDetails
                </table>
            </div>
            <div class='email-footer'>VetAID Online - Order Confirmation</div>
        </div>
    </body>
    </html>";

    $mail->send();
}
