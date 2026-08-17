<?php
session_start();
include '../../connections/connections.php';

// Initialize the response array
$response = [
    'success' => false,
    'items' => [],
    'vouchers' => [],
    'total_items' => 0,
    'total_price' => 0.0,
    'message' => ''
];

try {

    // Get all vouchers
    $voucherQuery = "SELECT * FROM voucher";
    $voucherResult = $conn->query($voucherQuery);

    if (!$voucherResult) {
        throw new Exception("Voucher query failed: " . $conn->error);
    }

    while ($voucherRow = $voucherResult->fetch_assoc()) {
        $response['vouchers'][] = $voucherRow;
    }

    if (isset($_SESSION['user_id'])) {

        // User is logged in
        $user_id = $conn->real_escape_string($_SESSION['user_id']);

        // Fetch cart items
        $query = "SELECT
                    product.*,
                    cart.*,
                    variations.value,
                    variations.variation_id,
                    variations.price,
                    variations_colors.color
                  FROM cart
                  LEFT JOIN product
                    ON cart.product_id = product.product_id
                  LEFT JOIN variations
                    ON cart.variation_id = variations.variation_id
                  LEFT JOIN variations_colors
                    ON cart.variation_color_id = variations_colors.variation_color_id
                  WHERE cart.user_id = '$user_id'
                    AND cart.cart_status = 'Cart'";

        $result = $conn->query($query);

        if (!$result) {
            throw new Exception("Database query failed: " . $conn->error);
        }

        $cart_items = [];
        $total_price = 0;

        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
            $total_price += (float) $row['total_price'];
        }

        $response['success'] = true;
        $response['items'] = $cart_items;
        $response['total_items'] = count($cart_items);
        $response['total_price'] = $total_price;

    } else {
        $response['message'] = 'User is not logged in.';
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);