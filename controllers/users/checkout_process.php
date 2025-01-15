<?php
include './../../connections/connections.php';

session_start();

$response = array('success' => false, 'message' => '');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$paymentMethod = $_POST['paymentCategory'];

// Start a transaction to ensure data integrity
mysqli_begin_transaction($conn);

try {
    // Retrieve cart items for stock update (only those in 'Cart' status for the logged-in user)
    $cartItemsSql = "SELECT product_id, cart_quantity FROM cart 
                     WHERE user_id = '$user_id' AND cart_status = 'Cart'";
    $result = mysqli_query($conn, $cartItemsSql);
    if (!$result || mysqli_num_rows($result) === 0) {
        // throw new Exception('No items in the cart to checkout.');
    }

    // Update the cart status for all items in 'Cart' for the current user
    $updateCartSql = "UPDATE cart SET 
                      cart_status = 'Processing', 
                      payment_method = '$paymentMethod',
                      payment_status = 'Unpaid'
                      WHERE user_id = '$user_id' AND cart_status = 'Cart'";

    // Execute the cart status update for all items
    if (!mysqli_query($conn, $updateCartSql)) {
        throw new Exception('Failed to update cart status for all items');
    }

    // Commit the transaction
    mysqli_commit($conn);

    // Success response
    $response['success'] = true;
    $response['message'] = 'Checkout successful for all items in the cart';
} catch (Exception $e) {
    // Rollback the transaction on error
    mysqli_rollback($conn);
    $response['message'] = $e->getMessage();
}

// Output the JSON response
echo json_encode($response);
