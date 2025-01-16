<?php
include '../../connections/connections.php';
session_start();

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

  // Update the cart status for all items in 'Cart' for the current user
  $updateCartSql = "UPDATE cart SET 
                      cart_status = 'Processing', 
                      payment_method = '$paymentMethod',
                      payment_status = 'Unpaid'
                      WHERE user_id = '$user_id' AND cart_status = 'Cart'";

  // Execute the cart status update for all items
  if (!mysqli_query($conn, $updateCartSql)) {
    throw new Exception('Failed to update cart status for all items');
  }

  // Commit the transaction
  mysqli_commit($conn);

  // Success response
  $response['success'] = true;
  $response['message'] = 'Checkout successful for all items in the cart';
} catch (Exception $e) {
  // Rollback the transaction on error
  mysqli_rollback($conn);
  $response['message'] = $e->getMessage();
}

// Output the JSON response
echo json_encode($response);