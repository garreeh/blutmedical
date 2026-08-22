<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

file_put_contents("debug_session.txt", print_r($_SESSION, true));
file_put_contents("debug_post.txt", print_r($_POST, true));

include '../../connections/connections.php';

header('Content-Type: application/json');


// Retrieve product_id and cart_quantity from POST
if (isset($_POST['product_id']) && isset($_POST['cart_quantity'])) {

    $product_id = $conn->real_escape_string($_POST['product_id']);
    $cart_quantity = (int) $_POST['cart_quantity'];

    /*
    |--------------------------------------------------------------------------
    | Variation ID
    |--------------------------------------------------------------------------
    */
    $variation_id = (
        isset($_POST['variation_id']) &&
        $_POST['variation_id'] !== '' &&
        is_numeric($_POST['variation_id'])
    ) ? (int) $_POST['variation_id'] : null;


    /*
    |--------------------------------------------------------------------------
    | Variation Color ID
    |--------------------------------------------------------------------------
    */
    $variation_color_id = (
        isset($_POST['variation_color_id']) &&
        $_POST['variation_color_id'] !== '' &&
        is_numeric($_POST['variation_color_id'])
    ) ? (int) $_POST['variation_color_id'] : null;


    /*
    |--------------------------------------------------------------------------
    | Cart ID (optional)
    |--------------------------------------------------------------------------
    | This is useful if you specifically want to identify an existing
    | cart row.
    |--------------------------------------------------------------------------
    */
    $cart_id = (
        isset($_POST['cart_id']) &&
        $_POST['cart_id'] !== '' &&
        is_numeric($_POST['cart_id'])
    ) ? (int) $_POST['cart_id'] : null;


    /*
    |--------------------------------------------------------------------------
    | Validate quantity
    |--------------------------------------------------------------------------
    */
    if ($cart_quantity <= 0) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Invalid cart quantity.'
        ));
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Check logged-in user
    |--------------------------------------------------------------------------
    */
    if (!isset($_SESSION['user_id'])) {

        $response = array(
            'success' => false,
            'message' => 'User not logged in.'
        );

        echo json_encode($response);
        exit();
    }


    $user_id = $conn->real_escape_string($_SESSION['user_id']);


    /*
    |--------------------------------------------------------------------------
    | Get product price
    |--------------------------------------------------------------------------
    */

    $product_price = 0;

    $product_query = "
        SELECT 
            product_stocks,
            product_sellingprice 
        FROM product 
        WHERE product_id = '$product_id'
    ";

    $product_result = $conn->query($product_query);


    if (!$product_result) {

        echo json_encode(array(
            'success' => false,
            'message' => 'Error fetching product: ' . $conn->error
        ));

        exit();
    }


    if ($product_result->num_rows > 0) {

        $product_row = $product_result->fetch_assoc();

        $product_price = $product_row['product_sellingprice'];


        /*
        |--------------------------------------------------------------------------
        | If variation exists, get variation price
        |--------------------------------------------------------------------------
        */

        if ($variation_id !== null) {

            $variation_query = "
                SELECT price 
                FROM variations 
                WHERE product_id = '$product_id'
                AND variation_id = '$variation_id'
            ";

            $variation_result = $conn->query($variation_query);


            if (!$variation_result) {

                echo json_encode(array(
                    'success' => false,
                    'message' => 'Error fetching variation: ' . $conn->error
                ));

                exit();
            }


            if ($variation_result->num_rows > 0) {

                $variation_row = $variation_result->fetch_assoc();

                $product_price = $variation_row['price'];
            }
        }

    } else {

        echo json_encode(array(
            'success' => false,
            'message' => 'Product not found.'
        ));

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND EXISTING CART ITEM
    |--------------------------------------------------------------------------
    |
    | Priority:
    |
    | 1. If cart_id is supplied -> use cart_id.
    |
    | 2. If variation_id + variation_color_id exist
    |       -> match product + variation + color.
    |
    | 3. If variation_id exists but color does not
    |       -> match product + variation.
    |
    | 4. If no variation exists
    |       -> match product only.
    |
    |--------------------------------------------------------------------------
    */

    $cart_query = "";


    // ---------------------------------------------------------
    // CASE 1: cart_id provided
    // ---------------------------------------------------------

    if ($cart_id !== null) {

        $cart_query = "
            SELECT 
                cart_id,
                cart_quantity,
                cart_status
            FROM cart
            WHERE cart_id = '$cart_id'
            AND user_id = '$user_id'
            AND cart_status = 'Cart'
            LIMIT 1
        ";
    }


    // ---------------------------------------------------------
    // CASE 2: variation_id + variation_color_id
    // ---------------------------------------------------------
    elseif (
        $variation_id !== null &&
        $variation_color_id !== null
    ) {

        $cart_query = "
            SELECT 
                cart_id,
                cart_quantity,
                cart_status
            FROM cart
            WHERE user_id = '$user_id'
            AND product_id = '$product_id'
            AND variation_id = '$variation_id'
            AND variation_color_id = '$variation_color_id'
            AND cart_status = 'Cart'
            LIMIT 1
        ";
    }


    // ---------------------------------------------------------
    // CASE 3: variation_id only
    // ---------------------------------------------------------
    elseif ($variation_id !== null) {

        $cart_query = "
            SELECT 
                cart_id,
                cart_quantity,
                cart_status
            FROM cart
            WHERE user_id = '$user_id'
            AND product_id = '$product_id'
            AND variation_id = '$variation_id'
            AND cart_status = 'Cart'
            LIMIT 1
        ";
    }


    // ---------------------------------------------------------
    // CASE 4: no variation
    // ---------------------------------------------------------
    else {

        $cart_query = "
            SELECT 
                cart_id,
                cart_quantity,
                cart_status
            FROM cart
            WHERE user_id = '$user_id'
            AND product_id = '$product_id'
            AND variation_id IS NULL
            AND cart_status = 'Cart'
            LIMIT 1
        ";
    }


    /*
    |--------------------------------------------------------------------------
    | Execute cart lookup
    |--------------------------------------------------------------------------
    */

    $cart_result = $conn->query($cart_query);


    if (!$cart_result) {

        echo json_encode(array(
            'success' => false,
            'message' => 'Error checking cart: ' . $conn->error
        ));

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | EXISTING CART ITEM
    |--------------------------------------------------------------------------
    */

    if ($cart_result->num_rows > 0) {

        $cart_row = $cart_result->fetch_assoc();

        $existing_cart_id = $cart_row['cart_id'];
        $existing_quantity = (int) $cart_row['cart_quantity'];

        $new_quantity = $existing_quantity + $cart_quantity;

        $total_price = $new_quantity * $product_price;


        /*
        |--------------------------------------------------------------------------
        | Update existing cart row
        |--------------------------------------------------------------------------
        */

        $update_query = "
            UPDATE cart
            SET 
                cart_quantity = '$new_quantity',
                total_price = '$total_price'
            WHERE cart_id = '$existing_cart_id'
            AND user_id = '$user_id'
            AND cart_status = 'Cart'
        ";


        if ($conn->query($update_query)) {

            $response = array(
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_id' => $existing_cart_id,
                'cart_quantity' => $new_quantity
            );

        } else {

            $response = array(
                'success' => false,
                'message' => 'Error updating cart: ' . $conn->error
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NEW CART ITEM
        |--------------------------------------------------------------------------
        */

    } else {

        $total_price = $cart_quantity * $product_price;


        /*
        |--------------------------------------------------------------------------
        | Convert NULL values correctly for SQL
        |--------------------------------------------------------------------------
        */

        $variationIdValue = (
            $variation_id !== null
        ) ? $variation_id : "NULL";


        $variationColorValue = (
            $variation_color_id !== null
        ) ? $variation_color_id : "NULL";


        /*
        |--------------------------------------------------------------------------
        | Insert new cart item
        |--------------------------------------------------------------------------
        */

        $insert_query = "
            INSERT INTO cart
            (
                user_id,
                product_id,
                cart_quantity,
                total_price,
                cart_status,
                variation_id,
                variation_color_id,
                payment_status,
                delivery_guest_fullname,
                paypal_contact_number
            )
            VALUES
            (
                '$user_id',
                '$product_id',
                '$cart_quantity',
                '$total_price',
                'Cart',
                $variationIdValue,
                $variationColorValue,
                'Unpaid',
                '',
                ''
            )
        ";


        if ($conn->query($insert_query)) {

            $new_cart_id = $conn->insert_id;

            $response = array(
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_id' => $new_cart_id,
                'cart_quantity' => $cart_quantity
            );

        } else {

            $response = array(
                'success' => false,
                'message' => 'Error adding product to cart: ' . $conn->error
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Return response
    |--------------------------------------------------------------------------
    */

    echo json_encode($response);
    exit();


} else {

    $response = array(
        'success' => false,
        'message' => 'No product ID or quantity provided.'
    );

    echo json_encode($response);
    exit();
}
?>