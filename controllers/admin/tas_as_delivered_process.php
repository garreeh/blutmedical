<?php
include '../../connections/connections.php';

if (isset($_POST['tag_as_shipped'])) {

  $cart_id = $conn->real_escape_string($_POST['cart_id']);

  $sql = "UPDATE `cart` 
          SET 
              cart_status = 'Shipped'
          WHERE cart_id = '$cart_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array(
      'success' => true,
      'message' => 'Shipped successfully!',
      'cart_id' => $cart_id // Include cart_id
    );
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error Delivering: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
