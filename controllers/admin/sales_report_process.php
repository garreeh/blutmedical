<?php
include './../../connections/connections.php';

$total_sales = 0; // Initialize total sales

// Check if the button is clicked
if (isset($_POST['searchSalesReport'])) {
    $date_from = $_POST['date_from'] . ' 00:00:00'; // Append time for start of day
    $date_to = $_POST['date_to'] . ' 23:59:59'; // Append time for end of day

    // Validate dates
    if ($date_from && $date_to) {
        // Prepare and execute the SQL statement to get total sales
        $query = "SELECT SUM(total_price) AS total_sales FROM cart WHERE updated_at BETWEEN '$date_from' AND '$date_to' AND cart_status = 'Delivered'";
        $result = $conn->query($query);

        if ($result) {
            $data = $result->fetch_assoc();
            $total_sales = $data['total_sales'] ? $data['total_sales'] : 0;
        }
    }
}

// Format total sales with peso sign
$formatted_sales = '$ ' . number_format($total_sales, 2);

// Return total sales as JSON for AJAX
echo json_encode(['total_sales' => $formatted_sales]);
