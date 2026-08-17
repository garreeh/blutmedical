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
        SELECT reference_no, paypal_order_id, payment_method, user_id
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
  if (
    $row['payment_method'] === 'Paypal' &&
    !empty($row['paypal_order_id'])
  ) {

    $where = "paypal_order_id = '" . $conn->real_escape_string($row['paypal_order_id']) . "'";
    $group_id = $row['paypal_order_id'];

  } elseif (!empty($row['reference_no'])) {

    $where = "reference_no = '" . $conn->real_escape_string($row['reference_no']) . "'";
    $group_id = $row['reference_no'];

  } else {

    // No reference number or PayPal order ID
    // Generate a new reference number
    $user_id = (int) $row['user_id'];
    $reference_no = 'REF-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

    // Assign the reference number to all ungrouped items of this user
    $assignReference = "
        UPDATE cart
        SET reference_no = '$reference_no'
        WHERE user_id = '$user_id'
          AND (reference_no IS NULL OR reference_no = '')
          AND (paypal_order_id IS NULL OR paypal_order_id = '')
    ";

    mysqli_query($conn, $assignReference);

    // Use the newly generated reference number for the shipped update
    $where = "reference_no = '$reference_no'";
    $group_id = $reference_no;
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