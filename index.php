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
  include './connections/connections.php';
  include './includes/navigation.php';

  ?>

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Welcome to BLüT Medical
            </h1>
            <p class="mb-4">We are a provider of innovative premium quality products that will elevate any medical
              practice be it for veterinarians or human doctors.</p>
            <p><a href="products.php" class="btn btn-secondary me-2">Shop Now</a></p>
          </div>
        </div>
        <div class="col-lg-7 d-none d-md-block">
          <div class="hero-img-wrap">
            <img src="assets/logo/sample.png" class="img-fluid">
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="product-section">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        </ol>
      </nav>
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




  <div class="why-choose-section">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-6">
          <h2 class="section-title">Why Choose Us</h2>
          <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet
            dolor tempor tristique.</p>

          <div class="row my-5">
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/truck.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Fast &amp; Free Shipping</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/bag.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Easy to Shop</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/support.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>24/7 Support</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/return.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Hassle Free Returns</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-5">
          <div class="img-wrap">
            <img src="images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="we-help-section">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-7 mb-5 mb-lg-0">
          <div class="imgs-grid">
            <div class="grid grid-1"><img src="images/img-grid-1.jpg" alt="Untree.co"></div>
            <div class="grid grid-2"><img src="images/img-grid-2.jpg" alt="Untree.co"></div>
            <div class="grid grid-3"><img src="images/img-grid-3.jpg" alt="Untree.co"></div>
          </div>
        </div>
        <div class="col-lg-5 ps-lg-5">
          <h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
          <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac
            aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
            tristique senectus et netus et malesuada</p>

          <ul class="list-unstyled custom-list my-4">
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
          </ul>
          <p><a herf="#" class="btn">Explore</a></p>
        </div>
      </div>
    </div>
  </div>

  <div class="popular-product">
    <div class="container">
      <div class="row">

        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="images/product-1.png" alt="Image" class="img-fluid">
            </div>
            <div class="pt-3">
              <h3>Nordic Chair</h3>
              <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
              <p><a href="#">Read More</a></p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="images/product-2.png" alt="Image" class="img-fluid">
            </div>
            <div class="pt-3">
              <h3>Kruzo Aero Chair</h3>
              <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
              <p><a href="#">Read More</a></p>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
          <div class="product-item-sm d-flex">
            <div class="thumbnail">
              <img src="images/product-3.png" alt="Image" class="img-fluid">
            </div>
            <div class="pt-3">
              <h3>Ergonomic Chair</h3>
              <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
              <p><a href="#">Read More</a></p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php

  include './includes/footer.php';

  ?>



</body>

</html>

<!-- <script>
  // Get all the plus and minus buttons and product quantity inputs
  const minusButtons = document.querySelectorAll('.btn-minus');
  const plusButtons = document.querySelectorAll('.btn-plus');
  const quantityInputs = document.querySelectorAll('.product-quantity');

  // Function to update quantity based on button click
  function updateQuantity() {
    minusButtons.forEach((button, index) => {
      button.addEventListener('click', () => {
        let currentValue = parseInt(quantityInputs[index].value);
        if (currentValue > 1) {
          quantityInputs[index].value = currentValue - 1; // Decrease quantity
        }
      });
    });

    plusButtons.forEach((button, index) => {
      button.addEventListener('click', () => {
        let currentValue = parseInt(quantityInputs[index].value);
        quantityInputs[index].value = currentValue + 1; // Increase quantity
      });
    });
  }

  // Initialize the quantity functionality
  updateQuantity();




</script> -->