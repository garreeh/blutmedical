<?php
session_start();
include './../../connections/connections.php';

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT * FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.user_id = '$user_id' AND c.cart_status = 'Cart'";
$result = mysqli_query($conn, $sql);

$cartItems = [];
$totalPrice = 0;

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = [
      'product_name' => $row['product_name'],
      'cart_quantity' => $row['cart_quantity'],
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
