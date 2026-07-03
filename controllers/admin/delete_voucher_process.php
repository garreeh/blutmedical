<?php
include '../../connections/connections.php';

if (isset($_POST['delete_voucher']) && isset($_POST['voucher_id'])) {
  $voucher_id = $conn->real_escape_string($_POST['voucher_id']);

  // Construct the DELETE query
  $sql = "DELETE FROM voucher WHERE voucher_id = '$voucher_id'";

  // Execute the DELETE query
  if (mysqli_query($conn, $sql)) {
    // Voucher deleted successfully
    $response = array('success' => true, 'message' => 'Voucher deleted successfully!');
  } else {
    // Error deleting voucher
    $response = array('success' => false, 'message' => 'Error deleting voucher: ' . mysqli_error($conn));
  }

  // Return the response as JSON
  echo json_encode($response);
  exit();
} else {
  // Invalid request
  $response = array('success' => false, 'message' => 'Invalid request. Voucher ID missing.');
  echo json_encode($response);
  exit();
}
