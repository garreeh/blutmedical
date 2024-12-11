<?php
// Assuming you already have a connection to your database
// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Query to sum total_price for the current month's sales
$query = "SELECT SUM(total_price) as monthly_sales FROM cart 
          WHERE MONTH(updated_at) = '$currentMonth' AND YEAR(updated_at) = '$currentYear' 
          AND cart_status = 'Delivered'"; // Modify cart_status based on your use case

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// If there are no sales, set monthly_sales to 0
$monthly_sales = $row['monthly_sales'] ?? 0;
?>
