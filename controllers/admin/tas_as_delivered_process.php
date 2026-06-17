<?php
include '../../connections/connections.php';

if (isset($_POST['tag_as_shipped'])) {

  // sanitize inputs
  $cart_id = $conn->real_escape_string($_POST['cart_id']);

  /*
  |--------------------------------------------------------------------------
  | STEP 1: GET ORDER IDENTIFIER (REFERENCE OR PAYPAL)
  |--------------------------------------------------------------------------
  */
  $getOrder = mysqli_query($conn, "
        SELECT reference_no, paypal_order_id, payment_method
        FROM cart
        WHERE cart_id = '$cart_id'
        LIMIT 1
    ");

  if (!$getOrder || mysqli_num_rows($getOrder) == 0) {
    echo json_encode([
      'success' => false,
      'message' => 'Order not found!'
    ]);
    exit();
  }

  $row = mysqli_fetch_assoc($getOrder);

  /*
  |--------------------------------------------------------------------------
  | STEP 2: DETERMINE GROUP KEY
  |--------------------------------------------------------------------------
  */
  if ($row['payment_method'] === 'Paypal') {
    $where = "paypal_order_id = '" . $conn->real_escape_string($row['paypal_order_id']) . "'";
    $group_id = $row['paypal_order_id'];
  } else {
    $where = "reference_no = '" . $conn->real_escape_string($row['reference_no']) . "'";
    $group_id = $row['reference_no'];
  }

  /*
  |--------------------------------------------------------------------------
  | STEP 3: UPDATE ALL ITEMS IN SAME ORDER GROUP
  |--------------------------------------------------------------------------
  */
  $sql = "
        UPDATE cart
        SET cart_status = 'Shipped'
        WHERE $where
    ";

  if (mysqli_query($conn, $sql)) {

    echo json_encode([
      'success' => true,
      'message' => 'Shipped successfully!',
      'group_id' => $group_id
    ]);

    exit();

  } else {

    echo json_encode([
      'success' => false,
      'message' => 'Error updating: ' . mysqli_error($conn)
    ]);

    exit();
  }
}