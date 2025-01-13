<?php
include './connections/connections.php';

if (isset($_GET['product_id'])) {
  $product_id = $_GET['product_id'];

  // Updated query with LEFT JOIN on `variations` table
  $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    // Fetch product details and variations
    $product = mysqli_fetch_assoc($result);
    $product_image = basename($product['product_image']);
    $image_url = './uploads/' . $product_image;
    ?>

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

      <?php include './includes/navigation.php'; ?>

      <div class="product-section">
        <div class="container">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product Showcase</li>
            </ol>
          </nav>
          <div class="row">
            <div class="col-md-6 align-items-stretch">
              <!-- Card for Main Product Image and Thumbnails -->
              <div id="productStyles">
                <!-- Main Product Image with Zoom Effect -->
                <div class="zoom-container">
                  <img id="mainProductImage" src="<?php echo $image_url; ?>" class="img-fluid"
                    style="border-radius: 10px; object-fit: cover;">

                  <!-- Optional: Add zoom effect using CSS -->
                  <div id="zoomedImage" class="zoomed-image"></div>
                </div>

                <!-- Thumbnail Images below main image -->
                <div class="product-thumbnails">
                  <?php
                  // Display the main product image as the first thumbnail
                  echo '<img src="' . $image_url . '" class="img-thumbnail" alt="Product Image" style="cursor: pointer;" onclick="changeMainImage(\'' . $image_url . '\')">';

                  // Fetch all other product images from 'product_image' table
                  $queryImages = "SELECT product_image_path FROM product_image WHERE product_id = $product_id";
                  $resultImages = mysqli_query($conn, $queryImages);

                  if ($resultImages && mysqli_num_rows($resultImages) > 0) {
                    while ($image = mysqli_fetch_assoc($resultImages)) {
                      $productImagePath = './uploads/' . basename($image['product_image_path']);
                      echo '<img src="' . $productImagePath . '" class="img-thumbnail" alt="Product Image" style="cursor: pointer;" onclick="changeMainImage(\'' . $productImagePath . '\')">';
                    }
                  }
                  ?>
                </div>
              </div>
            </div>

            <div class="col-md-6 align-items-stretch">
              <!-- Card for Product Details -->
              <div id="productStyles">
                <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

                <hr>
                <i class="fas fa-box"></i> In stock: <?php echo htmlspecialchars($product['product_stocks']); ?>
                <hr>


                <?php
                // Fetch variations for the specific product
                $query = "SELECT * FROM variations WHERE product_id = $product_id";
                $result = mysqli_query($conn, $query);

                $variations = [];
                $initialPrice = 0;
                if ($result && mysqli_num_rows($result) > 0) {
                  $variations = mysqli_fetch_all($result, MYSQLI_ASSOC);
                  $initialPrice = $variations[0]['price']; // Use the price of the first variation
                }
                ?>

                <!-- Display the price -->
                <p class="text-muted">₱ <span id="productPrice"><?php echo number_format($initialPrice, 2); ?></span></p>
                <p><?php echo htmlspecialchars($product['product_description']); ?></p>

                <?php if (!empty($variations)) { ?>
                  <h4>Available Sizes:</h4>
                  <form id="sizeForm">
                    <?php foreach ($variations as $index => $variation) { ?>
                      <button type="button" class="btn variation-toggle <?php echo $index === 0 ? 'active' : ''; ?>"
                        data-bs-toggle="button" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>" autocomplete="off"
                        data-value="<?php echo htmlspecialchars($variation['variation_id']); ?>"
                        data-price="<?php echo htmlspecialchars($variation['price']); ?>">
                        <?php echo htmlspecialchars($variation['value']); ?>
                      </button>
                    <?php } ?>

                    <!-- Hidden input to store the selected variation -->
                    <input type="hidden" name="selected_variation" id="selectedVariation"
                      value="<?php echo $variations[0]['variation_id']; ?>">
                  </form>
                <?php } ?>

                <br>

                <!-- Quantity Selector -->
                <div>
                  <div class="input-group" style="max-width: 13rem;">
                    <button class="btn btn-outline-secondary" type="button" id="btn-minus">-</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="btn-plus">+</button>
                  </div>
                </div>

                <button class="btn btn-primary btn-lg mt-4" id="addToCartBtn">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Start Product Section -->
      <div class="footer-section">
        <div class="container">
          <h2>Product Description</h2>
        </div>
      </div>
      <!-- End Product Section -->

      <!-- Start Product Section -->
      <div class="footer-section" style="text-align: center;">
        <div class="container">
          <h2 style="margin-bottom: 20px;">You may also like</h2>
          <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-inner">
              <?php
              $sql = "SELECT * FROM product"; // Fetch all products
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                $products = [];
                while ($row = $result->fetch_assoc()) {
                  $products[] = $row;
                }

                $totalProducts = count($products);
                $productsPerSlide = 4;

                // Loop through the products and create carousel items
                for ($i = 0; $i < $totalProducts; $i++) {
                  if ($i % $productsPerSlide == 0) {
                    $isActive = ($i == 0) ? 'active' : ''; // Set first item as active
                    echo "<div class='carousel-item $isActive'>";
                    echo '<div class="row">';
                  }

                  $product = $products[$i];
                  $product_image = basename($product['product_image']);
                  $image_url = './uploads/' . $product_image;
                  $product_id = $product['product_id'];
                  $product_name = htmlspecialchars($product['product_name']);
                  $product_price = number_format($product['product_sellingprice'], 2);

                  echo <<<HTML
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
              <a href="product_details.php?product_id={$product_id}" target="_blank">
                <div class="product-item">
                  <img src="{$image_url}" class="img-fluid product-thumbnail"
                    style="height: 200px; width: 100%; object-fit: cover; border-radius: 10px;">
                  <h3 class="product-title" style="font-size: 1rem; text-align: center; margin-top: 10px;">{$product_name}</h3>
                  <strong class="product-price" style="font-size: 1.2rem; margin-top: auto;">₱{$product_price}</strong>
                </div>
              </a>
            </div>
            HTML;

                  // Close the row and carousel-item after every 4 products
                  if (($i + 1) % $productsPerSlide == 0 || $i == $totalProducts - 1) {
                    echo '</div>'; // Close row
                    echo '</div>'; // Close carousel-item
                  }
                }
              }
              ?>
            </div>

            <!-- Carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
      <!-- End Product Section -->




      <!-- Start Product Section -->
      <div class="product-section">
        <!-- THIS IS EMPTY FOR DIVISION -->
      </div>
      <!-- End Product Section -->

      <?php include './includes/footer.php'; ?>

    </body>

    </html>

    <style>
      #productStyles {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        height: 100%;
      }

      #mainProductImage {
        width: 100%;
        transition: transform 0.3s ease;
      }

      /* Hidden zoomed image container */
      #zoomedImage {
        position: absolute;
        /* Use absolute positioning */
        width: 150px;
        height: 150px;
        background-color: rgba(255, 255, 255, 0.8);
        display: none;
        background-size: contain;
        background-repeat: no-repeat;
        pointer-events: none;
        /* Prevent zoomed image from blocking clicks */
        border-radius: 40%;
        /* Circular effect */
        border: 3px solid #000;
        /* Border for better visibility */
        transition: transform 0.1s ease;
        z-index: 1000;
        /* Make sure it's above other elements */
      }

      /* Thumbnail Image Styling */
      .product-thumbnails img {
        max-width: 100px;
        margin-right: 10px;
      }

      /* Hover effect for thumbnails */
      .product-thumbnails img:hover {
        border: 2px solid #000;
      }

      .product-section h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #333333;
        margin-bottom: 1rem;
      }

      .product-section .text-muted {
        font-size: 1.2rem;
        color: #666666;
        margin-bottom: 1.5rem;
      }

      .product-section ul li {
        font-size: 1rem;
        color: #555555;
        margin-bottom: 0.5rem;
      }

      .product-section .form-control {
        font-size: 1.2rem;
        border: 1px solid #ddd;
        color: #333333;
      }

      /* Style for the toggle buttons */
      .variation-toggle {
        /* Default border */
        color: rgb(0, 0, 0);
        /* Default text color */
        background-color: transparent;
        /* Remove background */
        transition: all 0.3s ease;
      }

      /* When button is pressed/toggled */
      .variation-toggle.active,
      .variation-toggle:focus {
        border: 2px solid #007bff !important;
        /* Lighter blue when active */
        color: #0056b3 !important;
      }

      /* Prevents background color when clicked */
      .variation-toggle:active {
        background-color: transparent !important;
      }

      form {
        display: flex;
        gap: 10px;
      }
    </style>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
      const btnMinus = document.getElementById('btn-minus');
      const btnPlus = document.getElementById('btn-plus');
      const quantityInput = document.getElementById('quantity');

      btnMinus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
          quantityInput.value = currentValue - 1;
        }
      });

      btnPlus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
      });
      document.addEventListener('DOMContentLoaded', () => {
        // Attach click event to variation buttons
        const buttons = document.querySelectorAll('.variation-toggle');
        const productPrice = document.getElementById('productPrice');
        const selectedVariationInput = document.getElementById('selectedVariation');

        buttons.forEach(button => {
          button.addEventListener('click', () => {
            // Untoggle all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            buttons.forEach(btn => btn.setAttribute('aria-pressed', 'false'));

            // Toggle the clicked button
            button.classList.add('active');
            button.setAttribute('aria-pressed', 'true');

            // Update the displayed price
            const price = button.getAttribute('data-price');
            productPrice.textContent = parseFloat(price).toFixed(2);

            // Update the hidden input value
            const variationId = button.getAttribute('data-value');
            selectedVariationInput.value = variationId;
          });
        });
      });

      document.querySelector('#addToCartBtn').addEventListener('click', function () {
        var productId = <?php echo json_encode($product_id); ?>; // Safely output product ID
        var quantity = parseInt(document.getElementById('quantity').value) || 1; // Default to 1 if no valid quantity

        // Check if the selectedVariation element exists
        var selectedVariationElement = document.getElementById('selectedVariation');
        var selectedVariation = selectedVariationElement ? selectedVariationElement.value : null; // Set to null if no variation selected

        if (!productId) {
          console.error("Product ID is missing!");
          Toastify({
            text: 'Unable to add product to cart. Product ID is missing.',
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: '#FF0000', // Red
          }).showToast();
          return;
        }

        // Get the button and store original text
        var button = document.getElementById('addToCartBtn');
        var originalText = button.textContent;

        // Change the text to "Adding to Cart..."
        button.textContent = 'Adding to Cart...';

        // Disable the button during the process
        button.disabled = true;

        // Check if the user is logged in
        var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

        // Prepare cart data
        var cartData = {
          product_id: productId,
          cart_quantity: quantity,
          variation_id: selectedVariation,
        };

        if (isLoggedIn) {
          // User is logged in, make an AJAX call to add to the server cart
          fetch('/blutmedical/controllers/users/add_cart_process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(cartData),
          })
            .then((response) => response.json())
            .then((res) => {
              Toastify({
                text: res.message || (res.success ? 'Added to cart successfully!' : 'Failed to add to cart.'),
                duration: 3000,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: res.success ? '#4CAF50' : '#FF0000', // Green for success, Red for error
              }).showToast();

              // Update the cart badge after adding to the cart
              updateCartBadge(); // Call to update the cart badge
            })
            .catch((error) => {
              console.error('Error:', error);
              Toastify({
                text: 'An unexpected error occurred while adding to cart.',
                duration: 3000,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#FF0000', // Red
              }).showToast();
            })
            .finally(() => {
              // Restore the button text and enable the button again
              button.textContent = originalText;
              button.disabled = false;
            });
        } else {
          // User is not logged in, update the guest cart in localStorage
          var cart = JSON.parse(localStorage.getItem('guestCart')) || [];
          var existingProduct = cart.find(
            (item) => item.product_id === productId && item.variation_id === selectedVariation
          );

          if (existingProduct) {
            // Update quantity if the product already exists
            existingProduct.cart_quantity += quantity;
          } else {
            // Add new product
            cart.push(cartData);
          }

          localStorage.setItem('guestCart', JSON.stringify(cart));

          // Show success message
          Toastify({
            text: 'Added to cart as guest.',
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: '#4CAF50', // Green
          }).showToast();

          // Update the cart badge after adding to the cart
          updateCartBadge(); // Call to update the cart badge

          // Restore the button text and enable the button again
          button.textContent = originalText;
          button.disabled = false;
        }
      });

    </script>

    <!-- Zoom JavaScript -->
    <script>
      // Change the main image when a thumbnail is clicked
      function changeMainImage(imagePath) {
        document.getElementById('mainProductImage').src = imagePath;
      }

      // Handle zoom effect on hover
      var mainImage = document.getElementById('mainProductImage');
      var zoomedImage = document.getElementById('zoomedImage');

      mainImage.addEventListener('mousemove', function (e) {
        var zoomScale = 1.5; // Scale factor
        var offsetX = e.offsetX;
        var offsetY = e.offsetY;

        var x = (offsetX / mainImage.width) * 105;
        var y = (offsetY / mainImage.height) * 100;

        // Position the zoomed image next to the cursor
        var zoomedImageX = e.pageX + 20; // 20px offset from cursor
        var zoomedImageY = e.pageY + 20; // 20px offset from cursor

        zoomedImage.style.display = 'block';
        zoomedImage.style.backgroundImage = 'url(' + mainImage.src + ')';
        zoomedImage.style.backgroundPosition = x + '% ' + y + '%';
        zoomedImage.style.backgroundSize = (mainImage.width * zoomScale) + 'px ' + (mainImage.height * zoomScale) + 'px';
        zoomedImage.style.left = zoomedImageX + 'px';
        zoomedImage.style.top = zoomedImageY + 'px';
      });

      mainImage.addEventListener('mouseleave', function () {
        zoomedImage.style.display = 'none';
      });
    </script>

    <?php
  }
}
?>