<?php
include '../../connections/connections.php';

if (isset($_POST['add_delivery_rider'])) {

  // Get cart_id and user_id
  $cart_id = $conn->real_escape_string($_POST['cart_id']);
  $user_id = $conn->real_escape_string($_POST['user_id']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `cart` 
          SET 
              delivery_rider_id = '$user_id',
              cart_status = 'Out For Delivery'
          WHERE cart_id = '$cart_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'Delivery Rider Set successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  } 
}
?>
