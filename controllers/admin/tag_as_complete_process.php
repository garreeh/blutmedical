<?php
include '../../connections/connections.php';

if (isset($_POST['tag_as_complete'])) {

  $cart_id = $conn->real_escape_string($_POST['cart_id']);

  // GET ORDER IDENTIFIERS
  $get = mysqli_query($conn, "
    SELECT reference_no, paypal_order_id 
    FROM cart 
    WHERE cart_id = '$cart_id'
    LIMIT 1
  ");

  $row = mysqli_fetch_assoc($get);

  $reference_no = $row['reference_no'];
  $paypal_order_id = $row['paypal_order_id'];

  // SMART WHERE CONDITION
  if (!empty($paypal_order_id)) {
    $where = "paypal_order_id = '$paypal_order_id'";
  } else {
    $where = "reference_no = '$reference_no'";
  }

  $sql = "UPDATE cart 
          SET 
              cart_status = 'Delivered',
              payment_status = 'Paid'
          WHERE $where";

  if (mysqli_query($conn, $sql)) {

    echo json_encode([
      'success' => true,
      'message' => 'Delivered successfully!',
      'reference_no' => $reference_no,
      'paypal_order_id' => $paypal_order_id
    ]);
    exit();

  } else {

    echo json_encode([
      'success' => false,
      'message' => mysqli_error($conn)
    ]);
    exit();
  }
}