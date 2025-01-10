<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <?php include 'assets.php'; ?>
  <title>Blut Medical</title>
</head>

<body>

  <?php

  include './includes/navigation.php';
  include './connections/connections.php';

  ?>

  <!-- Start Product Section -->
  <div class="product-section">
    <div class="container">
      <div class="row">
        <?php
        $sql = "SELECT * FROM product";
        $result = $conn->query($sql);

        // Check if there are any products
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $product_image = basename($row['product_image']);
            $image_url = './uploads/' . $product_image;
            $product_id = $row['product_id']; // Assuming the product_id is in the 'product_id' column
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
              <a href="product_details.php?product_id=<?php echo $product_id; ?>" target="_blank">
                <div class="product-item">
                  <!-- Wrap the product item in a link to redirect -->

                  <img src="<?php echo $image_url; ?>" class="img-fluid product-thumbnail"
                    style="height: 200px; width: 100%; object-fit: cover; border-radius: 10px;">
                  <h3 class="product-title" style="font-size: 1rem; text-align: center; margin-top: 10px;">
                    <?php echo htmlspecialchars($row['product_name']); ?>
                  </h3>
                  <strong class="product-price"
                    style="font-size: 1.2rem; margin-top: auto;">₱<?php echo number_format($row['product_sellingprice'], 2); ?></strong>
                </div>
              </a>
            </div>
            <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
  <!-- End Product Section -->

  <?php
  include './includes/footer.php';
  ?>


</body>

</html>