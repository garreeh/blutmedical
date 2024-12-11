<?php
session_start();
include '../../connections/connections.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'message' => 'Not logged in.'));
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get cart items and related product details
$query = "SELECT *
          FROM cart c
          LEFT JOIN product p ON c.product_id = p.product_id
          WHERE c.user_id = '$user_id' AND c.cart_status IN ('Processing', 'Out For Delivery')";

$result = $conn->query($query);
$cart_items = array();

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

$total_items = count($cart_items);
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['cart_quantity'] * $item['product_sellingprice'];
}

$response = array(
    'success' => true,
    'items' => $cart_items,
    'total_items' => $total_items,
    'total_price' => $total_price
);

echo json_encode($response);
?>
