<?php
include '../../connections/connections.php';

$subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;


$sql = "SELECT * FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['category_name']) . '</option>';
  }
}
