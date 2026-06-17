<?php

include '../../connections/connections.php';

if (isset($_POST['set_admin_user'])) {

  $user_id = $conn->real_escape_string($_POST['user_id']);


  $is_admin = $conn->real_escape_string($_POST['is_admin']);

  $user_type_id = $conn->real_escape_string($_POST['user_type_id']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `users` 
          SET 
            is_admin = '$is_admin',
            user_type_id = '$user_type_id'

          WHERE user_id = '$user_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'User set admin successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
