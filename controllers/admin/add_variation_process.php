<?php

include '../../connections/connections.php';

if (isset($_POST['add_variation'])) {
  // Get form data
  $attribute = $conn->real_escape_string($_POST['attribute']);
  $value = $conn->real_escape_string($_POST['value']);
  $product_id = $conn->real_escape_string($_POST['product_id']);

  // Construct SQL query
  $sql = "INSERT INTO `variations` (`value`, attribute, product_id)
          VALUES ('$value', '$attribute', '$product_id')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier added successfully
    $response = array('success' => true, 'message' => 'Variation Added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error adding supplier
    $response = array('success' => false, 'message' => 'Error Adding Variation!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>