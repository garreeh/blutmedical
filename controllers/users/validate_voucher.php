<?php

session_start();
include '../../connections/connections.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
  echo json_encode([
    'status' => false,
    'message' => 'User not authenticated.'
  ]);
  exit;
}

$voucher_code = mysqli_real_escape_string($conn, $_POST['voucher_code']);
$cart_total = (float) $_POST['cart_total'];

// ==========================================
// GET TOTAL ORDER QUANTITY
// ==========================================
$totalQty = 0;

$cartSql = "SELECT SUM(cart_quantity) AS total_quantity
            FROM cart
            WHERE user_id = '$user_id'
            AND cart_status = 'Cart'";

$cartResult = mysqli_query($conn, $cartSql);

if ($cartResult && mysqli_num_rows($cartResult) > 0) {
  $cartRow = mysqli_fetch_assoc($cartResult);
  $totalQty = (int) $cartRow['total_quantity'];
}

// ==========================================
// FIND VOUCHER
// ==========================================
$sql = "SELECT *
        FROM voucher
        WHERE voucher_code = '$voucher_code'
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
  echo json_encode([
    'status' => false,
    'message' => 'Voucher not found.'
  ]);
  exit;
}

$row = mysqli_fetch_assoc($result);

// ==========================================
// CHECK STATUS
// ==========================================
if ($row['voucher_status'] != 'Active') {
  echo json_encode([
    'status' => false,
    'message' => 'Voucher is inactive.'
  ]);
  exit;
}

// ==========================================
// CHECK MINIMUM ORDER QUANTITY
// ==========================================
if ($totalQty < $row['minimum_cart']) {
  echo json_encode([
    'status' => false,
    'message' => 'This voucher requires a minimum order of ' . $row['minimum_cart'] . ' item(s). Your cart currently has ' . $totalQty . ' item(s).'
  ]);
  exit;
}

// ==========================================
// COMPUTE DISCOUNT
// ==========================================
$voucher_percentage = (float) $row['voucher_percentage'];
$discount = ($cart_total * $voucher_percentage) / 100;

// ==========================================
// SUCCESS
// ==========================================
echo json_encode([
  'status' => true,
  'message' => 'Voucher applied successfully!',
  'voucher_id' => $row['voucher_id'],
  'voucher_percentage' => $voucher_percentage,
  'discount' => round($discount, 2),
  'total_quantity' => $totalQty
]);