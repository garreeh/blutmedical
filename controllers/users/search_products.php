<?php
include '../../connections/connections.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = isset($_POST['query']) ? trim($conn->real_escape_string($_POST['query'])) : '';
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

if ($query === '') {
  exit;
}

$likeQuery = "%$query%";

// Base query
$sql = "SELECT product_id, product_name 
        FROM product 
        WHERE product_name LIKE '$likeQuery'";

// Apply category filter only if not "All Categories"
if ($category_id > 0) {
  $sql .= " AND category_id = $category_id";
}

$sql .= " LIMIT 10";

$result = $conn->query($sql);

if (!$result) {
  echo "SQL Error: " . $conn->error;
  exit;
}

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo '<a href="product_details.php?product_id=' . $row['product_id'] . '" class="dropdown-item" target="_blank">'
      . htmlspecialchars($row['product_name']) .
      '</a>';
  }
} else {
  echo '<p class="dropdown-item text-muted">No products found</p>';
}
?>