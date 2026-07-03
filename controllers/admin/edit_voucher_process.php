<?php

include '../../connections/connections.php';

if (isset($_POST['edit_voucher'])) {

  $voucher_id = $conn->real_escape_string($_POST['voucher_id']);
  $voucher_code = $conn->real_escape_string($_POST['voucher_code']);
  $voucher_percentage = $conn->real_escape_string($_POST['voucher_percentage']);
  $voucher_status = $conn->real_escape_string($_POST['voucher_status']);


  // Construct SQL query for UPDATE
  $sql = "UPDATE `voucher` 
          SET 
            voucher_code = '$voucher_code',
            voucher_percentage = '$voucher_percentage',
            voucher_status = '$voucher_status'
          WHERE voucher_id = '$voucher_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Voucher updated successfully
    $response = array('success' => true, 'message' => 'Voucher updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating voucher
    $response = array('success' => false, 'message' => 'Error updating voucher: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>