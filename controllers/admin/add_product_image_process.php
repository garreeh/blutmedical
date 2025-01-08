<?php

include '../../connections/connections.php';

if (isset($_POST['add_product_image'])) {

  // Initialize response array
  $response = array('success' => false, 'message' => '');

  $target_dir = "../../uploads/";
  $target_filename = basename($_FILES["fileToUpload"]["name"]);
  $target_file = $target_dir . $target_filename;
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Create target directory if it doesn't exist (this is not needed in your case)
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  // Other checks and upload logic
  if (file_exists($target_file)) {
    $response['message'] = "Sorry, file already exists.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PDF" && $imageFileType != "pdf"
  ) {
    $response['message'] = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $response['message'] = "Sorry, your file was not uploaded.";
    echo json_encode($response);
    exit();
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $response['message'] = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
    } else {
      $response['message'] = "Sorry, there was an error uploading your file.";
      echo json_encode($response);
      exit();
    }
  }

  // Get form data
  $product_id = $conn->real_escape_string($_POST['product_id']);

  // Construct SQL query
  $sql = "INSERT INTO `product_image` (product_id, product_image_path)
            VALUES ('$product_id','$target_file')";

  // Execute SQL query
  if (mysqli_query($conn, $sql)) {
    $response['success'] = true;
    $response['message'] = 'Product Image added successfully!';
  } else {
    $response['message'] = 'Error adding product image: ' . mysqli_error($conn);
  }

  echo json_encode($response);
  exit();
}
?>