<?php
session_start();
include '../../connections/connections.php';

// Initialize the response array
$response = [
  'success' => false,
  'message' => ''
];

try {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Logged-in user
    $user_id = $conn->real_escape_string($_SESSION['user_id']);
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $variation_id = $conn->real_escape_string($_POST['variation_id']);
    $action = $conn->real_escape_string($_POST['action']); // 'increase' or 'decrease'

    // Fetch current quantity and price
    $query = "SELECT product.*, cart.*, variations.`value`, variations.variation_id, variations.price, product.product_sellingprice
              FROM cart
              LEFT JOIN product ON cart.product_id = product.product_id
              LEFT JOIN variations ON cart.variation_id = variations.variation_id
              WHERE user_id = '$user_id' AND cart.product_id = '$product_id' AND cart.variation_id = '$variation_id' AND cart_status = 'Cart'";
    $result = $conn->query($query);

    if (!$result) {
      throw new Exception("Database query failed: " . $conn->error);
    }

    $cartItem = $result->fetch_assoc();
    if ($cartItem) {
      $currentQuantity = (int)$cartItem['cart_quantity'];

      // Determine the price: use variation price if exists, otherwise use product_sellingprice
      $price = isset($cartItem['price']) ? (float)$cartItem['price'] : (float)$cartItem['product_sellingprice'];

      if ($action === 'increase') {
        $newQuantity = $currentQuantity + 1;
      } else {
        $newQuantity = max($currentQuantity - 1, 1);
      }

      $newTotalPrice = $newQuantity * $price;

      // Update the quantity and total_price in the cart
      $updateQuery = "UPDATE cart 
                      SET cart_quantity = '$newQuantity', total_price = '$newTotalPrice' 
                      WHERE user_id = '$user_id' AND product_id = '$product_id' AND variation_id = '$variation_id' AND cart_status = 'Cart'";
      if ($conn->query($updateQuery)) {
        $response['success'] = true;
        $response['new_total_price'] = $newTotalPrice; // Added the response for new total price
      } else {
        throw new Exception("Update failed: " . $conn->error);
      }
    } else {
      throw new Exception("Item not found in cart.");
    }
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
}

// Return the response as JSON
echo json_encode($response);
