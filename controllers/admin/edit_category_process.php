<?php

include '../../connections/connections.php';

if (isset($_POST['edit_supplier'])) {

  $category_id = $conn->real_escape_string($_POST['category_id']);
	$category_name = $conn->real_escape_string($_POST['category_name']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `category` 
          SET 
            category_name = '$category_name'
          WHERE category_id = '$category_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier updated successfully
    $response = array('success' => true, 'message' => 'Category updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating supplier
    $response = array('success' => false, 'message' => 'Error updating Category: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  } 
}
?>