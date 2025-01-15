<?php
session_start();
include './../../connections/connections.php';

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT product.*, cart.*, variations.`value`, variations.variation_id, variations.price
        FROM cart
        LEFT JOIN product ON cart.product_id = product.product_id
        LEFT JOIN variations ON cart.variation_id = variations.variation_id
        WHERE cart.user_id = '$user_id' AND cart.cart_status = 'Cart'";
$result = mysqli_query($conn, $sql);

$cartItems = [];
$totalPrice = 0;

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = [
      'product_name' => $row['product_name'],
      'cart_quantity' => $row['cart_quantity'],
      'value' => $row['value'],
      'variation_id' => $row['variation_id'],
      'price' => $row['price'],
      'product_sellingprice' => floatval($row['product_sellingprice']),

    ];
    $totalPrice += $row['cart_quantity'] * $row['product_sellingprice'];
  }
}

echo json_encode([
  'success' => true,
  'cartItems' => $cartItems,
  'totalPrice' => $totalPrice
]);
