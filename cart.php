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
            <h1>Cart</h1>
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
                  <th class="product-total">Total</th>
                  <th class="product-remove">Remove</th>
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
                <div class="col-md-12 text-right border-bottom mb-5">
                  <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <span class="text-black">Subtotal</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="cart-subtotal">$0.00</strong>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col-md-6">
                  <span class="text-black">Total</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="cart-total">$0.00</strong>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <?php include './modals/checkout_modal.php' ?>


                  <button class="btn btn-black btn-lg py-3 btn-block" data-toggle="modal" data-target="#checkoutModal"
                    id="checkout-button" style="display:none;">Proceed To Checkout</button>
                  <!-- <button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#checkoutModal" id="checkout-button">Checkout <i class="fa fa-check"></i></button> -->
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
        url: '/blutmedical/controllers/users/fetch_cart_process.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log('Cart Data:', response);

          var cartContent = '';
          var totalPrice = 0;

          if (response.success) {
            cartItems = response.items;
            if (response.items.length > 0) {
              $.each(response.items, function(index, item) {
                var productPrice = parseFloat(item.product_sellingprice) || 0;
                var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
                var variationId = item.variation_id;
                var productId = item.product_id;
                var variationColorId = item.variation_color_id;

                var baseURL = "/blutmedical/";

                var variationPrice = item.variation_id ? parseFloat(item.price) : productPrice; // Check if variation_id exists
                var variationValue = item.value !== null ? item.value : '-';

                // Dynamically render each row
                cartContent += '<tr>';
                cartContent += '<td class="product-thumbnail"><img src="' + baseURL + item.product_image.replace(/^\.\.\//, baseURL + '') + '" alt="' + item.name + '" class="img-fluid"></td>';
                cartContent += '<td>' + item.product_name + '</td>';
                cartContent += '<td>$ ' + variationPrice.toFixed(2) + '</td>'; // Display variation price if available, otherwise product price
                cartContent += '<td>' + variationValue + '</td>';

                cartContent += '<td>';
                cartContent += '<div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">';
                cartContent += '<div class="input-group-prepend">';
                cartContent += '<button class="btn btn-outline-black decrease" type="button">&minus;</button>';
                cartContent += '</div>';
                cartContent += '<input type="text" class="form-control text-center quantity-amount" value="' + cartQuantity + '" readonly>';
                cartContent += '<div class="input-group-append">';
                cartContent += '<button class="btn btn-outline-black increase" type="button">&plus;</button>';
                cartContent += '</div>';
                cartContent += '</div>';
                cartContent += '</td>';
                cartContent += '<td>$ ' + (variationPrice * cartQuantity).toFixed(2) + '</td>'; // Display total price with variation if available
                cartContent += '<td><a href="#" class="btn btn-black btn-sm remove-item" data-product-id="' + productId + '" data-variation-id="' + variationId + '" data-variation-color-id="' + variationColorId + '">X</a></td>';


                cartContent += '</tr>';

                totalPrice += variationPrice * cartQuantity; // Use variationPrice in total calculation if it exists
              });

              $('#cart-items').html(cartContent);
              $('#cart-subtotal').text('$ ' + totalPrice.toFixed(2));
              $('#cart-total').text('$ ' + (totalPrice).toFixed(2));

              if (response.items.length > 0) {
                $('#checkout-button').show();
              } else {
                $('#checkout-button').hide();
              }
            } else {
              cartContent = '<tr><td colspan="6" class="text-center">Cart is empty</td></tr>';
              $('#cart-items').html(cartContent);
              $('#cart-subtotal').text('$ 0.00');
              $('#cart-total').text('$ 0.00');
              $('#checkout-button').hide();
            }

          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    } else {
      // Retrieve and parse the guest cart data from localStorage
      var guestCart = JSON.parse(localStorage.getItem('guestCart')) || [];

      if (guestCart.length > 0) {
        var cartContent = '';
        var totalPrice = 0;

        $.each(guestCart, function(index, item) {

          var productId = item.product_id;
          var variationId = item.variation_id;
          var variationColorId = item.variation_color_id;


          var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
          var variationPrice = item.price !== '-' ? parseFloat(item.price) : parseFloat(item.product_sellingprice); // Check if price is not '-', otherwise use product_sellingprice
          var variationValue = item.value !== null ? item.value : '-';

          var baseURL = "/blutmedical/";

          var cartContentRow = '<tr>';

          cartContentRow += '<td class="product-thumbnail"><img src="' + baseURL + item.product_image.replace(/^\.\.\//, baseURL + '') + '" alt="' + item.product_image + '" class="img-fluid"></td>';
          cartContentRow += '<td>' + item.product_name + '</td>';
          cartContentRow += '<td>$ ' + variationPrice.toFixed(2) + '</td>';
          cartContentRow += '<td>' + (item.value !== null ? item.value : '-') + '</td>';
          cartContentRow += '<td>';
          cartContentRow += '<div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">';
          cartContentRow += '<div class="input-group-prepend">';
          cartContentRow += '<button class="btn btn-outline-black decrease" type="button">&minus;</button>';
          cartContentRow += '</div>';
          cartContentRow += '<input type="text" class="form-control text-center quantity-amount" value="' + cartQuantity + '" readonly>';
          cartContentRow += '<div class="input-group-append">';
          cartContentRow += '<button class="btn btn-outline-black increase" type="button">&plus;</button>';
          cartContentRow += '</div>';
          cartContentRow += '</div>';
          cartContentRow += '</td>';
          cartContentRow += '<td>$ ' + (variationPrice * cartQuantity).toFixed(2) + '</td>';
          cartContentRow += '<td><a href="#" class="btn btn-black btn-sm remove-item" data-product-id="' + productId + '" data-variation-id="' + variationId + '" data-variation-color-id="' + variationColorId + '">X</a></td>';
          cartContentRow += '</tr>';

          cartContent += cartContentRow;

          totalPrice += variationPrice * cartQuantity;
        });

        $('#cart-items').html(cartContent);
        $('#cart-subtotal').text('$ ' + totalPrice.toFixed(2));
        $('#cart-total').text('$ ' + (totalPrice).toFixed(2));

        if (guestCart.length > 0) {
          $('#checkout-button').show();
        } else {
          $('#checkout-button').hide();
        }
      } else {
        $('#cart-items').html('<tr><td colspan="6" class="text-center">Cart is empty</td></tr>');
        $('#cart-subtotal').text('$ 0.00');
        $('#cart-total').text('$ 0.00');
        $('#checkout-button').hide();
      }

    }
  }



  // Delete cart item (handles both database and localStorage-based carts)
  function deleteCartItem(productId, variationId, variationColorId) {
    // Check if the user is logged in
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      // For logged-in users, delete the cart item from the database
      $.ajax({
        url: '/blutmedical/controllers/users/delete_cart_process.php',
        method: 'POST',
        data: {
          product_id: productId,
          variation_id: variationId,
          variation_color_id: variationColorId

        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Toastify({
              text: "Item removed from cart.",
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
    } else {
      $(document).on('click', '.remove-item', function(event) {
        event.preventDefault(); // Prevent default link behavior
        var productId = $(this).data('product-id');
        var variationId = $(this).data('variation-id'); // Include variation ID
        var variationColorId = $(this).data('variation-color-id'); // Include variation ID


        // Retrieve the current guestCart from localStorage
        var cart = JSON.parse(localStorage.getItem('guestCart')) || [];

        // Filter out the item based on both productId and variationId
        var updatedCart = cart.filter(item => !(item.product_id == productId && item.variation_id == variationId && item.variation_color_id == variationColorId));

        // Update localStorage with the filtered cart
        localStorage.setItem('guestCart', JSON.stringify(updatedCart));

        // Notify user of the removal
        Toastify({
          text: "Item removed from cart.",
          backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
          duration: 3000
        }).showToast();

        // Refresh the cart for guest users
        updateCart();
        updateCartBadge();
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
      var variationColorId = $(this).data('variation-color-id'); // Include variation ID

      deleteCartItem(productId, variationId, variationColorId);
      console.log(variationId)
      console.log(productId)
      console.log(variationColorId)

    });

  } else {
    $(document).on('click', '.remove-item', function(event) {
      event.preventDefault(); // Prevent default link behavior
      var productId = $(this).data('product-id');
      var variationId = $(this).data('variation-id'); // Include variation ID
      var variationColorId = $(this).data('variation-color-id');

      // Retrieve the current guestCart from localStorage
      var cart = JSON.parse(localStorage.getItem('guestCart')) || [];

      // Filter out the item based on both productId and variationId
      var updatedCart = cart.filter(item => !(item.product_id == productId && item.variation_id == variationId && item.variation_color_id == variationColorId));

      // Update localStorage with the filtered cart
      localStorage.setItem('guestCart', JSON.stringify(updatedCart));

      // Notify user of the removal
      Toastify({
        text: "Item removed from cart.",
        backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
        duration: 3000
      }).showToast();

      // Refresh the cart for guest users
      updateCart();
      updateCartBadge();
    });
  }

  function updateCartQuantity(productId, variationId, variationColorId, action) {
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      // For logged-in users, update the cart quantity in the database
      $.ajax({
        url: '/blutmedical/controllers/users/update_cart_quantity_process.php',
        method: 'POST',
        data: {
          product_id: productId,
          variation_id: variationId,
          variation_color_id: variationColorId,

          action: action
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Toastify({
              text: "Cart quantity updated.",
              backgroundColor: '#4CAF50',
              duration: 3000
            }).showToast();

            // Refresh the cart after successful update
            updateCart();
          } else {
            Toastify({
              text: response.message || "Failed to update quantity.",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
              duration: 3000
            }).showToast();
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    } else {
      // For guest users, update localStorage
      var cart = JSON.parse(localStorage.getItem('guestCart')) || [];
      console.log(cart);

      $.each(cart, function(index, item) {
        if (item.product_id == productId && item.variation_id == variationId && item.variation_color_id == variationColorId) {

          if (action === 'increase') {
            item.cart_quantity++; // Increment the cart quantity
          } else if (action === 'decrease') {
            item.cart_quantity = Math.max(item.cart_quantity - 1, 1); // Prevent decrementing below 1
          }

        }
      });

      console.log(cart);

      // Update localStorage with the updated cart
      localStorage.setItem('guestCart', JSON.stringify(cart));

      // Refresh the cart for guest users
      updateCart();

    }
  }

  // Event listeners for increase and decrease buttons
  $(document).on('click', '.increase', function(event) {
    var productId = $(this).closest('tr').find('.remove-item').data('product-id');
    var variationId = $(this).closest('tr').find('.remove-item').data('variation-id');
    var variationColorId = $(this).closest('tr').find('.remove-item').data('variation-color-id');

    updateCartQuantity(productId, variationId, variationColorId, 'increase');
  });

  $(document).on('click', '.decrease', function(event) {
    var productId = $(this).closest('tr').find('.remove-item').data('product-id');
    var variationId = $(this).closest('tr').find('.remove-item').data('variation-id');
    var variationColorId = $(this).closest('tr').find('.remove-item').data('variation-color-id');

    updateCartQuantity(productId, variationId, variationColorId, 'decrease');
  });

  // Call the updateCart function to render the cart on page load
  $(document).ready(function() {
    updateCart();
  });

  $('#checkoutModal').on('show.bs.modal', function() {
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      // Fetch cart items from the server
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/fetch_cart_last_process.php',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            var cartItemsHtml = '';
            var totalPrice = 0;

            // Loop through cart items and create table rows
            response.cartItems.forEach(function(item) {
              var productPrice = parseFloat(item.product_sellingprice) || 0;
              var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
              var variationId = item.variation_id;
              var productId = item.product_id;

              var variationPrice = item.variation_id === 0 ? productPrice : (item.variation_id ? parseFloat(item.price) : productPrice); // Check if variation_id exists

              var variationValue = item.value !== null ? item.value : '-';

              var itemTotal = variationPrice * cartQuantity;

              cartItemsHtml += '<tr>';
              cartItemsHtml += '<td>' + item.product_name + '</td>';
              cartItemsHtml += '<td>' + cartQuantity + '</td>';
              cartItemsHtml += '<td>$ ' + variationPrice.toFixed(2) + '</td>';
              cartItemsHtml += '<td>$ ' + itemTotal.toFixed(2) + '</td>';
              cartItemsHtml += '</tr>';

              totalPrice += itemTotal;
            });

            // Update the cart content and total price in the modal
            $('#order-summary').html(cartItemsHtml);
            $('#total-amount').text('$ ' + totalPrice.toFixed(2));
          } else {
            alert('Error fetching cart items');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error fetching cart:', error);
          alert('An error occurred while fetching the cart.');
        }
      });
    } else {
      // Fetch cart items from localStorage
      var cart = JSON.parse(localStorage.getItem('guestCart')) || [];
      var cartItemsHtml = '';
      var totalPrice = 0;

      // Loop through localStorage cart items and create table rows
      cart.forEach(function(item) {
        var productPrice = parseFloat(item.product_sellingprice) || 0;
        var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
        var variationId = item.variation_id;
        var productId = item.product_id;
        var baseURL = "/blutmedical/";

        var variationPrice = item.variation_id === '-' ? productPrice : (item.variation_id ? parseFloat(item.price) : productPrice); // Check if variation_id exists

        var variationValue = item.value !== null ? item.value : '-';

        var itemTotal = cartQuantity * variationPrice; // Add shipping cost

        cartItemsHtml += '<tr>';
        cartItemsHtml += '<td>' + item.product_name + '</td>';
        cartItemsHtml += '<td>' + cartQuantity + '</td>';
        cartItemsHtml += '<td>$ ' + variationPrice.toFixed(2) + '</td>';
        cartItemsHtml += '<td>$ ' + itemTotal.toFixed(2) + '</td>';
        cartItemsHtml += '</tr>';

        totalPrice += itemTotal;
      });

      // Update the cart content and total price in the modal
      $('#order-summary').html(cartItemsHtml);
      $('#total-amount').text('$ ' + totalPrice.toFixed(2));
    }
  });
</script>