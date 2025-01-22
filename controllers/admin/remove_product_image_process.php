<?php

include '../../connections/connections.php';

// Check for removed variations
if (isset($_POST['remove_image_id'])) {
  $removed_image_ids = $_POST['remove_image_id'];
  foreach ($removed_image_ids as $remove_id) {
    $remove_id = $conn->real_escape_string($remove_id);
    // Remove image record from DB and unlink the actual file if it exists
    $sql_remove_image = "SELECT product_image_path FROM `product_image` WHERE product_image_id='$remove_id'";
    $result = mysqli_query($conn, $sql_remove_image);
    $row = mysqli_fetch_assoc($result);

    if ($row && file_exists($row['product_image_path'])) {
      unlink($row['product_image_path']); // Remove the actual image file
    }

    $sql_delete_image = "DELETE FROM `product_image` WHERE product_image_id='$remove_id'";
    mysqli_query($conn, $sql_delete_image);
  }
  echo json_encode(['success' => true, 'message' => 'Image(s) removed successfully!']);
  exit();
}

echo json_encode(['success' => false, 'message' => 'No image to remove']);
exit();
