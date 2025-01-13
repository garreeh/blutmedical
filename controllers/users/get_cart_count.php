<?php
session_start();
include '../../connections/connections.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // Query to count the number of cart items (cart_id) for the logged-in user
  $cart_query = "SELECT COUNT(cart_id) as cart_count FROM cart WHERE user_id = '$user_id' AND cart_status = 'Cart'";
  $cart_result = $conn->query($cart_query);

  if ($cart_result && $cart_result->num_rows > 0) {
    $cart_row = $cart_result->fetch_assoc();
    $cart_count = $cart_row['cart_count'] ? (int) $cart_row['cart_count'] : 0;

    // Send the cart count as a JSON response
    echo json_encode(['cart_count' => $cart_count]);
  } else {
    // No items in the cart or error in query
    echo json_encode(['cart_count' => 0]);
  }
} else {
  // User not logged in
  echo json_encode(['error' => 'User not logged in.']);
}

exit();
