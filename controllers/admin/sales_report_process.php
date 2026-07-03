<?php
include './../../connections/connections.php';

$total_sales = 0;

if (isset($_POST['searchSalesReport'])) {

    $date_from = $_POST['date_from'] . ' 00:00:00';
    $date_to = $_POST['date_to'] . ' 23:59:59';

    if ($date_from && $date_to) {

        $query = "
        SELECT
            SUM(
                (
                    cart.total_price
                )
                -
                (
                    (cart.total_price * IFNULL(voucher.voucher_percentage, 0) / 100)
                )
            ) AS total_sales
        FROM cart
        LEFT JOIN voucher
            ON voucher.voucher_id = cart.voucher_id
        WHERE cart.updated_at BETWEEN '$date_from' AND '$date_to'
        AND cart.cart_status = 'Delivered'
        ";

        $result = $conn->query($query);

        if ($result) {
            $data = $result->fetch_assoc();
            $total_sales = $data['total_sales'] ?? 0;
        }
    }
}

$formatted_sales = '$ ' . number_format($total_sales, 2);

echo json_encode(['total_sales' => $formatted_sales]);
?>