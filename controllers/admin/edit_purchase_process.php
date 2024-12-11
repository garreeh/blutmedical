<?php

include './../../connections/connections.php';

if (isset($_POST['edit_purchase'])) {
    $purchase_order_id = $conn->real_escape_string($_POST['purchase_order_id']);
    $new_quantity = $conn->real_escape_string($_POST['quantity']);

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Get the current quantity for the purchase order
        $sql = "SELECT quantity, product_id FROM purchase_order WHERE purchase_order_id = '$purchase_order_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $current_quantity = $row['quantity'];
            $product_id = $row['product_id'];

            // Calculate the quantity difference
            $quantity_diff = $new_quantity - $current_quantity;

            // Check if the product stock is sufficient
            $stock_check_sql = "SELECT product_stocks FROM product WHERE product_id = '$product_id'";
            $stock_check_result = mysqli_query($conn, $stock_check_sql);
            $stock_row = mysqli_fetch_assoc($stock_check_result);
            $current_stocks = $stock_row['product_stocks'];

            if (($current_stocks + $quantity_diff) < 0) {
                // Insufficient stock, rollback and return an error
                mysqli_rollback($conn);
                $response = array('success' => false, 'message' => 'Insufficient stock to adjust the quantity.');
                echo json_encode($response);
                exit;
            }

            // Update the purchase order with the new quantity
            $update_purchase_sql = "UPDATE purchase_order SET quantity = '$new_quantity' WHERE purchase_order_id = '$purchase_order_id'";
            if (mysqli_query($conn, $update_purchase_sql)) {
                
                // Debugging - Check the quantity difference and current stocks
                error_log("Quantity Difference: $quantity_diff");
                error_log("Current Stocks before update: $current_stocks");

                // Update the product stock
                $update_stock_sql = "UPDATE product SET product_stocks = product_stocks + '$quantity_diff' WHERE product_id = '$product_id'";
                if (mysqli_query($conn, $update_stock_sql)) {
                    // Commit transaction
                    mysqli_commit($conn);

                    // Debugging - Check the new product stocks
                    $new_stock_check_sql = "SELECT product_stocks FROM product WHERE product_id = '$product_id'";
                    $new_stock_check_result = mysqli_query($conn, $new_stock_check_sql);
                    $new_stock_row = mysqli_fetch_assoc($new_stock_check_result);
                    error_log("New Stocks after update: " . $new_stock_row['product_stocks']);

                    // Success response
                    $response = array('success' => true, 'message' => 'Purchase order and product stock updated successfully!');
                    echo json_encode($response);
                } else {
                    // Rollback transaction if stock update fails
                    mysqli_rollback($conn);
                    $response = array('success' => false, 'message' => 'Error updating product stock: ' . mysqli_error($conn));
                    echo json_encode($response);
                }
            } else {
                // Rollback transaction if purchase order update fails
                mysqli_rollback($conn);
                $response = array('success' => false, 'message' => 'Error updating purchase order: ' . mysqli_error($conn));
                echo json_encode($response);
            }
        } else {
            // No purchase order found, return an error
            $response = array('success' => false, 'message' => 'Purchase order not found.');
            echo json_encode($response);
        }
    } catch (Exception $e) {
        // Rollback transaction on exception
        mysqli_rollback($conn);
        $response = array('success' => false, 'message' => 'Transaction failed: ' . $e->getMessage());
        echo json_encode($response);
    }
}
?>
