<?php
session_start();
include '../../connections/connections.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Not logged in.'
    ));
    exit();
}

$user_id = $conn->real_escape_string($_SESSION['user_id']);

/*
|--------------------------------------------------------------------------
| Get POST values
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| CASE 1: cart_id is provided
|--------------------------------------------------------------------------
| Delete the exact cart row.
|--------------------------------------------------------------------------
*/

if ($cart_id !== '') {

    $cart_id = $conn->real_escape_string($cart_id);

    $delete_query = "
        DELETE FROM cart
        WHERE user_id = '$user_id'
          AND cart_id = '$cart_id'
          AND cart_status = 'Cart'
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

    $delete_query = "
        DELETE FROM cart
        WHERE user_id = '$user_id'
          AND product_id = '$product_id'
          AND variation_id = '$variation_id'
          AND variation_color_id = '$variation_color_id'
          AND cart_status = 'Cart'
    ";
}


/*
|--------------------------------------------------------------------------
| CASE 3: Product + Variation only
|--------------------------------------------------------------------------
| variation_color_id is not provided.
|--------------------------------------------------------------------------
*/ elseif (
    $product_id !== '' &&
    $variation_id !== ''
) {

    $product_id = $conn->real_escape_string($product_id);
    $variation_id = $conn->real_escape_string($variation_id);

    $delete_query = "
        DELETE FROM cart
        WHERE user_id = '$user_id'
          AND product_id = '$product_id'
          AND variation_id = '$variation_id'
          AND cart_status = 'Cart'
    ";
}


/*
|--------------------------------------------------------------------------
| CASE 4: Product without variation
|--------------------------------------------------------------------------
| This handles products where variation_id is NULL or empty.
|--------------------------------------------------------------------------
*/ elseif ($product_id !== '') {

    $product_id = $conn->real_escape_string($product_id);

    $delete_query = "
        DELETE FROM cart
        WHERE user_id = '$user_id'
          AND product_id = '$product_id'
          AND variation_id IS NULL
          AND cart_status = 'Cart'
    ";
}


/*
|--------------------------------------------------------------------------
| Nothing valid was provided
|--------------------------------------------------------------------------
*/ else {

    echo json_encode(array(
        'success' => false,
        'message' => 'Cart ID or Product ID was not provided.'
    ));

    exit();
}


/*
|--------------------------------------------------------------------------
| Execute DELETE
|--------------------------------------------------------------------------
*/

if ($conn->query($delete_query)) {

    if ($conn->affected_rows > 0) {

        $response = array(
            'success' => true,
            'message' => 'Item removed from cart.'
        );

    } else {

        $response = array(
            'success' => false,
            'message' => 'Cart item not found or already removed.'
        );
    }

} else {

    $response = array(
        'success' => false,
        'message' => 'Error removing item from cart: ' . $conn->error
    );
}


/*
|--------------------------------------------------------------------------
| Return JSON response
|--------------------------------------------------------------------------
*/

echo json_encode($response);
exit();
?>