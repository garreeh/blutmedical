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
$referenceNo = strtoupper(bin2hex(random_bytes(3))); // Generate a 6-digit random reference number

// Handle file upload
$proofOfPaymentPath = null;
if ($paymentMethod === 'GCash' && isset($_FILES['proofOfPayment']) && $_FILES['proofOfPayment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../uploads/payments/';
    $uploadFile = $uploadDir . basename($_FILES['proofOfPayment']['name']);
    
    // Check if directory exists and is writable
    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        $response['message'] = 'Upload directory not writable';
        echo json_encode($response);
        exit;
    }

    if (move_uploaded_file($_FILES['proofOfPayment']['tmp_name'], $uploadFile)) {
        $proofOfPaymentPath = $uploadFile;
    } else {
        $response['message'] = 'Failed to upload file';
        echo json_encode($response);
        exit;
    }
}

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

    // Prepare to update product stocks based on quantities from the cart
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $cart_quantity = $row['cart_quantity'];

        // Update product stocks by subtracting the quantity of the products in 'Cart' status
        $updateProductSql = "UPDATE product 
                             SET product_stocks = product_stocks - $cart_quantity 
                             WHERE product_id = '$product_id'";

        if (!mysqli_query($conn, $updateProductSql)) {
            throw new Exception('Failed to update product stocks for product ID: ' . $product_id);
        }

        // Check if product stocks are now zero or less
        $checkStockSql = "SELECT product_stocks FROM product WHERE product_id = '$product_id'";
        $stockResult = mysqli_query($conn, $checkStockSql);
        if ($stockResult) {
            $stockRow = mysqli_fetch_assoc($stockResult);
            if ($stockRow['product_stocks'] < 0) {
                throw new Exception('Stock cannot go negative for product ID: ' . $product_id);
            }
        } else {
            throw new Exception('Failed to check product stocks for product ID: ' . $product_id);
        }
    }

    // Update the cart status for all items in 'Cart' for the current user
    $updateCartSql = "UPDATE cart SET cart_status = 'Processing', reference_no = '$referenceNo', payment_method = '$paymentMethod' 
                      WHERE user_id = '$user_id' AND cart_status = 'Cart'";
    
    if ($proofOfPaymentPath) {
        // Save the path of the proof of payment if available
        $updateProofSql = "UPDATE cart SET proof_of_payment = '$proofOfPaymentPath' 
                           WHERE user_id = '$user_id' AND cart_status = 'Cart'";
        if (!mysqli_query($conn, $updateProofSql)) {
            throw new Exception('Failed to update proof of payment');
        }
    }

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
?>
