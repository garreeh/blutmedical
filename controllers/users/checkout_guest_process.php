<?php
include '../../connections/connections.php';
session_start();

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

    $order_id = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));
    $cartData = $formData['localStorageItems'];
    $payment_category = $formData['payment_category'];
    $delivery_guest_fullname = isset($formData['fullname']) ? $formData['fullname'] : null;
    $delivery_address = isset($formData['address']) ? $formData['address'] : null;
    $delivery_guest_contact_number = isset($formData['contact_number']) ? $formData['contact_number'] : null;
    $delivery_guest_email = isset($formData['email']) ? $formData['email'] : null;

    // Handle localStorage data
    foreach ($cartData as $item) {
      $product_id = $item['product_id'];
      $cart_quantity = $item['cart_quantity'];
      $variation_id = $item['variation_id'];
      $variation_color_id = $item['variation_color_id'];

      $productPrice = ($variation_id === '-') ? $item['product_sellingprice'] : $item['price'];
      $productTotalPrice = $productPrice * $cart_quantity;

      // Insert into cart table
      $sql = "INSERT INTO cart (reference_no, product_id, cart_quantity, variation_id, total_price, cart_status, payment_method, delivery_guest_fullname, delivery_address, delivery_guest_contact_number, delivery_guest_email, payment_status, variation_color_id) 
                        VALUES ('$order_id', '$product_id', '$cart_quantity', '$variation_id', '$productTotalPrice', 'Processing', 'Cash on Delivery', '$delivery_guest_fullname', '$delivery_address', '$delivery_guest_contact_number', '$delivery_guest_email', 'Unpaid', '$variation_color_id')";

      if (!mysqli_query($conn, $sql)) {
        throw new Exception('Error saving cart: ' . mysqli_error($conn));
      }
    }

    echo json_encode([
      'status' => 'success',
      'message' => 'Items saved to cart successfully!',
      'order_id' => $order_id
    ]);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
