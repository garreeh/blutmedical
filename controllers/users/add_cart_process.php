<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User not logged in
    $response = array('success' => false, 'message' => 'You are not logged in.');
    echo json_encode($response);
    exit();
}

include '../../connections/connections.php';

if (isset($_POST['product_id']) && isset($_POST['cart_quantity'])) {
    // Get product_id from POST request and user_id from session
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $user_id = $_SESSION['user_id'];
    $cart_quantity = (int) $_POST['cart_quantity']; // Get the quantity from the POST data

    // Get the current stock and selling price of the product
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

    // Check if requested quantity exceeds stock
    if ($cart_quantity > $product_stock) {
        $response = array('success' => false, 'message' => 'Low on Stock. Available stock: ' . $product_stock);
        echo json_encode($response);
        exit();
    }

    // Check if the product is in the cart with status 'Cart'
    $cart_query = "SELECT cart_quantity, cart_status FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND cart_status = 'Cart'";
    $cart_result = $conn->query($cart_query);

    if ($cart_result->num_rows > 0) {
        // Product is already in the cart with status 'Cart', update the quantity
        $cart_row = $cart_result->fetch_assoc();
        $new_quantity = $cart_row['cart_quantity'] + $cart_quantity;

        // Check if the new quantity exceeds available stock
        if ($new_quantity > $product_stock) {
            $response = array('success' => false, 'message' => 'Not enough stock available.');
            echo json_encode($response);
            exit();
        }

        // Compute the new total price
        $total_price = $new_quantity * $product_price;

        // Update the quantity, total price, and cart status in the cart
        $update_query = "UPDATE cart SET cart_quantity = '$new_quantity', total_price = '$total_price', cart_status = 'Cart' WHERE user_id = '$user_id' AND product_id = '$product_id' AND cart_status = 'Cart'";
        if ($conn->query($update_query)) {
            $response = array('success' => true, 'message' => 'Cart updated successfully!');
        } else {
            $response = array('success' => false, 'message' => 'Error updating cart: ' . $conn->error);
        }
    } else {
        // If the product is in the cart but with status 'Processing'
        $cart_processing_query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND cart_status = 'Processing'";
        $cart_processing_result = $conn->query($cart_processing_query);

        if ($cart_processing_result->num_rows > 0) {
            // Product is already in the cart with status 'Processing', add a new cart entry with status 'Cart'
            if ($product_stock < 1) {
                $response = array('success' => false, 'message' => 'Not enough stock available.');
                echo json_encode($response);
                exit();
            }

            // Insert a new entry with quantity 1 for the same product but with 'Cart' status
            $total_price = $product_price;
            $insert_new_cart_query = "INSERT INTO cart (user_id, product_id, cart_quantity, total_price, cart_status) VALUES ('$user_id', '$product_id', 1, '$total_price', 'Cart')";
            if ($conn->query($insert_new_cart_query)) {
                $response = array('success' => true, 'message' => 'Product added to cart successfully!');
            } else {
                $response = array('success' => false, 'message' => 'Error adding product to cart: ' . $conn->error);
            }
        } else {
            // Product is not in the cart at all, add it with quantity 1
            if ($product_stock < 1) {
                $response = array('success' => false, 'message' => 'Not enough stock available.');
                echo json_encode($response);
                exit();
            }

            // Compute the total price
            $total_price = $product_price;

            // Insert the new product into the cart with cart_status set to 'Cart'
            $insert_query = "INSERT INTO cart (user_id, product_id, cart_quantity, total_price, cart_status) VALUES ('$user_id', '$product_id', $cart_quantity, '$total_price', 'Cart')";
            if ($conn->query($insert_query)) {
                $response = array('success' => true, 'message' => 'Product added to cart successfully!');
            } else {
                $response = array('success' => false, 'message' => 'Error adding product to cart: ' . $conn->error);
            }
        }
    }

    echo json_encode($response);
    exit();
} else {
    $response = array('success' => false, 'message' => 'No product ID provided.');
    echo json_encode($response);
    exit();
}
