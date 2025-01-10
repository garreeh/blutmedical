<?php
session_start();

// if (!isset($_SESSION['user_id'])) {
//     // User not logged in
//     $response = array('success' => false, 'message' => 'You are not logged in.');
//     echo json_encode($response);
//     exit();
// }

include '../../connections/connections.php';

// Read and decode the JSON payload from the request body
$requestData = json_decode(file_get_contents('php://input'), true);

// Check if the required fields are present
if (isset($requestData['product_id']) && isset($requestData['cart_quantity'])) {
    $product_id = $conn->real_escape_string($requestData['product_id']);
    $cart_quantity = (int) $requestData['cart_quantity'];

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $product_query = "SELECT product_stocks, product_sellingprice FROM product WHERE product_id = '$product_id'";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();
            $product_stock = $product_row['product_stocks'];
            $product_price = $product_row['product_sellingprice'];
        } else {
            $response = array('success' => false, 'message' => 'Product not found.');
            echo json_encode($response);
            exit();
        }

        if ($cart_quantity > $product_stock) {
            $response = array('success' => false, 'message' => 'Low on Stock. Available stock: ' . $product_stock);
            echo json_encode($response);
            exit();
        }

        $cart_query = "SELECT cart_quantity, cart_status FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND cart_status = 'Cart'";
        $cart_result = $conn->query($cart_query);

        if ($cart_result->num_rows > 0) {
            $cart_row = $cart_result->fetch_assoc();
            $new_quantity = $cart_row['cart_quantity'] + $cart_quantity;

            if ($new_quantity > $product_stock) {
                $response = array('success' => false, 'message' => 'Not enough stock available.');
                echo json_encode($response);
                exit();
            }

            $total_price = $new_quantity * $product_price;

            $update_query = "UPDATE cart SET cart_quantity = '$new_quantity', total_price = '$total_price', cart_status = 'Cart' WHERE user_id = '$user_id' AND product_id = '$product_id' AND cart_status = 'Cart'";
            if ($conn->query($update_query)) {
                $response = array('success' => true, 'message' => 'Cart updated successfully!');
            } else {
                $response = array('success' => false, 'message' => 'Error updating cart: ' . $conn->error);
            }
        } else {
            $total_price = $cart_quantity * $product_price;

            $insert_query = "INSERT INTO cart (user_id, product_id, cart_quantity, total_price, cart_status) VALUES ('$user_id', '$product_id', $cart_quantity, '$total_price', 'Cart')";
            if ($conn->query($insert_query)) {
                $response = array('success' => true, 'message' => 'Product added to cart successfully!');
            } else {
                $response = array('success' => false, 'message' => 'Error adding product to cart: ' . $conn->error);
            }
        }
    } else {
        $response = array('success' => false, 'message' => 'User not logged in.');
    }

    echo json_encode($response);
    exit();
} else {
    $response = array('success' => false, 'message' => 'No product ID or quantity provided.');
    echo json_encode($response);
    exit();
}

