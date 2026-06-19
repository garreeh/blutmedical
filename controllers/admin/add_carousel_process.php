<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

include '../../connections/connections.php';


function response($success, $message)
{
  echo json_encode([
    "success" => $success,
    "message" => $message
  ]);

  exit;
}



try {


  // =========================
  // REQUEST CHECK
  // =========================

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    throw new Exception(
      "Invalid request method."
    );

  }



  // =========================
  // FILE CHECK
  // =========================


  if (!isset($_FILES['fileToUpload'])) {

    throw new Exception(
      "No file uploaded."
    );

  }



  $file = $_FILES['fileToUpload'];



  if ($file['error'] !== UPLOAD_ERR_OK) {


    $errors = [

      UPLOAD_ERR_INI_SIZE =>
        "File exceeds server upload limit.",

      UPLOAD_ERR_FORM_SIZE =>
        "File exceeds form limit.",

      UPLOAD_ERR_PARTIAL =>
        "File upload incomplete.",

      UPLOAD_ERR_NO_FILE =>
        "No file selected.",

      UPLOAD_ERR_NO_TMP_DIR =>
        "Missing temporary folder.",

      UPLOAD_ERR_CANT_WRITE =>
        "Cannot write uploaded file."
    ];



    throw new Exception(
      $errors[$file['error']] ??
      "Upload error."
    );

  }





  // =========================
  // CHECK CAROUSEL LIMIT
  // =========================


  $check = mysqli_query(
    $conn,
    "SELECT COUNT(*) total FROM carousel"
  );


  if (!$check) {

    throw new Exception(
      mysqli_error($conn)
    );

  }


  $count = mysqli_fetch_assoc($check);



  if ($count['total'] >= 3) {

    throw new Exception(
      "Maximum of 3 carousel items allowed."
    );

  }





  // =========================
  // UPLOAD DIRECTORY
  // =========================


  $uploadDir = "../../uploads/";


  if (!is_dir($uploadDir)) {


    if (
      !mkdir(
        $uploadDir,
        0755,
        true
      )
    ) {

      throw new Exception(
        "Cannot create upload directory."
      );

    }

  }





  // =========================
  // FILE VALIDATION
  // =========================


  $originalName = $file['name'];

  $tmp = $file['tmp_name'];

  $extension = strtolower(
    pathinfo(
      $originalName,
      PATHINFO_EXTENSION
    )
  );



  $allowed = [

    "jpg",
    "jpeg",
    "png",
    "gif",
    "webp",
    "mp4"

  ];



  if (!in_array($extension, $allowed)) {

    throw new Exception(
      "Invalid file type."
    );

  }





  // MIME CHECK

  $mime = mime_content_type($tmp);



  $allowedMime = [

    "image/jpeg",
    "image/png",
    "image/gif",
    "image/webp",
    "video/mp4"

  ];



  if (!in_array($mime, $allowedMime)) {

    throw new Exception(
      "Invalid file content."
    );

  }





  // =========================
  // CREATE NEW NAME
  // =========================


  if ($extension === "mp4") {

    $newName =
      uniqid(
        "carousel_video_",
        true
      )
      . ".mp4";

  } else {


    $newName =
      uniqid(
        "carousel_",
        true
      )
      . "."
      . $extension;

  }



  $destination =
    $uploadDir . $newName;





  // =========================
  // MOVE FILE
  // =========================


  if (
    !move_uploaded_file(
      $tmp,
      $destination
    )
  ) {


    throw new Exception(
      "Failed moving uploaded file."
    );

  }





  // =========================
  // SAVE DATABASE
  // =========================


  $stmt = mysqli_prepare(
    $conn,
    "INSERT INTO carousel(scene) VALUES (?)"
  );


  if (!$stmt) {


    unlink($destination);


    throw new Exception(
      mysqli_error($conn)
    );

  }



  mysqli_stmt_bind_param(
    $stmt,
    "s",
    $destination
  );



  if (!mysqli_stmt_execute($stmt)) {


    unlink($destination);


    throw new Exception(
      mysqli_stmt_error($stmt)
    );

  }



  mysqli_stmt_close($stmt);





  response(
    true,
    "Carousel uploaded successfully."
  );





} catch (Exception $e) {


  response(
    false,
    $e->getMessage()
  );


}

?>