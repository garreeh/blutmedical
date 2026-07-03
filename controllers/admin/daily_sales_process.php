<?php

$daily_sales = 0;

$sql = "
SELECT SUM(final_total) AS total_sales FROM (

    SELECT
        COALESCE(cart.reference_no, cart.paypal_order_id) AS order_ref,

        SUM(cart.total_price * cart.cart_quantity) AS subtotal,

        MAX(IFNULL(voucher.voucher_percentage, 0)) AS voucher_percent,

        (
            SUM(cart.total_price * cart.cart_quantity)
            -
            (
                SUM(cart.total_price * cart.cart_quantity)
                * MAX(IFNULL(voucher.voucher_percentage, 0)) / 100
            )
        ) AS final_total

    FROM cart
    LEFT JOIN product
        ON product.product_id = cart.product_id
    LEFT JOIN voucher
        ON voucher.voucher_id = cart.voucher_id

    WHERE cart.cart_status = 'Delivered'
    AND DATE(cart.updated_at) = CURDATE()

    GROUP BY order_ref

) t
";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $daily_sales = $row['total_sales'] ?? 0;
}

?>