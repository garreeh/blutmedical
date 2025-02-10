<?php

include '../../connections/connections.php';


// Check for removed variations
if (isset($_POST['remove_variation_id'])) {
  $removed_variation_ids = $_POST['remove_variation_id'];
  foreach ($removed_variation_ids as $remove_id) {
    $remove_id = $conn->real_escape_string($remove_id);
    // Remove variation from DB and unlink it if it exists
    $sql_remove_variation = "DELETE FROM `variations_colors` WHERE variation_color_id='$remove_id'";
    mysqli_query($conn, $sql_remove_variation);
  }
  echo json_encode(['success' => true, 'message' => 'Variation(s) Color removed successfully!']);
  exit();
}

echo json_encode($response);
exit();
