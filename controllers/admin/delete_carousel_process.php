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
  // GET FILE
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

  $file_to_delete = $row['scene'];

  // =========================
  // DELETE DATABASE RECORD
  // =========================

  $delete = mysqli_prepare(
    $conn,
    "DELETE FROM carousel WHERE carousel_id=?"
  );

  mysqli_stmt_bind_param(
    $delete,
    "i",
    $carousel_id
  );

  if (!mysqli_stmt_execute($delete)) {

    throw new Exception(
      mysqli_stmt_error($delete)
    );

  }

  // =========================
  // DELETE FILE
  // =========================

  if (!empty($file_to_delete)) {

    $file_path = str_replace(
      "\\",
      "/",
      $file_to_delete
    );

    if (file_exists($file_path)) {

      unlink($file_path);

    }

  }

  response(
    true,
    "Carousel deleted successfully."
  );

} catch (Exception $e) {

  response(
    false,
    $e->getMessage()
  );

}

?>