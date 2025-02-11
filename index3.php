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
        $product_image_no_base = $product['product_image'];

        $product_name = $product['product_name'];
        $product_sellingprice = $product['product_sellingprice'];


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
                                <i class="fas fa-box"></i> IN STOCK
                                <hr>

                                <?php
                                // Fetch variations for the specific product (Size)
                                $query = "SELECT * FROM variations WHERE product_id = $product_id";
                                $result = mysqli_query($conn, $query);

                                $variations = [];
                                $initialPrice = $product['product_sellingprice']; // Default product price
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $variations = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                    $initialPrice = $variations[0]['price']; // Default to the first variation price
                                }

                                // Fetch color variations
                                $colorQuery = "SELECT * FROM variations_colors WHERE product_id = $product_id";
                                $colorResult = mysqli_query($conn, $colorQuery);

                                $colors = [];
                                if ($colorResult && mysqli_num_rows($colorResult) > 0) {
                                    $colors = mysqli_fetch_all($colorResult, MYSQLI_ASSOC);
                                }
                                ?>

                                <!-- Display the price -->
                                <p class="text-muted">$ <span id="productPrice"><?php echo number_format($initialPrice, 2); ?></span></p>
                                <p><?php echo htmlspecialchars($product['product_description']); ?></p>

                                <!-- Product Form -->
                                <form id="productForm">
                                    <div class="variation-container">
                                        <!-- Size Variations -->
                                        <?php if (!empty($variations)) { ?>
                                            <h4>Available Sizes:</h4>
                                            <div class="size-variations">
                                                <?php foreach ($variations as $index => $variation) { ?>
                                                    <button type="button" class="btn variation-toggle <?php echo $index === 0 ? 'active' : ''; ?>"
                                                        data-bs-toggle="button" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>" autocomplete="off"
                                                        data-value="<?php echo htmlspecialchars(trim($variation['value'])); ?>"
                                                        data-price="<?php echo htmlspecialchars(trim($variation['price'])); ?>"
                                                        data-id="<?php echo htmlspecialchars(trim($variation['variation_id'])); ?>">
                                                        <?php echo $variation['value']; ?>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <!-- Hidden fields for size selection -->
                                        <input type="hidden" name="selected_variation" id="selectedVariationId" value="<?php echo trim($variations[0]['variation_id'] ?? '-'); ?>">
                                        <input type="hidden" name="selected_variation" id="selectedVariation" value="<?php echo trim($variations[0]['value'] ?? '-'); ?>">
                                        <input type="hidden" name="selected_price" id="selectedPrice" value="<?php echo trim($variations[0]['price'] ?? '-'); ?>">

                                        <!-- Color Variations (Now Positioned Below) -->
                                        <?php if (!empty($colors)) { ?>
                                            <h4>Available Colors:</h4>

                                            <div class="color-variations">
                                                <?php foreach ($colors as $index => $color) { ?>
                                                    <button type="button" class="btn color-toggle <?php echo $index === 0 ? 'active' : ''; ?>"
                                                        data-bs-toggle="button" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>" autocomplete="off"
                                                        data-color="<?php echo htmlspecialchars(trim($color['color'])); ?>"
                                                        data-id="<?php echo htmlspecialchars(trim($color['variation_color_id'])); ?>">
                                                        <?php echo ucfirst($color['color']); ?>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <br>

                                            <!-- Hidden fields for selected color and color ID -->
                                            <input type="text" name="selected_color_id" id="selectedColorId" value="<?php echo trim($colors[0]['variation_color_id'] ?? '-'); ?>">
                                            <input type="text" name="selected_color_name" id="selectedColorName" value="<?php echo trim($colors[0]['color'] ?? '-'); ?>">
                                        <?php } ?>

                                    </div>

                                    <!-- Other hidden inputs -->
                                    <input type="hidden" name="product_name" id="product_name" value="<?php echo $product_name; ?>">
                                    <input type="hidden" name="product_image" id="product_image" value="<?php echo $product_image_no_base; ?>">
                                    <input type="hidden" name="product_sellingprice" id="product_sellingprice" value="<?php echo $product_sellingprice; ?>">
                                </form>

                                <!-- CSS Styling -->
                                <style>
                                    .variation-container {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 10px;
                                        /* Space between sections */
                                    }

                                    .size-variations,
                                    .color-variations {
                                        display: flex;
                                        flex-wrap: wrap;
                                        gap: 5px;
                                    }
                                </style>


                                <!-- Quantity Selector -->
                                <div>
                                    <div class="input-group" style="max-width: 13rem;">
                                        <button class="btn btn-outline-secondary" type="button" id="btn-minus">-</button>
                                        <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" readonly>
                                        <button class="btn btn-outline-secondary" type="button" id="btn-plus">+</button>
                                    </div>
                                </div>

                                <!-- Add to Cart Button -->
                                <button class="btn btn-primary btn-lg mt-4" id="addToCartBtn">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Product Section -->
            <!-- <div class="footer-section">
        <div class="container">
          <h2>Product Description</h2>
          <p></?php echo $product['product_description']; ?></p>
        </div>
      </div> -->
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
                                    $product_id_you = $product['product_id'];

                                    $product_name = htmlspecialchars($product['product_name']);
                                    $product_price = number_format($product['product_sellingprice'], 2);

                            ?>

                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">
                                        <a href="product_details.php?product_id=<?php echo $product_id_you; ?>" target="_blank">
                                            <div class="product-item">
                                                <img src="<?php echo $image_url; ?>" class="img-fluid product-thumbnail"
                                                    style="height: 200px; width: 100%; object-fit: cover; border-radius: 10px;">
                                                <h3 class="product-title" style="font-size: 1rem; text-align: center; margin-top: 10px;">
                                                    <?php echo $product_name; ?></h3>
                                                <strong class="product-price" style="font-size: 1.2rem; margin-top: auto;">$
                                                    <?php echo $product_price; ?></strong>
                                            </div>
                                        </a>
                                    </div>
                            <?php

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
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                /* Set the arrow background to black */
                background-image: none;
                /* Remove the default icon */
            }

            .carousel-control-prev-icon::after,
            .carousel-control-next-icon::after {
                content: '';
                /* Clear any default content */
                display: inline-block;
                border: solid black;
                /* Set arrow color */
                border-width: 0 5px 5px 0;
                padding: 10px;
            }

            .carousel-control-prev-icon::after {
                transform: rotate(135deg);
                /* Left arrow */
            }

            .carousel-control-next-icon::after {
                transform: rotate(-45deg);
                /* Right arrow */
            }

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

            .color-toggle {
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

            .color-toggle.active,
            .color-toggle:focus {
                border: 2px solid #007bff !important;
                /* Lighter blue when active */
                color: #0056b3 !important;
            }

            /* Prevents background color when clicked */
            .variation-toggle:active {
                background-color: transparent !important;
            }

            .color-toggle:active {
                background-color: transparent !important;
            }

            form {
                display: flex;
                gap: 10px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

        <script>
            document.querySelectorAll(".color-toggle").forEach(button => {
                button.addEventListener("click", function() {
                    // Remove 'active' class from all color buttons
                    document.querySelectorAll(".color-toggle").forEach(btn => btn.classList.remove("active"));

                    // Add 'active' class to the clicked button
                    this.classList.add("active");

                    // Set the hidden input fields to store the selected color ID and color name
                    document.getElementById("selectedColorId").value = this.getAttribute("data-id"); // variation_color_id
                    document.getElementById("selectedColorName").value = this.getAttribute("data-color"); // color name
                });
            });

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
                const selectedPriceInput = document.getElementById('selectedPrice');
                const selectedVariationIdInput = document.getElementById('selectedVariationId'); // New input for variation_id

                buttons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Untoggle all buttons
                        buttons.forEach(btn => {
                            btn.classList.remove('active');
                            btn.setAttribute('aria-pressed', 'false');
                        });

                        // Toggle the clicked button
                        button.classList.add('active');
                        button.setAttribute('aria-pressed', 'true');

                        // Update the displayed price
                        const price = button.getAttribute('data-price');
                        productPrice.textContent = parseFloat(price).toFixed(2);

                        // Update the hidden input value for variation
                        const variationValue = button.getAttribute('data-value');
                        selectedVariationInput.value = variationValue;

                        // Update the hidden input value for price
                        selectedPriceInput.value = price;

                        // Update the hidden input value for variation_id
                        const variationId = button.getAttribute('data-id'); // Get the data-id
                        selectedVariationIdInput.value = variationId; // Set it in the hidden input
                    });
                });
            });




            document.querySelector('#addToCartBtn').addEventListener('click', function() {
                // Retrieve product ID securely from a hidden input or directly from PHP
                const product_id = <?php echo isset($product_id) ? json_encode($product_id) : 'null'; ?>;

                // Debug to verify
                console.log("Product ID:", product_id);

                if (!product_id) {
                    console.error("Product ID is not defined!");
                    Toastify({
                        text: "Product ID is missing.",
                        duration: 3000,
                        close: true,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#FF0000',
                    }).showToast();
                    return;
                }

                // Retrieve quantity (default to 1 if invalid or empty)
                var quantity = parseInt(document.getElementById('quantity').value) || 1;
                var selectedPrice = document.getElementById('selectedPrice').value || '-';

                var product_sellingprice = document.getElementById('product_sellingprice').value;

                var selectedVariation = document.getElementById('selectedVariation').value;
                var selectedVariationId = document.getElementById('selectedVariationId').value || null;
                var selectedColorId = document.getElementById('selectedColorId').value || null;

                var product_name = document.getElementById('product_name').value;
                var product_image = document.getElementById('product_image').value;

                // Get the button and store original text
                var button = document.getElementById('addToCartBtn');
                var originalText = button.textContent;

                // Update button state
                button.textContent = 'Adding to Cart...';
                button.disabled = true;

                // Check if the user is logged in
                var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

                // Prepare data for AJAX
                var cartData = {
                    product_id: product_id,
                    price: selectedPrice || null,
                    value: selectedVariation,
                    variation_id: selectedVariationId, // Add variation_id to cartData
                    cart_quantity: quantity,
                    product_name: product_name,
                    product_image: product_image,
                    product_sellingprice: product_sellingprice,
                    variation_color_id: selectedColorId,

                };

                if (isLoggedIn) {
                    // User is logged in, perform AJAX call to add to the server cart
                    $.ajax({
                        url: '/blutmedical/controllers/users/add_cart_process.php',
                        type: 'POST',
                        data: cartData,
                        dataType: 'json',
                        success: function(response) {
                            Toastify({
                                text: response.message || (response.success ? 'Added to cart successfully!' : 'Failed to add to cart.'),
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'right',
                                backgroundColor: response.success ? '#4CAF50' : '#FF0000',
                            }).showToast();

                            // Update the cart badge
                            updateCartBadge();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            Toastify({
                                text: 'An unexpected error occurred while adding to cart.',
                                duration: 3000,
                                close: true,
                                gravity: 'top',
                                position: 'right',
                                backgroundColor: '#FF0000',
                            }).showToast();
                        },
                        complete: function() {
                            button.textContent = originalText;
                            button.disabled = false;
                        },
                    });
                } else {
                    // User is not logged in, update the guest cart in localStorage
                    var cart = JSON.parse(localStorage.getItem('guestCart')) || [];
                    var existingProductIndex = cart.findIndex(
                        (item) =>
                        item.product_id === product_id &&
                        item.variation_id === selectedVariation &&
                        item.variation_color_id === selectedVariationId // Check color variation too
                    );

                    if (existingProductIndex !== -1) {
                        // Update quantity if the product already exists
                        cart[existingProductIndex].cart_quantity += quantity;
                    } else {
                        // Add new product
                        var cartData = {
                            product_id: product_id,
                            cart_quantity: quantity,
                            variation_id: selectedVariation || null, // Ensure it handles cases with no size
                            variation_color_id: selectedColorVariation || null, // Ensure it handles cases with no color
                        };

                        cart.push(cartData);
                    }

                    localStorage.setItem('guestCart', JSON.stringify(cart));

                    Toastify({
                        text: 'Added to cart as guest.',
                        duration: 3000,
                        close: true,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#4CAF50',
                    }).showToast();

                    // Update the cart badge
                    updateCartBadge();

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

            mainImage.addEventListener('mousemove', function(e) {
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

            mainImage.addEventListener('mouseleave', function() {
                zoomedImage.style.display = 'none';
            });
        </script>

<?php
    }
}
?>