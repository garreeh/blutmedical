<?php
include '../../connections/connections.php';


if (isset($_POST['query'])) {
  $query = $conn->real_escape_string($_POST['query']);
  $sql = "SELECT product_id, product_name FROM product WHERE product_name LIKE '%$query%' LIMIT 5";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<a href="product_details.php?product_id=' . $row['product_id'] . '" class="dropdown-item" target="_blank">' . htmlspecialchars($row['product_name']) . '</a>';
    }
  } else {
    echo '<p class="dropdown-item text-muted">No products found</p>';
  }
}
?>