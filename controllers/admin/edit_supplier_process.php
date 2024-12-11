<?php

include '../../connections/connections.php';

if (isset($_POST['edit_supplier'])) {

  $supplier_id = $conn->real_escape_string($_POST['supplier_id']);
	$supplier_name = $conn->real_escape_string($_POST['supplier_name']);
	$address = $conn->real_escape_string($_POST['address']);
	$landline = $conn->real_escape_string($_POST['landline']);
	$mobile_number = $conn->real_escape_string($_POST['mobile_number']);
	$email = $conn->real_escape_string($_POST['email']);
	$tin = $conn->real_escape_string($_POST['tin']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `supplier` 
          SET 
            supplier_name = '$supplier_name',
            `address` = '$address',
            landline = '$landline',
            mobile_number = '$mobile_number',
            email = '$email',
            tin = '$tin'
          WHERE supplier_id = '$supplier_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier updated successfully
    $response = array('success' => true, 'message' => 'Supplier updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating supplier
    $response = array('success' => false, 'message' => 'Error updating supplier: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  } 
}
?>