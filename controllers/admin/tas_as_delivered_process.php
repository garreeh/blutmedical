<?php
include '../../connections/connections.php';

if (isset($_POST['tag_as_delivered'])) {

  // Get cart_id and user_id
  $cart_id = $conn->real_escape_string($_POST['cart_id']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `cart` 
          SET 
              cart_status = 'Delivered',
              payment_status = 'Paid'
          WHERE cart_id = '$cart_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'Delivered successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error Delivering: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  } 
}
?>
