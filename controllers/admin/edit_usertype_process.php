<?php

include '../../connections/connections.php';

if (isset($_POST['edit_user_type'])) {

  $user_type_id = $conn->real_escape_string($_POST['user_type_id']);
  $user_type_name = $conn->real_escape_string($_POST['user_type_name']);

  $ship_order = isset($_POST['ship_order']) ? $conn->real_escape_string($_POST['ship_order']) : '0';
  $view_order = isset($_POST['view_order']) ? $conn->real_escape_string($_POST['view_order']) : '0';
  $client_order_module = isset($_POST['client_order_module']) ? $conn->real_escape_string($_POST['client_order_module']) : '0';
  $complete_order = isset($_POST['complete_order']) ? $conn->real_escape_string($_POST['complete_order']) : '0';
  $view_shipped_order = isset($_POST['view_shipped_order']) ? $conn->real_escape_string($_POST['view_shipped_order']) : '0';
  $shipped_order_module = isset($_POST['shipped_order_module']) ? $conn->real_escape_string($_POST['shipped_order_module']) : '0';
  $view_transaction_module = isset($_POST['view_transaction_module']) ? $conn->real_escape_string($_POST['view_transaction_module']) : '0';
  $sales_report_module = isset($_POST['sales_report_module']) ? $conn->real_escape_string($_POST['sales_report_module']) : '0';
  $product_setup_module = isset($_POST['product_setup_module']) ? $conn->real_escape_string($_POST['product_setup_module']) : '0';
  $user_setup = isset($_POST['user_setup']) ? $conn->real_escape_string($_POST['user_setup']) : '0';

  // Construct SQL query for UPDATE
  $sql = "UPDATE `usertype` 
          SET 
            user_type_name = '$user_type_name',
            ship_order = '$ship_order',
            view_order = '$view_order',
            client_order_module = '$client_order_module',
            complete_order = '$complete_order',
            view_shipped_order = '$view_shipped_order',
            shipped_order_module = '$shipped_order_module',
            view_transaction_module = '$view_transaction_module',
            sales_report_module = '$sales_report_module',
            product_setup_module = '$product_setup_module',
            user_setup = '$user_setup'
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
