<?php

include '../../connections/connections.php';

if (isset($_POST['edit_product_image'])) {

  $response = array('success' => false, 'message' => '');
  $product_image_id = $conn->real_escape_string($_POST['product_image_id']);

  // Get the old file path from the database
  $sql = "SELECT product_image_path FROM product_image WHERE product_image_id='$product_image_id'";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc(result: $result);
    $old_file = $row['product_image_path'];
  } else {
    $response['message'] = "Error retrieving old file information.";
    echo json_encode($response);
    exit();
  }

  $target_dir = "../../uploads/";
  $uploadOk = 1;

  // Handle file upload
  if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] != UPLOAD_ERR_NO_FILE) {
    $target_filename = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $target_filename;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check for upload errors
    if ($_FILES["fileToUpload"]["error"] !== UPLOAD_ERR_OK) {
      $response['message'] = "File upload error: " . $_FILES["fileToUpload"]["error"];
      echo json_encode($response);
      exit();
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      $response['message'] = "Sorry, file already exists.";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if (
      $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" && $imageFileType != "pdf"
    ) {
      $response['message'] = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      $response['message'] = "Sorry, file already exists.";
      echo json_encode($response);
      exit();
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $response['message'] = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

        // Delete the old file if it exists and is different from the new file
        if (!empty($old_file) && $old_file !== $target_file) {
          if (file_exists($old_file)) {
            unlink($old_file);
          }
        }
      } else {
        $response['message'] = "Sorry, there was an error uploading your file.";
        echo json_encode($response);
        exit();
      }
    }

    // Set new filename if a new file is uploaded
    $new_filename = $target_file;
  } else {
    // No file uploaded, retain old filename
    $new_filename = $old_file;
  }

  // Update SQL query with full path
  $sql = "UPDATE product_image SET 
            product_image_path = '$new_filename'
            WHERE product_image_id = '$product_image_id'";

  if (mysqli_query($conn, $sql)) {
    $response['success'] = true;
    $response['message'] = 'Product Image updated successfully!';
  } else {
    $response['message'] = 'Error updating product image: ' . mysqli_error($conn);
  }

  echo json_encode($response);
  exit();
}
