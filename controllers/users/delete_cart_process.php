<?php
session_start();
include '../../connections/connections.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'message' => 'Not logged in.'));
    exit();
}

if (isset($_POST['product_id'])) {
    $product_id = $conn->real_escape_string($_POST['product_id']);
    $user_id = $_SESSION['user_id'];

    // Delete the item from the cart
    $delete_query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($conn->query($delete_query)) {
        $response = array('success' => true, 'message' => 'Item removed from cart.');
    } else {
        $response = array('success' => false, 'message' => 'Error removing item from cart: ' . $conn->error);
    }

    echo json_encode($response);
    exit();
} else {
    $response = array('success' => false, 'message' => 'No product ID provided.');
    echo json_encode($response);
    exit();
}
?>
