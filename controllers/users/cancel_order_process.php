<?php
include './../../connections/connections.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $cart_quantity = (int) $_POST['cart_quantity'];
    $cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);

    // Fetch current product stock
    $product_query = "SELECT product_stocks FROM product WHERE product_id = '$product_id'";
    $product_result = mysqli_query($conn, $product_query);

    if ($product_result && mysqli_num_rows($product_result) > 0) {
        $product_data = mysqli_fetch_assoc($product_result);
        $current_stocks = $product_data['product_stocks'];

        // Update product stocks by adding the canceled quantity
        $new_stocks = $current_stocks + $cart_quantity;

        mysqli_begin_transaction($conn);

        try {
            // Update product stocks
            $update_product_query = "UPDATE product SET product_stocks = '$new_stocks' WHERE product_id = '$product_id'";
            if (!mysqli_query($conn, $update_product_query)) {
                throw new Exception('Failed to update product stocks: ' . mysqli_error($conn));
            }
        
            // Delete the cart item
            $delete_order_query = "DELETE FROM cart WHERE cart_id = '$cart_id'";
            if (!mysqli_query($conn, $delete_order_query)) {
                throw new Exception('Failed to delete the order item: ' . mysqli_error($conn));
            }
        
            // Commit transaction
            mysqli_commit($conn);
        
            echo json_encode([
                'success' => true,
                'message' => 'Order canceled successfully and stocks updated.'
            ]);
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

mysqli_close($conn);
?>
