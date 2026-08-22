<?php
session_start();
include '../../connections/connections.php';

// Initialize response
$response = [
  'success' => false,
  'message' => ''
];

try {

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Invalid request method.');
  }

  if (!isset($_SESSION['user_id'])) {
    throw new Exception('Not logged in.');
  }


  /*
  |--------------------------------------------------------------------------
  | Get POST values
  |--------------------------------------------------------------------------
  */

  $user_id = $conn->real_escape_string($_SESSION['user_id']);

  $product_id = isset($_POST['product_id'])
    ? trim($_POST['product_id'])
    : '';

  $variation_id = isset($_POST['variation_id'])
    ? trim($_POST['variation_id'])
    : '';

  $variation_color_id = isset($_POST['variation_color_id'])
    ? trim($_POST['variation_color_id'])
    : '';

  $cart_id = isset($_POST['cart_id'])
    ? trim($_POST['cart_id'])
    : '';

  $action = isset($_POST['action'])
    ? trim($_POST['action'])
    : '';


  /*
  |--------------------------------------------------------------------------
  | Validate action
  |--------------------------------------------------------------------------
  */

  if ($action !== 'increase' && $action !== 'decrease') {
    throw new Exception('Invalid cart action.');
  }


  /*
  |--------------------------------------------------------------------------
  | Validate product/cart ID
  |--------------------------------------------------------------------------
  */

  if ($cart_id === '' && $product_id === '') {
    throw new Exception('Product ID or Cart ID is required.');
  }


  /*
  |--------------------------------------------------------------------------
  | Build WHERE condition
  |--------------------------------------------------------------------------
  */

  $whereCondition = "";


  /*
  |--------------------------------------------------------------------------
  | CASE 1: cart_id provided
  |--------------------------------------------------------------------------
  | Most precise option.
  |--------------------------------------------------------------------------
  */

  if ($cart_id !== '') {

    $cart_id = $conn->real_escape_string($cart_id);

    $whereCondition = "
            cart.cart_id = '$cart_id'
            AND cart.user_id = '$user_id'
            AND cart.cart_status = 'Cart'
        ";
  }


  /*
  |--------------------------------------------------------------------------
  | CASE 2: Product + Variation + Color
  |--------------------------------------------------------------------------
  */ elseif (
    $product_id !== '' &&
    $variation_id !== '' &&
    $variation_color_id !== ''
  ) {

    $product_id = $conn->real_escape_string($product_id);
    $variation_id = $conn->real_escape_string($variation_id);
    $variation_color_id = $conn->real_escape_string($variation_color_id);

    $whereCondition = "
            cart.user_id = '$user_id'
            AND cart.product_id = '$product_id'
            AND cart.variation_id = '$variation_id'
            AND cart.variation_color_id = '$variation_color_id'
            AND cart.cart_status = 'Cart'
        ";
  }


  /*
  |--------------------------------------------------------------------------
  | CASE 3: Product + Variation only
  |--------------------------------------------------------------------------
  */ elseif (
    $product_id !== '' &&
    $variation_id !== ''
  ) {

    $product_id = $conn->real_escape_string($product_id);
    $variation_id = $conn->real_escape_string($variation_id);

    $whereCondition = "
            cart.user_id = '$user_id'
            AND cart.product_id = '$product_id'
            AND cart.variation_id = '$variation_id'
            AND cart.cart_status = 'Cart'
        ";
  }


  /*
  |--------------------------------------------------------------------------
  | CASE 4: Product without variation
  |--------------------------------------------------------------------------
  */ elseif ($product_id !== '') {

    $product_id = $conn->real_escape_string($product_id);

    $whereCondition = "
            cart.user_id = '$user_id'
            AND cart.product_id = '$product_id'
            AND cart.variation_id IS NULL
            AND cart.cart_status = 'Cart'
        ";
  }


  /*
  |--------------------------------------------------------------------------
  | Fetch current cart item
  |--------------------------------------------------------------------------
  */

  $query = "
        SELECT
            cart.cart_id,
            cart.cart_quantity,
            cart.product_id,
            cart.variation_id,
            cart.variation_color_id,
            product.product_sellingprice,
            variations.value,
            variations.variation_id AS variation_table_id,
            variations.price AS variation_price

        FROM cart

        LEFT JOIN product
            ON cart.product_id = product.product_id

        LEFT JOIN variations
            ON cart.variation_id = variations.variation_id

        WHERE $whereCondition

        LIMIT 1
    ";


  $result = $conn->query($query);


  if (!$result) {
    throw new Exception(
      "Database query failed: " . $conn->error
    );
  }


  $cartItem = $result->fetch_assoc();


  if (!$cartItem) {
    throw new Exception('Item not found in cart.');
  }


  /*
  |--------------------------------------------------------------------------
  | Current quantity
  |--------------------------------------------------------------------------
  */

  $currentQuantity = (int) $cartItem['cart_quantity'];


  /*
  |--------------------------------------------------------------------------
  | Determine price
  |--------------------------------------------------------------------------
  |
  | If variation has a price:
  |     use variation price
  |
  | Otherwise:
  |     use product selling price
  |--------------------------------------------------------------------------
  */

  if (
    $cartItem['variation_id'] !== null &&
    $cartItem['variation_price'] !== null
  ) {

    $price = (float) $cartItem['variation_price'];

  } else {

    $price = (float) $cartItem['product_sellingprice'];
  }


  /*
  |--------------------------------------------------------------------------
  | Calculate new quantity
  |--------------------------------------------------------------------------
  */

  if ($action === 'increase') {

    $newQuantity = $currentQuantity + 1;

  } else {

    // Never allow quantity to go below 1
    $newQuantity = max($currentQuantity - 1, 1);
  }


  /*
  |--------------------------------------------------------------------------
  | Calculate new total
  |--------------------------------------------------------------------------
  */

  $newTotalPrice = $newQuantity * $price;


  /*
  |--------------------------------------------------------------------------
  | Update cart
  |--------------------------------------------------------------------------
  */

  $updateQuery = "
        UPDATE cart

        SET
            cart_quantity = '$newQuantity',
            total_price = '$newTotalPrice'

        WHERE $whereCondition
    ";


  if (!$conn->query($updateQuery)) {

    throw new Exception(
      "Update failed: " . $conn->error
    );
  }


  /*
  |--------------------------------------------------------------------------
  | Response
  |--------------------------------------------------------------------------
  */

  $response['success'] = true;
  $response['message'] = 'Cart updated successfully.';
  $response['cart_id'] = $cartItem['cart_id'];
  $response['new_quantity'] = $newQuantity;
  $response['new_total_price'] = $newTotalPrice;


} catch (Exception $e) {

  $response['success'] = false;
  $response['message'] = $e->getMessage();
}


/*
|--------------------------------------------------------------------------
| Return JSON
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json');

echo json_encode($response);
exit();
?>