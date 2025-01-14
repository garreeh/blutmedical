<?php
session_start();
include '../../connections/connections.php';

// Initialize an empty response array
$response = array();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to get cart items and related product details
    $query = "SELECT * 
              FROM cart
              LEFT JOIN product ON cart.product_id = product.product_id
              LEFT JOIN variations ON cart.variation_id = variations.variation_id
              WHERE user_id = '$user_id' AND cart_status = 'Cart'";

    $result = $conn->query($query);
    $cart_items = array();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    $total_items = count($cart_items);
    $total_price = 0;
    foreach ($cart_items as $item) {
        $total_price += $item['total_price'];
    }

    $response['success'] = true;
    $response['items'] = $cart_items;
    $response['total_items'] = $total_items;
    $response['total_price'] = $total_price;
} else {
    // Fetch cart items from localStorage if not logged in
    $guestCart = json_decode(file_get_contents('php://input'), true) ?? [];

    if (!empty($guestCart)) {
        $cart_items = array();
        $total_price = 0;

        foreach ($guestCart as $item) {
            $productPrice = isset($item['product_sellingprice']) ? floatval($item['product_sellingprice']) : 0;
            $cartQuantity = isset($item['cart_quantity']) ? intval($item['cart_quantity']) : 0;
            $variationPrice = isset($item['price']) ? floatval($item['price']) : $productPrice;

            $cart_items[] = array(
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'product_image' => $item['product_image'],
                'price' => $variationPrice,
                'variation_id' => isset($item['variation_id']) ? $item['variation_id'] : null,
                'value' => isset($item['value']) ? $item['value'] : '-',
                'cart_quantity' => $cartQuantity,
                'total_price' => $variationPrice * $cartQuantity
            );

            $total_price += $variationPrice * $cartQuantity;
        }

        $total_items = count($cart_items);

        $response['success'] = true;
        $response['items'] = $cart_items;
        $response['total_items'] = $total_items;
        $response['total_price'] = $total_price;
    } else {
        $response['success'] = false;
        $response['message'] = 'Cart is empty';
    }
}

echo json_encode($response);
