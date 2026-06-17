<?php

include '../../connections/connections.php';

if (isset($_POST['delete_user'])) {

  $user_id = $conn->real_escape_string($_POST['user_id']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `users` 
          SET 

            is_deleted = '1',
            account_status = 'Inactive'

          WHERE user_id = '$user_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'User deleted successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
