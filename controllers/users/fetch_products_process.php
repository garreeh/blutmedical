<?php
include '../../connections/connections.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

if ($category_id) {
  $query = "SELECT * FROM product LEFT JOIN category ON product.category_id = category.category_id WHERE product.category_id = $category_id";
} else {
  $query = "SELECT * FROM product LEFT JOIN category ON product.category_id = category.category_id";
}

$result = $conn->query($query);
$output = '';

if ($result) {
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $product_image = basename($row['product_image']);
      $image_url = './uploads/' . $product_image;
      $output .= '
                <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 2rem;">
                    <div class="product-item"
                        style="display: flex; flex-direction: column; padding-bottom: 5rem; border: 1px solid #ddd; border-radius: 10px; position: relative;">
                        <div class="pi-img-wrapper" style="text-align: center;">
                            <img src="' . $image_url . '" class="img-responsive"
                                alt="' . htmlspecialchars($row['product_name']) . '"
                                style="width: auto; height: 22rem; border-radius: 8px;">
                        </div>
                        <h3 style="text-align: center; color: #333;">
                            ' . htmlspecialchars($row['product_name']) . '
                        </h3>
                        <div class="pi-price" style="text-align: center; color: #2E8B57;">
                            ₱' . number_format($row['product_sellingprice'], 2) . '
                        </div>
                        <input type="hidden" class="product-id" value="' . $row['product_id'] . '" />

                        <div class="quantity-controls"
                            style="display: flex; justify-content: center; align-items: center; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f9f9f9; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <button class="minus-btn"
                                style="background-color: #ff6f61; color: white; border: none; border-radius: 5px; width: 50px; height: 50px; cursor: pointer; transition: background-color 0.3s; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                                -
                            </button>
                            <input type="number" class="cart_quantity" value="1" min="1"
                                style="width: 60px; text-align: center; border: none; outline: none; font-size: 1.5rem; margin: 0 10px; border-radius: 5px; height: 50px;">
                            <button class="add-btn"
                                style="background-color: #4CAF50; color: white; border: none; border-radius: 5px; width: 50px; height: 50px; cursor: pointer; transition: background-color 0.3s; font-size: 1.5rem; display: flex; align-items: center; justify-content: center;">
                                +
                            </button>
                        </div>
                        
                        <a href="javascript:void(0)" class="btn btn-default add-to-cart add2cart"
                            data-product-id="' . $row['product_id'] . '"
                            style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); width: 90%; text-align: center; padding: 0.5rem 0; background-color: #4682B4; color: #fff; font-weight: bold !important;">Add
                            to cart</a>
                    </div>
                </div>
            ';
    }
  } else {
    // No products found
    $output .= '<div class="col-xs-12" style="text-align: center; color: red; font-size: 1.5rem;">No Products Available</div>';
  }
} else {
  // Error in query execution
  $output .= '<div class="col-xs-12" style="text-align: center; color: red; font-size: 1.5rem;">Error fetching products. Please try again later.</div>';
}

echo $output;
