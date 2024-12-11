<?php
// Assuming you already have a connection to your database
// Get the current year
$currentYear = date('Y');

// Query to sum total_price for the current year's sales
$query = "SELECT SUM(total_price) as annual_sales FROM cart 
          WHERE YEAR(updated_at) = '$currentYear' 
          AND cart_status = 'Delivered'"; // Modify cart_status based on your use case

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// If there are no sales, set annual_sales to 0
$annual_sales = $row['annual_sales'] ?? 0;
?>
