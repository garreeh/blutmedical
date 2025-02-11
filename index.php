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
  <title>Blüt Medical</title>
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
        <div class="col-lg-7">
          <div class="intro-excerpt">
            <h1>Welcome to BLüT Medical
            </h1>
            <p class="mb-4">We are a provider of innovative premium quality products that will elevate any medical
              practice be it for veterinarians or human doctors.</p>
            <p><a href="products.php" class="btn btn-secondary me-2">Shop Now</a></p>
          </div>
        </div>
        <div class="col-lg-5 d-none d-md-block">
          <div class="hero-img-wrap">
            <img src="assets/logo/blutfront.png" class="img-fluid" style="max-width: 75%;">
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="product-section">
    <div class="container">
      <nav class="d-flex justify-content-between align-items-center" aria-label="breadcrumb">
        <!-- Breadcrumb on the left -->
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        </ol>

        <!-- <div class="search-bar">
          <input type="text" id="searchInput" class="form-control d-none d-md-block" placeholder="Search products..."
            autocomplete="off" style="width: 35rem;">
          <div id="searchResults" class="dropdown-menu" style="width: 35rem;"></div>

        </div> -->

        <div class="dropdown-category">
          <select id="categoryDropdown" class="form-control" aria-label="Select Category" style="width: 13rem;">
            <option value="" selected>All Categories</option>
            <!-- Categories will be dynamically populated -->
          </select>
        </div>

      </nav>

      <br>

      <div class="row" id="productList">
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
                  <strong class="product-price" style="font-size: 1.2rem; margin-top: auto;">$
                    <?php echo number_format($row['product_sellingprice'], 2); ?></strong>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
  // AJAX for Search Bar using jQuery
  $('#searchInput').on('input', function() {
    const query = $(this).val().trim();
    if (query.length > 0) {
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/search_products.php',
        data: {
          query: query
        },
        success: function(response) {
          $('#searchResults').html(response).addClass('show');
        }
      });
    } else {
      $('#searchResults').removeClass('show');
    }
  });

  // Hide search results when clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('#searchInput, #searchResults').length) {
      $('#searchResults').removeClass('show');
    }
  });

  $(document).ready(function() {
    // Populate categories in the dropdown
    $.ajax({
      type: 'GET',
      url: '/blutmedical/controllers/users/fetch_categories.php',
      success: function(response) {
        $('#categoryDropdown').append(response);
      },
      error: function() {
        $('#categoryDropdown').append('<option disabled>Error loading categories</option>');
      }
    });

    // Handle category change
    $('#categoryDropdown').on('change', function() {
      const categoryId = $(this).val();

      // Fetch products by selected category
      $.ajax({
        type: 'GET',
        url: '/blutmedical/controllers/users/fetch_products_by_category.php',
        data: {
          category_id: categoryId
        },
        success: function(response) {
          $('#productList').html(response);
        },
        error: function() {
          $('#productList').html('<p>Error loading products. Please try again.</p>');
        }
      });
    });
  });
</script>