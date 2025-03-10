<?php

include '../../connections/connections.php';

if (isset($_POST['add_category'])) {
  // Get form data
  $category_name = $conn->real_escape_string($_POST['category_name']);
  $subcategory_id = $conn->real_escape_string($_POST['subcategory_id']);


  // Construct SQL query
  $sql = "INSERT INTO `category` (category_name, subcategory_id)
          VALUES ('$category_name', '$subcategory_id')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier added successfully
    $response = array('success' => true, 'message' => 'Category Added successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error adding supplier
    $response = array('success' => false, 'message' => 'Error Adding Category!: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>