<?php
include '../../connections/connections.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

if ($category_id) {
  $sql = "SELECT * FROM product WHERE category_id = $category_id";
} else {
  $sql = "SELECT * FROM product"; // If no category is selected, show all products
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $product_image = basename($row['product_image']);
    $image_url = './uploads/' . $product_image;
    $product_id = $row['product_id'];
?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
      <a href="product_details.php?product_id=<?php echo $product_id; ?>" target="_blank">
        <div class="product-item">
          <img src="<?php echo $image_url; ?>" class="img-fluid product-thumbnail"
            style="height: 200px; width: 100%; object-fit: cover; border-radius: 10px;">
          <h3 class="product-title" style="font-size: 1rem; text-align: center; margin-top: 10px;">
            <?php echo htmlspecialchars($row['product_name']); ?>
          </h3>
          <strong class="product-price" style="font-size: 1.2rem; margin-top: auto;">
            <?php echo ($row['product_sellingprice'] == 0) ? 'Ask for Price' : '$ ' . number_format($row['product_sellingprice'], 2); ?>
          </strong>
        </div>
      </a>
    </div>
<?php
  }
} else {
  echo '<p>No products found in this category.</p>';
}
?>