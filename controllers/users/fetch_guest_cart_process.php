<?php
include '../../connections/connections.php';

// Get the guest cart data from the POST request
$guestCart = json_decode(file_get_contents('php://input'), true);

if (!empty($guestCart)) {
  try {
    // Convert the product IDs into a comma-separated string for the IN clause
    $productIds = array_map(function ($item) {
      return $item['product_id'];
    }, $guestCart);
    $productIdsStr = implode(',', $productIds);

    // SQL query to fetch product details from the database
    $query = "SELECT p.id, p.product_name, p.product_image, p.price, v.id AS variation_id, v.value
              FROM products p
              LEFT JOIN product_variations v ON p.id = v.product_id
              WHERE p.id IN ($productIdsStr)
              LIMIT 1";  // Ensures we get at least one product or none

    // Using mysqli_query instead of PDO::query
    $result = mysqli_query($db, $query);

    if (!$result) {
      error_log('MySQL Error: ' . mysqli_error($db));  // Log the SQL error
      echo json_encode([
        'success' => false,
        'message' => 'Database query failed.'
      ]);
      exit;
    }

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (empty($products)) {
      // No products found in the database
      echo json_encode([
        'success' => true,
        'cartItems' => []  // Return empty cart
      ]);
      exit;
    }

    $cartItems = [];

    foreach ($guestCart as $cartItem) {
      foreach ($products as $product) {
        if ($cartItem['product_id'] == $product['id']) {
          $cartItems[] = [
            'product_id' => $product['id'],
            'product_name' => $product['product_name'],
            'product_image' => $product['product_image'],
            'price' => $product['price'],
            'variation_id' => $product['variation_id'],
            'value' => $product['value'],
            'cart_quantity' => $cartItem['cart_quantity']
          ];
        }
      }
    }

    echo json_encode([
      'success' => true,
      'cartItems' => $cartItems
    ]);
  } catch (Exception $e) {
    error_log('Exception: ' . $e->getMessage());  // Log any exception
    echo json_encode([
      'success' => false,
      'message' => $e->getMessage()
    ]);
  }
} else {
  echo json_encode([
    'success' => false,
    'message' => 'No cart data received.'
  ]);
}
