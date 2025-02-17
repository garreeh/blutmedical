<?php

include '../../connections/connections.php';

if (isset($_POST['edit_subcategory'])) {

  $subcategory_id = $conn->real_escape_string($_POST['subcategory_id']);
  $subcategory_name = $conn->real_escape_string($_POST['subcategory_name']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `subcategory` 
          SET 
            subcategory_name = '$subcategory_name'
          WHERE subcategory_id = '$subcategory_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // Supplier updated successfully
    $response = array('success' => true, 'message' => 'Sub Category updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating supplier
    $response = array('success' => false, 'message' => 'Error updating Sub Category: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
?>