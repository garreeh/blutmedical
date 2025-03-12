<?php

include '../../connections/connections.php';

if (isset($_POST['edit_supplier'])) {

  $category_id = $conn->real_escape_string($_POST['category_id']);

  // Get the old file path from the database
  $sql = "SELECT category_image FROM category WHERE category_id='$category_id'";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc(result: $result);
    $old_file = $row['category_image'];
  } else {
    $response['message'] = "Error retrieving old file information.";
    echo json_encode($response);
    exit();
  }

  $target_dir = "../../uploads/category/";
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


  $subcategory_id = $conn->real_escape_string($_POST['subcategory_id']);

  $category_name = $conn->real_escape_string($_POST['category_name']);

  // Construct SQL query for UPDATE
  $sql = "UPDATE `category` 
          SET 
            category_name = '$category_name',
            subcategory_id = '$subcategory_id',
            category_image = '$new_filename'
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