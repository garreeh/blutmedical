<?php

include '../../connections/connections.php';

if (isset($_POST['add_variation'])) {
  // Get form data
  $price = $conn->real_escape_string($_POST['price']);
  $value = $conn->real_escape_string($_POST['value']);
  $product_id = $conn->real_escape_string($_POST['product_id']);

  // Construct SQL query for inserting a new variation
  $sql = "INSERT INTO `variations` (`value`, `price`, `product_id`) 
          VALUES ('$value', '$price', '$product_id')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Variation inserted successfully
    $response = array('success' => true, 'message' => 'Variation added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error inserting variation
    $response = array('success' => false, 'message' => 'Error adding variation!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
