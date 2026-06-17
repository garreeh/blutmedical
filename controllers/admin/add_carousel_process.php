<?php

include '../../connections/connections.php';

if (isset($_POST['add_carousel'])) {

  $response = array('success' => false, 'message' => '');

  $target_dir = "../../uploads/";
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  // =========================
  // LIMIT CHECK (MAX 3 ITEMS)
  // =========================
  $check_sql = "SELECT COUNT(*) as total FROM carousel";
  $check_result = mysqli_query($conn, $check_sql);
  $row = mysqli_fetch_assoc($check_result);

  if ($row['total'] >= 3) {
    $response['message'] = "Maximum of 3 carousel items only has been reached.";
    echo json_encode($response);
    exit();
  }

  $main_picture = NULL;

  $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif'];
  $allowed_video_types = ['mp4'];

  if (!empty($_FILES["fileToUpload"]["name"])) {

    $file_name = $_FILES["fileToUpload"]["name"];
    $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if ($file_ext === 'jfif') {
      $file_ext = 'jpg';
    }

    // =========================
    // IMAGE → WEBP USING cwebp
    // =========================
    if (in_array($file_ext, $allowed_image_types)) {

      $input_path = $tmp_name;

      $output_name = uniqid("carousel_", true) . ".webp";
      $output_path = $target_dir . $output_name;

      // cwebp command
      $command = "cwebp -q 80 " . escapeshellarg($input_path) . " -o " . escapeshellarg($output_path);

      exec($command, $output, $result_code);

      if ($result_code === 0 && file_exists($output_path)) {
        $main_picture = $output_path;
      } else {
        // fallback: just upload original if conversion fails
        $fallback_name = uniqid("carousel_", true) . "." . $file_ext;
        $fallback_path = $target_dir . $fallback_name;

        if (move_uploaded_file($tmp_name, $fallback_path)) {
          $main_picture = $fallback_path;
        } else {
          $response['message'] = "Failed to upload image.";
          echo json_encode($response);
          exit();
        }
      }

    }

    // =========================
    // VIDEO
    // =========================
    elseif (in_array($file_ext, $allowed_video_types)) {

      $new_name = uniqid("carousel_video_", true) . ".mp4";
      $target_file = $target_dir . $new_name;

      if (move_uploaded_file($tmp_name, $target_file)) {
        $main_picture = $target_file;
      } else {
        $response['message'] = "Failed to upload video.";
        echo json_encode($response);
        exit();
      }

    } else {
      $response['message'] = "Invalid file type. Allowed: JPG, PNG, WEBP, GIF, JFIF, MP4.";
      echo json_encode($response);
      exit();
    }
  }

  // =========================
  // INSERT DB
  // =========================
  $sql = "INSERT INTO carousel (scene) VALUES ('$main_picture')";

  if (mysqli_query($conn, $sql)) {
    $response['success'] = true;
    $response['message'] = 'Image / Video added successfully!';
  } else {
    $response['message'] = 'DB Error: ' . mysqli_error($conn);
  }

  echo json_encode($response);
  exit();
}
?>