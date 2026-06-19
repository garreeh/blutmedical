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
  // ID CHECK
  // =========================

  if (!isset($_POST['carousel_id'])) {

    throw new Exception(
      "Carousel ID missing."
    );

  }


  $carousel_id = intval($_POST['carousel_id']);






  // =========================
  // GET OLD FILE
  // =========================


  $stmt = mysqli_prepare(
    $conn,
    "SELECT scene FROM carousel WHERE carousel_id=?"
  );


  mysqli_stmt_bind_param(
    $stmt,
    "i",
    $carousel_id
  );


  mysqli_stmt_execute($stmt);


  $result = mysqli_stmt_get_result($stmt);



  if (!$row = mysqli_fetch_assoc($result)) {

    throw new Exception(
      "Carousel item not found."
    );

  }



  $old_file = $row['scene'];

  $new_file = $old_file;







  // =========================
  // CHECK NEW UPLOAD
  // =========================


  if (
    isset($_FILES['fileToUpload']) &&
    $_FILES['fileToUpload']['error'] !== UPLOAD_ERR_NO_FILE
  ) {



    $file = $_FILES['fileToUpload'];



    if ($file['error'] !== UPLOAD_ERR_OK) {


      throw new Exception(
        "Upload error code: " . $file['error']
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
          "Cannot create upload folder."
        );

      }

    }






    // =========================
    // VALIDATION
    // =========================


    $extension = strtolower(
      pathinfo(
        $file['name'],
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





    $mime = mime_content_type(
      $file['tmp_name']
    );



    $allowedMime = [

      "image/jpeg",
      "image/png",
      "image/gif",
      "image/webp",
      "video/mp4"

    ];



    if (!in_array($mime, $allowedMime)) {


      throw new Exception(
        "Invalid file."
      );

    }







    // =========================
    // CREATE NAME
    // =========================


    if ($extension === "mp4") {


      $filename =
        uniqid(
          "carousel_video_",
          true
        )
        . ".mp4";


    } else {


      $filename =
        uniqid(
          "carousel_",
          true
        )
        . "."
        . $extension;

    }



    $destination =
      $uploadDir . $filename;







    // =========================
    // MOVE NEW FILE
    // =========================


    if (
      !move_uploaded_file(
        $file['tmp_name'],
        $destination
      )
    ) {


      throw new Exception(
        "Failed uploading new file."
      );

    }



    $new_file = $destination;







    // =========================
    // UPDATE DATABASE
    // =========================


    $update = mysqli_prepare(
      $conn,
      "UPDATE carousel SET scene=? WHERE carousel_id=?"
    );



    mysqli_stmt_bind_param(
      $update,
      "si",
      $new_file,
      $carousel_id
    );



    if (!mysqli_stmt_execute($update)) {


      // remove new file if DB fails

      if (file_exists($new_file)) {

        unlink($new_file);

      }


      throw new Exception(
        mysqli_stmt_error($update)
      );

    }







    // =========================
    // DELETE OLD FILE
    // =========================


    if (!empty($old_file)) {


      $old_path = $old_file;



      // normalize path

      $old_path = str_replace(
        "\\",
        "/",
        $old_path
      );



      if (
        file_exists($old_path)
        &&
        $old_path !== $new_file
      ) {


        unlink($old_path);


      }


    }





  } else {



    // =========================
    // UPDATE ONLY DATA
    // =========================


    $update = mysqli_prepare(
      $conn,
      "UPDATE carousel SET scene=? WHERE carousel_id=?"
    );



    mysqli_stmt_bind_param(
      $update,
      "si",
      $new_file,
      $carousel_id
    );



    if (!mysqli_stmt_execute($update)) {


      throw new Exception(
        mysqli_stmt_error($update)
      );

    }


  }





  response(
    true,
    "Carousel updated successfully."
  );




} catch (Exception $e) {


  response(
    false,
    $e->getMessage()
  );


}


?>