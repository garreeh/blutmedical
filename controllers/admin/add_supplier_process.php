<?php

include '../../connections/connections.php';

if (isset($_POST['add_supplier'])) {
	// Get form data
	$supplier_name = $conn->real_escape_string($_POST['supplier_name']);
	$address = $conn->real_escape_string($_POST['address']);
	$landline = $conn->real_escape_string($_POST['landline']);
	$mobile_number = $conn->real_escape_string($_POST['mobile']);
	$email = $conn->real_escape_string($_POST['email']);
	$tin = $conn->real_escape_string($_POST['tin']);

  // Construct SQL query
  $sql = "INSERT INTO `supplier` (supplier_name, address, landline, mobile_number, email, tin)
          VALUES ('$supplier_name', '$address', '$landline', '$mobile_number', '$email', '$tin')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier added successfully
    $response = array('success' => true, 'message' => 'Supplier Added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error adding supplier
    $response = array('success' => false, 'message' => 'Error Adding Supplier!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  } 
}
?>