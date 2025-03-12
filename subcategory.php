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

  $subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

  // Fetch subcategory_name
  $subcategory_name = "All Products"; // Default value
  if ($subcategory_id > 0) {
    $query = "SELECT subcategory_name FROM subcategory WHERE subcategory_id = $subcategory_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $subcategory_name = $row['subcategory_name'];
    }
  }

  ?>

  <!-- Start Product Section -->
  <div class="product-section">
    <div class="container">
      <nav class="d-flex justify-content-between align-items-center" aria-label="breadcrumb">
        <!-- Breadcrumb on the left -->
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($subcategory_name); ?></li>
        </ol>

        <!-- <div class="search-bar">
          <input type="text" id="searchInput" class="form-control d-none d-md-block" placeholder="Search products..."
            autocomplete="off" style="width: 35rem;">
          <div id="searchResults" class="dropdown-menu" style="width: 35rem;"></div>

        </div> -->

        <div class="dropdown-category">
          <!-- <select id="categoryDropdown" class="form-control" aria-label="Select Category" style="width: 13rem;">
            <option value="" selected>All Categories</option>
          </select> -->
        </div>

      </nav>

      <br>

      <!-- Product List -->
      <div class="row" id="productList">
        <?php
        $subcategory_id = isset($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;

        $sql = "SELECT * FROM category WHERE subcategory_id = $subcategory_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $category_id = $row['category_id'];
            $category_name = htmlspecialchars($row['category_name']);
            $category_image = !empty($row['category_image']) ? 'uploads/category/' . htmlspecialchars($row['category_image']) : null;
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
              <a href="category_based.php?category_id=<?php echo $category_id; ?>"
                style="text-decoration: none; color: inherit;">
                <div class="product-item" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 15px; 
                                      box-shadow: 0px 4px 10px rgba(0,0,0,0.1); transition: all 0.3s ease-in-out;
                                      background: #fff;">

                  <!-- Category Image or Default Icon -->
                  <?php if ($category_image && file_exists($category_image)) { ?>
                    <div style="width: 100%; height: 180px; overflow: hidden; border-radius: 10px; margin-bottom: 15px;">
                      <img src="<?php echo $category_image; ?>" alt="<?php echo $category_name; ?>"
                        style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                  <?php } else { ?>
                    <div style="width: 100%; height: 180px; background: linear-gradient(45deg, rgb(86, 13, 210), #feb47b); 
                                              display: flex; align-items: center; justify-content: center; 
                                              border-radius: 10px; margin-bottom: 15px;">
                      <i class="fas fa-tags" style="font-size: 4rem; color: #fff;"></i>
                    </div>
                  <?php } ?>

                  <!-- Category Name with ellipsis for long text -->
                  <p class="product-text" style="font-size: 1.2rem; font-weight: bold; color: #333; padding: 10px; 
                                                             border-radius: 10px; box-shadow: inset 0px 2px 5px rgba(0,0,0,0.1);
                                                             white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                                             max-width: 100%;">
                    <?php echo $category_name; ?>
                  </p>
                </div>
              </a>
            </div>
            <?php
          }
        } else {
          ?>
          <div class="col-12 text-center mt-5">
            <p style="font-size: 1.5rem; font-weight: bold; color: #555;">There’s no category here.</p>
          </div>
          <?php
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
  $('#searchInput').on('input', function () {
    const query = $(this).val().trim();
    if (query.length > 0) {
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/search_products.php',
        data: {
          query: query
        },
        success: function (response) {
          $('#searchResults').html(response).addClass('show');
        }
      });
    } else {
      $('#searchResults').removeClass('show');
    }
  });

  // Hide search results when clicking outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#searchInput, #searchResults').length) {
      $('#searchResults').removeClass('show');
    }
  });

  $(document).ready(function () {
    // Populate categories in the dropdown
    $.ajax({
      type: 'GET',
      url: '/blutmedical/controllers/users/fetch_categories.php',
      success: function (response) {
        $('#categoryDropdown').append(response);
      },
      error: function () {
        $('#categoryDropdown').append('<option disabled>Error loading categories</option>');
      }
    });

    // Handle category change
    $('#categoryDropdown').on('change', function () {
      const categoryId = $(this).val();
      const urlParams = new URLSearchParams(window.location.search);
      const subcategoryId = urlParams.get('subcategory_id') || 0;

      // Fetch products by selected category
      $.ajax({
        type: 'GET',
        url: '/blutmedical/controllers/users/fetch_products_by_category_subs.php',
        data: {
          category_id: categoryId,
          subcategory_id: subcategoryId
        },
        success: function (response) {
          $('#productList').html(response);
        },
        error: function () {
          $('#productList').html('<p>Error loading products. Please try again.</p>');
        }
      });
    });
  });
</script>