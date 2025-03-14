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
    WHERE cart.user_id = '$user_id' AND cart_status = 'Cart'
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


sendUserEmail($userEmail, "Blut Medical Shopping", $cartItems);

$response['success'] = true;
echo json_encode($response);

// Function to send user email
function sendUserEmail($toEmail, $subject, $cartItems)
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
            <div class='email-header'>🛒 Don't Forget Your Items!</div>
            <div class='email-content'>
                <p><strong>You left something in your cart!</strong></p>
                <p>Great deals await you—your selected items are still available.</p>
                <p>Complete your purchase before they're gone!</p>

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
