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
          <div class="row">
            <div class="col-md-6">
              <img src="<?php echo $image_url; ?>" class="img-fluid" style="border-radius: 10px; object-fit: cover;">
            </div>
            <div class="col-md-6">
              <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

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
              <p class="text-muted">₱<span id="productPrice"><?php echo number_format($initialPrice, 2); ?></span></p>
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
              <?php } else { ?>
                <!-- No variations available -->
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

              <button class="btn btn-primary btn-lg mt-4">Add to Cart</button>
            </div>
          </div>
        </div>
      </div>

      <script>
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
      </script>



      <?php include './includes/footer.php'; ?>

      <script>
        // JavaScript for Quantity Adjustment
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
      </script>

    </body>

    </html>

    <?php
  }
}
?>

<style>
  .product-section .container {
    background: rgb(255, 255, 255);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 2rem;
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

  /* Optional: Additional style for the form group to ensure buttons appear inline */
  form {
    display: flex;
    gap: 10px;
  }
</style>

<!-- <script>
  document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.variation-toggle');
    const hiddenInput = document.getElementById('selectedVariation');

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        // Untoggle all buttons
        buttons.forEach(function (otherButton) {
          otherButton.classList.remove('active');
          otherButton.setAttribute('aria-pressed', 'false');
        });

        // Toggle the clicked button
        this.classList.add('active');
        this.setAttribute('aria-pressed', 'true');

        // Set the selected value to the hidden input
        hiddenInput.value = this.getAttribute('data-value');
      });
    });
  });


</script> -->