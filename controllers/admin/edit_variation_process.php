<?php

include '../../connections/connections.php';

if (isset($_POST['edit_variation'])) {
  // Get form data
  $variation_id = $conn->real_escape_string($_POST['variation_id']);
  $price = $conn->real_escape_string($_POST['price']);
  $value = $conn->real_escape_string($_POST['value']);
  $product_id = $conn->real_escape_string($_POST['product_id']);

  // Construct SQL query for updating the variation
  $sql = "UPDATE `variations` 
            SET `value` = '$value', 
                `price` = '$price', 
                `product_id` = '$product_id'
            WHERE `variation_id` = '$variation_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Variation updated successfully
    $response = array('success' => true, 'message' => 'Variation updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating variation
    $response = array('success' => false, 'message' => 'Error updating variation!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>