<?php
session_start();
include '../../connections/connections.php';

// Initialize the response array
$response = [
  'success' => false,
  'items' => [],
  'total_items' => 0,
  'total_price' => 0.0,
  'message' => ''
];

try {
  if (isset($_SESSION['user_id'])) {
    // User is logged in, fetch data from the database
    $user_id = $conn->real_escape_string($_SESSION['user_id']);

    $query = "SELECT product.*, cart.*, variations.`value`, variations.variation_id, variations.price
                  FROM cart
                  LEFT JOIN product ON cart.product_id = product.product_id
                  LEFT JOIN variations ON cart.variation_id = variations.variation_id
                  WHERE cart.user_id = '$user_id' AND cart.cart_status = 'Delivered'";

    $result = $conn->query($query);

    if (!$result) {
      throw new Exception("Database query failed: " . $conn->error);
    }

    $cart_items = [];
    $total_price = 0.0;

    while ($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
      $total_price += $row['total_price'];
    }

    $response['success'] = true;
    $response['items'] = $cart_items;
    $response['total_items'] = count($cart_items);
    $response['total_price'] = $total_price;
  }
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
}

// Return the response as JSON
echo json_encode($response);
