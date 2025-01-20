<?php
include '../../connections/connections.php';

// PayPal API credentials (get these from PayPal developer dashboard)
$paypalClientId = 'AfcJOedIT9WM3IBgUd8D4uEiAXppkMsftrR2DRtcm8CUco5sptEShId2hujHrtNd_FK7gzOyzbV53zsX';
$paypalSecret = 'EGS6Unh1tDJqJZlDz452qIXxa6i5XbHx9ZRg0vHhI6MZWT7QWWlu70KGTWuW6TnEIXJGN01ZGPL__KwM';

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

// Validate the payment response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Decode the incoming JSON request data
    $jsonData = file_get_contents('php://input');
    if (!$jsonData) {
      throw new Exception('No data received.');
    }

    $formData = json_decode($jsonData, true);
    if (is_null($formData) || !isset($formData['localStorageItems'])) {
      throw new Exception('Missing required data');
    }

    $cartData = $formData['localStorageItems'];
    $delivery_guest_fullname = isset($formData['fullname']) ? $formData['fullname'] : null;
    $delivery_address = isset($formData['address']) ? $formData['address'] : null;
    $delivery_guest_contact_number = isset($formData['contact_number']) ? $formData['contact_number'] : null;
    $delivery_guest_email = isset($formData['email']) ? $formData['email'] : null;
    $paypal_order_id = $formData['orderID'];  // This comes from the payload
    $paypal_payer_id = $formData['payerID'];  // This comes from the payload
    $paypal_name = $formData['paypalPayerName'];  // This comes from the payload
    $paypal_email = $formData['paypalPayerEmail'];  // This comes from the payload
    $paypal_contact_number = $formData['paypalPayerContact'];  // This comes from the payload
    $paypal_address = $formData['paypalPayerAddress'];  // This comes from the payload
    $paypal_transaction_id = $formData['transaction_id'];  // This comes from the payload



    // Handle localStorage data
    foreach ($cartData as $item) {
      $product_id = $item['product_id'];
      $cart_quantity = $item['cart_quantity'];
      $variation_id = $item['variation_id'];
      $productPrice = ($variation_id === '-') ? $item['product_sellingprice'] : $item['price'];
      $productTotalPrice = $productPrice * $cart_quantity;

      // Insert into cart table
      $sql = "INSERT INTO cart (product_id, cart_quantity, variation_id, total_price, cart_status, payment_method, delivery_guest_fullname, delivery_address, delivery_guest_contact_number, delivery_guest_email, payment_status, paypal_order_id, paypal_payer_id, paypal_name, paypal_email, paypal_contact_number, paypal_address, paypal_transaction_id) 
                        VALUES ('$product_id', '$cart_quantity', '$variation_id', '$productTotalPrice', 'Processing', 'Paypal', '$delivery_guest_fullname', '$delivery_address', '$delivery_guest_contact_number', '$delivery_guest_email', 'Unpad', '$paypal_order_id', '$paypal_payer_id', '$paypal_name', '$paypal_email', '$paypal_contact_number', '$paypal_address', '$paypal_transaction_id')";

      if (!mysqli_query($conn, $sql)) {
        throw new Exception('Error saving cart: ' . mysqli_error($conn));
      }
    }

    echo json_encode(['status' => 'success', 'message' => 'Items saved to cart successfully!']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  // Log payment status if not approved
  error_log("Unhandled Payment Status: {$data['state']}", 3, 'ipn_error.log');
}
