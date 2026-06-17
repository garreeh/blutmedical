<?php

$annual_sales = 0;

$sql = "
SELECT SUM(product.product_sellingprice * cart.cart_quantity) AS total_sales
FROM cart
LEFT JOIN product ON product.product_id = cart.product_id
WHERE cart.cart_status = 'Delivered'
AND YEAR(cart.updated_at) = YEAR(CURDATE())
";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $annual_sales = $row['total_sales'] ?? 0;
}
?>