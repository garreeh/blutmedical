<?php

include '../../connections/connections.php';

if (isset($_POST['edit_customers'])) {

  $user_id = $conn->real_escape_string($_POST['user_id']);
  $user_fullname = $conn->real_escape_string($_POST['user_fullname']);
  $user_address = $conn->real_escape_string($_POST['user_address']);
  $user_contact = $conn->real_escape_string($_POST['user_contact']);
  $username = $conn->real_escape_string($_POST['username']);
  $user_email = $conn->real_escape_string($_POST['user_email']);

  $user_confirm_password = $conn->real_escape_string($_POST['user_confirm_password']);

  // Hash the user_confirm_password
  $user_password = password_hash($user_confirm_password, PASSWORD_BCRYPT);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `users` 
          SET 
            user_fullname = '$user_fullname',
            user_address = '$user_address',
            user_contact = '$user_contact',
            username = '$username',
            user_email = '$user_email',
            user_password = '$user_password',
            user_confirm_password = '$user_confirm_password'

          WHERE user_id = '$user_id'";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    // User updated successfully
    $response = array('success' => true, 'message' => 'User updated successfully!');
    echo json_encode($response);
    exit();
  } else {
    // Error updating user
    $response = array('success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn));
    echo json_encode($response);
    exit();
  }
}
