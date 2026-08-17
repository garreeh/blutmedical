<?php
include '../../connections/connections.php';

session_start();

// PayPal Sandbox
// $paypalClientId = 'AfcJOedIT9WM3IBgUd8D4uEiAXppkMsftrR2DRtcm8CUco5sptEShId2hujHrtNd_FK7gzOyzbV53zsX';
// $paypalSecret = 'EGS6Unh1tDJqJZlDz452qIXxa6i5XbHx9ZRg0vHhI6MZWT7QWWlu70KGTWuW6TnEIXJGN01ZGPL__KwM';

// LIVE
$paypalClientId = 'AR4DFDz9j-s1s4O9bvAfIqeKsDHD8b-q-rPUW7Ay4hm5L_O9K02gyoze73IF1tEA09CF6vm6v1BCBq9D';
$paypalSecret = 'EONgTKQHhxWDbJVG3VpsHg1_L7ZMilG2tHlVkKFjvXVUwsFPmm3BRrsLOx9h-SzPktKpb3jS1UTiDwrt';

// Get PayPal API access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ':' . $paypalSecret);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/x-www-form-urlencoded',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');


// Get PayPal API access token (LIVE)
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ':' . $paypalSecret);
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//   'Content-Type: application/x-www-form-urlencoded',
// ]);
// curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

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
  echo json_encode([
    'success' => false,
    'message' => 'User not logged in'
  ]);
  exit;
}

$user_id = $_SESSION['user_id'];

$paymentMethod = $data['paymentCategory'] ?? 'PayPal';
$voucher_id = isset($data['voucher_id']) ? (int) $data['voucher_id'] : 0;
$paypal_order_id = $data['orderID'] ?? null;

// Start transaction
mysqli_begin_transaction($conn);

try {

  $cartItemsSql = "
        SELECT
            c.product_id,
            c.cart_quantity,
            p.product_sellingprice
        FROM cart c
        INNER JOIN product p
            ON c.product_id = p.product_id
        WHERE c.user_id = '$user_id'
        AND c.cart_status = 'Cart'
    ";

  $result = mysqli_query($conn, $cartItemsSql);

  if (!$result || mysqli_num_rows($result) == 0) {
    throw new Exception('No items found in cart.');
  }

  $totalAmount = 0;

  while ($row = mysqli_fetch_assoc($result)) {
    $price = (float) $row['product_sellingprice'];
    $qty = (int) $row['cart_quantity'];

    $totalAmount += ($price * $qty);
  }

  // ==========================
  // APPLY VOUCHER
  // ==========================
  $discountAmount = 0;

  if ($voucher_id > 0) {

    $voucherSql = "
            SELECT *
            FROM voucher
            WHERE voucher_id = '$voucher_id'
            AND voucher_status = 'Active'
            LIMIT 1
        ";

    $voucherResult = mysqli_query($conn, $voucherSql);

    if ($voucherResult && mysqli_num_rows($voucherResult) > 0) {

      $voucher = mysqli_fetch_assoc($voucherResult);

      $voucherPercent = (float) $voucher['voucher_percentage'];

      $discountAmount = ($totalAmount * $voucherPercent) / 100;

      $totalAmount -= $discountAmount;

      if ($totalAmount < 0) {
        $totalAmount = 0;
      }

    } else {
      $voucher_id = 0;
    }
  }

  // ==========================
  // UPDATE CART
  // ==========================
  $updateCartSql = "
        UPDATE cart
        SET
            cart_status = 'Processing',
            payment_method = '$paymentMethod',
            payment_status = 'Unpaid',
            paypal_order_id = " . ($paypal_order_id ? "'$paypal_order_id'" : "NULL") . ",
            voucher_id = '$voucher_id'
        WHERE user_id = '$user_id'
        AND cart_status = 'Cart'
    ";

  if (!mysqli_query($conn, $updateCartSql)) {
    throw new Exception(mysqli_error($conn));
  }

  mysqli_commit($conn);

  echo json_encode([
    'success' => true,
    'message' => 'Checkout successful.',
    'paypal_order_id' => $paypal_order_id,
    'subtotal' => round($totalAmount + $discountAmount, 2),
    'discount' => round($discountAmount, 2),
    'final_total' => round($totalAmount, 2),
    'voucher_id' => $voucher_id
  ]);

  exit;

} catch (Exception $e) {

  mysqli_rollback($conn);

  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);

  exit;
}

// Output the JSON response
echo json_encode($response);