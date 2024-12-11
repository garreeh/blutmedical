<?php
include '../../connections/connections.php';

if (isset($_POST['delete_supplier']) && isset($_POST['supplier_id'])) {
  $supplier_id = $conn->real_escape_string($_POST['supplier_id']);

  // Construct the DELETE query
  $sql = "DELETE FROM supplier WHERE supplier_id = '$supplier_id'";

  // Execute the DELETE query
  if (mysqli_query($conn, $sql)) {
    // Supplier deleted successfully
    $response = array('success' => true, 'message' => 'Supplier deleted successfully!');
  } else {
    // Error deleting supplier
    $response = array('success' => false, 'message' => 'Error deleting supplier: ' . mysqli_error($conn));
  }

  // Return the response as JSON
  echo json_encode($response);
  exit();
} else {
  // Invalid request
  $response = array('success' => false, 'message' => 'Invalid request. Supplier ID missing.');
  echo json_encode($response);
  exit();
}
