<?php

include '../../connections/connections.php';

if (isset($_POST['add_purchase'])) {
    // Get form data
    $purchase_number = $conn->real_escape_string($_POST['purchase_number']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $supplier_id = $conn->real_escape_string($_POST['supplier_id']);
    $product_id = $conn->real_escape_string($_POST['product_id']);

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Construct SQL query for inserting purchase order
        $sql = "INSERT INTO `purchase_order` (purchase_number, quantity, supplier_id, product_id)
                VALUES ('$purchase_number', '$quantity', '$supplier_id', '$product_id')";

        // Execute SQL query
        if (mysqli_query($conn, $sql)) {
            // Update product quantity
            $update_sql = "UPDATE `product` SET product_stocks = product_stocks + '$quantity' WHERE product_id = '$product_id'";
            if (mysqli_query($conn, $update_sql)) {
                // Commit transaction
                mysqli_commit($conn);

                // Success response
                $response = array('success' => true, 'message' => 'Purchase Added and Product Quantity Updated successfully!');
                echo json_encode($response);
            } else {
                // Rollback transaction if update fails
                mysqli_rollback($conn);

                // Error response
                $response = array('success' => false, 'message' => 'Error Updating Product Quantity: ' . mysqli_error($conn));
                echo json_encode($response);
            }
        } else {
            // Rollback transaction if insert fails
            mysqli_rollback($conn);

            // Error response
            $response = array('success' => false, 'message' => 'Error Adding Purchase: ' . mysqli_error($conn));
            echo json_encode($response);
        }
    } catch (Exception $e) {
        // Rollback transaction on exception
        mysqli_rollback($conn);

        // Error response
        $response = array('success' => false, 'message' => 'Transaction Failed: ' . $e->getMessage());
        echo json_encode($response);
    }
}
?>
