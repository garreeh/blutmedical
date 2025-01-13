<?php
session_start();
include '../../connections/connections.php';

// Retrieve product_id, cart_quantity, and variation_id from the POST request
if (isset($_POST['product_id']) && isset($_POST['cart_quantity'])) {
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $cart_quantity = (int) $_POST['cart_quantity'];
    $variation_id = isset($_POST['variation_id']) ? $conn->real_escape_string($_POST['variation_id']) : null;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Default product price
        $product_price = 0;

        // Query to fetch product details
        $product_query = "
            SELECT product_stocks, product_sellingprice 
            FROM product 
            WHERE product_id = '$product_id'
        ";
        $product_result = $conn->query($product_query);

        if ($product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();
            $product_price = $product_row['product_sellingprice'];

            // Check if a variation ID is provided and valid
            if ($variation_id) {
                $variation_query = "
                    SELECT price 
                    FROM variations 
                    WHERE product_id = '$product_id' 
                    AND variation_id = '$variation_id'
                ";
                $variation_result = $conn->query($variation_query);

                if ($variation_result->num_rows > 0) {
                    $variation_row = $variation_result->fetch_assoc();
                    $product_price = $variation_row['price']; // Use variation price
                }
            }
        } else {
            $response = array('success' => false, 'message' => 'Product not found.');
            echo json_encode($response);
            exit();
        }

        // Check if the product with the same variation already exists in the cart
        $cart_query = "
            SELECT cart_quantity, cart_status 
            FROM cart 
            WHERE user_id = '$user_id' 
            AND product_id = '$product_id' 
            AND variation_id = '$variation_id' 
            AND cart_status = 'Cart'
        ";
        $cart_result = $conn->query($cart_query);

        if ($cart_result->num_rows > 0) {
            // Product with the same variation already in the cart
            $cart_row = $cart_result->fetch_assoc();
            $new_quantity = $cart_row['cart_quantity'] + $cart_quantity;
            $total_price = $new_quantity * $product_price;

            $update_query = "
                UPDATE cart 
                SET cart_quantity = '$new_quantity', total_price = '$total_price'
                WHERE user_id = '$user_id' 
                AND product_id = '$product_id' 
                AND variation_id = '$variation_id' 
                AND cart_status = 'Cart'
            ";
            if ($conn->query($update_query)) {
                $response = array('success' => true, 'message' => 'Cart updated successfully!');
            } else {
                $response = array('success' => false, 'message' => 'Error updating cart: ' . $conn->error);
            }
        } else {
            // If no such product with the same variation, insert a new entry
            $total_price = $cart_quantity * $product_price;

            $insert_query = "
                INSERT INTO cart (user_id, product_id, cart_quantity, total_price, cart_status, variation_id) 
                VALUES ('$user_id', '$product_id', $cart_quantity, '$total_price', 'Cart', '$variation_id')
            ";
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
