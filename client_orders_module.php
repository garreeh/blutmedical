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
            <h1>My Orders</h1>
          </div>
        </div>
        <div class="col-lg-7">

        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->



  <div class="untree_co-section before-footer-section">
    <div class="container">
      <div class="row mb-5">
        <form class="col-md-12" method="post" id="cart-form">
          <div class="site-blocks-table">
            <table class="table">
              <thead>
                <tr>
                  <th class="product-thumbnail">Image</th>
                  <th class="product-name">Product</th>
                  <th class="product-variation">Price</th>
                  <th class="product-price">Variation</th>
                  <th class="product-quantity">Quantity</th>
                  <th class="product-status">Status</th>

                  <th class="product-total">Total</th>
                  <th class="product-remove">Cancel</th>
                </tr>
              </thead>
              <tbody id="cart-items">
                <!-- Dynamic content will be loaded here -->
              </tbody>
            </table>
          </div>
        </form>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="row mb-5">
            <div class="col-md-6">
              <a class="btn btn-outline-black btn-sm btn-block" href="products.php">Continue Shopping</a>
            </div>
          </div>
        </div>
        <div class="col-md-6 pl-5">
          <div class="row justify-content-end">
            <div class="col-md-7">

              <div class="row">
                <div class="col-md-12">
                  <?php include './modals/checkout_modal.php' ?>



                </div>
              </div>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  function updateCart() {
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;
    if (isLoggedIn) {

      $.ajax({
        url: '/blutmedical/controllers/users/fetch_order_process.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log('Cart Data:', response);

          var cartContent = '';
          var totalPrice = 0;

          if (response.success) {
            if (response.items.length > 0) {
              $.each(response.items, function(index, item) {
                var productPrice = parseFloat(item.product_sellingprice) || 0;
                var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
                var variationId = item.variation_id;
                var productId = item.product_id;
                var baseURL = "/blutmedical/";

                var variationPrice = item.variation_id ? parseFloat(item.price) : productPrice; // Check if variation_id exists
                var variationValue = item.value !== null ? item.value : '-';

                // Dynamically render each row
                cartContent += '<tr>';
                cartContent += '<td class="product-thumbnail"><img src="' + baseURL + item.product_image.replace(/^\.\.\//, baseURL + '') + '" alt="' + item.name + '" class="img-fluid"></td>';
                cartContent += '<td>' + item.product_name + '</td>';
                cartContent += '<td>₱ ' + variationPrice.toFixed(2) + '</td>'; // Display variation price if available, otherwise product price
                cartContent += '<td>' + variationValue + '</td>';

                cartContent += '<td>';
                cartContent += '<div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 80px;">';
                cartContent += '<input type="text" class="form-control text-center quantity-amount" value="' + cartQuantity + '" readonly>';
                cartContent += '</div>';
                cartContent += '</td>';
                cartContent += '<td style="background-color: #fff9c4;"> <strong>' + item.cart_status + '<strong></td>'; // Display total price with variation if available

                cartContent += '<td>₱ ' + (variationPrice * cartQuantity).toFixed(2) + '</td>'; // Display total price with variation if available

                // Conditionally render delete or "Already Shipped"
                if (item.cart_status === 'Shipped') {
                  cartContent += '<td><span class="text-muted">Already Shipped</span></td>';
                } else {
                  cartContent += '<td><a href="#" class="btn btn-black btn-sm remove-item" data-product-id="' + productId + '" data-variation-id="' + variationId + '">X</a></td>';
                }

                cartContent += '</tr>';

                totalPrice += variationPrice * cartQuantity; // Use variationPrice in total calculation if it exists
              });

              $('#cart-items').html(cartContent);
              $('#cart-subtotal').text('₱ ' + totalPrice.toFixed(2));
              $('#cart-total').text('₱ ' + (totalPrice + 35).toFixed(2));

              if (response.items.length > 0) {
                $('#checkout-button').show();
              } else {
                $('#checkout-button').hide();
              }
            }
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }
  }


  // Delete cart item (handles both database and localStorage-based carts)
  function deleteCartItem(productId, variationId) {
    // Check if the user is logged in
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      // For logged-in users, delete the cart item from the database
      $.ajax({
        url: '/blutmedical/controllers/users/delete_order_process.php',
        method: 'POST',
        data: {
          product_id: productId,
          variation_id: variationId
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Toastify({
              text: "Order has been cancelled.",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
              duration: 3000
            }).showToast();

            // Refresh the cart after successful deletion
            updateCart();
            updateCartBadge();
          } else {
            Toastify({
              text: response.message || "Failed to remove item.",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
              duration: 3000
            }).showToast();
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }
  }

  var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

  if (isLoggedIn) {
    // Event listener for the remove button
    $(document).on('click', '.remove-item', function(event) {
      event.preventDefault(); // Prevent default link behavior
      var productId = $(this).data('product-id');
      var variationId = $(this).data('variation-id'); // Include variation ID
      deleteCartItem(productId, variationId);
      console.log(variationId)
      console.log(productId)
    });

  }

  // Call the updateCart function to render the cart on page load
  $(document).ready(function() {
    updateCart();
  });
</script>