<?php
// Assuming you already have a connection to your database
// Get today's date
$today = date('Y-m-d');

// Query to sum total_price for today's sales
$query = "SELECT SUM(total_price) as daily_sales FROM cart WHERE DATE(updated_at) = '$today' AND cart_status = 'Delivered'"; // Modify cart_status based on your use case

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// If there are no sales, set daily_sales to 0
$daily_sales = $row['daily_sales'] ?? 0;

?>
