<?php

include '../../connections/connections.php';

if (isset($_POST['edit_user_type'])) {

  $user_type_id = $conn->real_escape_string($_POST['user_type_id']);
  $user_type_name = $conn->real_escape_string($_POST['user_type_name']);
  $inventory_module = $conn->real_escape_string($_POST['inventory_module']);
  $user_module = $conn->real_escape_string($_POST['user_module']);
  $reports_module = $conn->real_escape_string($_POST['reports_module']);
  $po_module = $conn->real_escape_string($_POST['po_module']);

  $transaction_module = $conn->real_escape_string($_POST['transaction_module']);
  $orders_module = $conn->real_escape_string($_POST['orders_module']);
  $deliveries_module = $conn->real_escape_string($_POST['deliveries_module']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `usertype` 
          SET 
            user_type_name = '$user_type_name',
            inventory_module = '$inventory_module',
            user_module = '$user_module',
            reports_module = '$reports_module',
            po_module = '$po_module',

            transaction_module = '$transaction_module',
            orders_module = '$orders_module',
            deliveries_module = '$deliveries_module'
          WHERE user_type_id = '$user_type_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'User Type updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
