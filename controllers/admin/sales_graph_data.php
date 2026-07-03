<?php
include '../../connections/connections.php';

$type = $_GET['type'];

$data = [];
$labels = [];

if ($type == 'daily') {

  $sql = "
    SELECT
        label,
        SUM(final_total) AS total
    FROM (

        SELECT
            COALESCE(cart.reference_no, cart.paypal_order_id) AS order_ref,
            HOUR(cart.updated_at) AS label,

            SUM(product.product_sellingprice * cart.cart_quantity) AS subtotal,

            MAX(IFNULL(voucher.voucher_percentage, 0)) AS voucher_percent,

            (
                SUM(product.product_sellingprice * cart.cart_quantity)
                -
                (
                    SUM(product.product_sellingprice * cart.cart_quantity)
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

        GROUP BY order_ref, HOUR(cart.updated_at)

    ) t
    GROUP BY label
    ORDER BY label
  ";

} elseif ($type == 'monthly') {

  $sql = "
    SELECT
        label,
        SUM(final_total) AS total
    FROM (

        SELECT
            COALESCE(cart.reference_no, cart.paypal_order_id) AS order_ref,
            DATE(cart.updated_at) AS label,

            SUM(product.product_sellingprice * cart.cart_quantity) AS subtotal,

            MAX(IFNULL(voucher.voucher_percentage, 0)) AS voucher_percent,

            (
                SUM(product.product_sellingprice * cart.cart_quantity)
                -
                (
                    SUM(product.product_sellingprice * cart.cart_quantity)
                    * MAX(IFNULL(voucher.voucher_percentage, 0)) / 100
                )
            ) AS final_total

        FROM cart
        LEFT JOIN product
            ON product.product_id = cart.product_id
        LEFT JOIN voucher
            ON voucher.voucher_id = cart.voucher_id

        WHERE cart.cart_status = 'Delivered'
          AND MONTH(cart.updated_at) = MONTH(CURDATE())
          AND YEAR(cart.updated_at) = YEAR(CURDATE())

        GROUP BY order_ref, DATE(cart.updated_at)

    ) t
    GROUP BY label
    ORDER BY label
  ";

} else {

  $sql = "
    SELECT
        label,
        SUM(final_total) AS total
    FROM (

        SELECT
            COALESCE(cart.reference_no, cart.paypal_order_id) AS order_ref,
            MONTH(cart.updated_at) AS label,

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
        LEFT JOIN voucher
            ON voucher.voucher_id = cart.voucher_id

        WHERE cart.cart_status = 'Delivered'
          AND YEAR(cart.updated_at) = YEAR(CURDATE())

        GROUP BY order_ref, MONTH(cart.updated_at)

    ) t
    GROUP BY label
    ORDER BY label
  ";
}

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $labels[] = $row['label'];
  $data[] = (float) $row['total'];
}

echo json_encode([
  'labels' => $labels,
  'data' => $data
]);
?>