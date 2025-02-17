<?php

include '../../connections/connections.php';

if (isset($_POST['add_category'])) {
  // Get form data
  $subcategory_name = $conn->real_escape_string($_POST['subcategory_name']);

  // Construct SQL query
  $sql = "INSERT INTO `subcategory` (subcategory_name)
          VALUES ('$subcategory_name')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier added successfully
    $response = array('success' => true, 'message' => 'Sub Category Added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error adding supplier
    $response = array('success' => false, 'message' => 'Error Adding Sub Category!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>