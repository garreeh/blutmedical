<?php

include '../../connections/connections.php';

if (isset($_POST['add_discount'])) {
  // Get form data
  $voucher_code = $conn->real_escape_string($_POST['voucher_code']);
  $voucher_percentage = $conn->real_escape_string($_POST['voucher_percentage']);
  $minimum_cart = $conn->real_escape_string($_POST['minimum_cart']);

  // Construct SQL query
  $sql = "INSERT INTO `voucher` (voucher_code, voucher_percentage, minimum_cart, voucher_status)
          VALUES ('$voucher_code', '$voucher_percentage', '$minimum_cart', 'Active')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Discount added successfully
    $response = array('success' => true, 'message' => 'Voucher Added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error adding discount
    $response = array('success' => false, 'message' => 'Error Adding Voucher!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>