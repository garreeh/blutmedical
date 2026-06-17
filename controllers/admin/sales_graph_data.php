<?php
include '../../connections/connections.php';

$type = $_GET['type'];

$data = [];
$labels = [];

if ($type == 'daily') {

  $sql = "
    SELECT HOUR(cart.updated_at) as label,
           SUM(product.product_sellingprice * cart.cart_quantity) as total
    FROM cart
    LEFT JOIN product ON product.product_id = cart.product_id
    WHERE cart.cart_status = 'Delivered'
    AND DATE(cart.updated_at) = CURDATE()
    GROUP BY HOUR(cart.updated_at)
    ORDER BY label
    ";

} elseif ($type == 'monthly') {

  $sql = "
    SELECT DATE(cart.updated_at) as label,
           SUM(product.product_sellingprice * cart.cart_quantity) as total
    FROM cart
    LEFT JOIN product ON product.product_id = cart.product_id
    WHERE cart.cart_status = 'Delivered'
    AND MONTH(cart.updated_at) = MONTH(CURDATE())
    GROUP BY DATE(cart.updated_at)
    ORDER BY label
    ";

} else { // annual

  $sql = "
    SELECT MONTH(cart.updated_at) as label,
           SUM(product.product_sellingprice * cart.cart_quantity) as total
    FROM cart
    LEFT JOIN product ON product.product_id = cart.product_id
    WHERE cart.cart_status = 'Delivered'
    AND YEAR(cart.updated_at) = YEAR(CURDATE())
    GROUP BY MONTH(cart.updated_at)
    ORDER BY label
    ";
}

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {

  $labels[] = $row['label'];
  $data[] = $row['total'];
}

echo json_encode([
  'labels' => $labels,
  'data' => $data
]);