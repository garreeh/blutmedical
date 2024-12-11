<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if the user is not logged in
  header("Location: /blutmedical/views/login.php");
  exit();
}

?>


<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->

<head>
  <meta charset="utf-8">
  <title>Shopping cart | Metronic Shop UI</title>

  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <meta content="Metronic Shop UI description" name="description">
  <meta content="Metronic Shop UI keywords" name="keywords">
  <meta content="keenthemes" name="author">

  <meta property="og:site_name" content="-CUSTOMER VALUE-">
  <meta property="og:title" content="-CUSTOMER VALUE-">
  <meta property="og:description" content="-CUSTOMER VALUE-">
  <meta property="og:type" content="website">
  <meta property="og:image" content="-CUSTOMER VALUE-">
  <meta property="og:url" content="-CUSTOMER VALUE-">

  <link rel="shortcut icon" href="favicon.ico">

  <!-- Fonts START -->
  <link
    href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all"
    rel="stylesheet" type="text/css">
  <!-- Fonts END -->

  <!-- Global styles START -->
  <link href="./../../assets/user/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="./../../assets/user/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END -->

  <!-- Page level plugin styles START -->
  <link href="./../../assets/user/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">

  <link href="./../../assets/user/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
  <link href="./../../assets/user/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
  <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">
  <!-- for slider-range -->
  <link href="./../../assets/user/plugins/rateit/src/rateit.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="./../../assets/user/pages/css/components.css" rel="stylesheet">
  <link href="./../../assets/user/corporate/css/style.css" rel="stylesheet">
  <link href="./../../assets/user/pages/css/style-shop.css" rel="stylesheet" type="text/css">
  <link href="./../../assets/user/corporate/css/style-responsive.css" rel="stylesheet">
  <link href="./../../assets/user/corporate/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="./../../assets/user/corporate/css/custom.css" rel="stylesheet">
  <!-- Theme styles END -->
</head>
<!-- Head END -->

<!-- Body BEGIN -->

<body class="ecommerce">
  <?php include './../../includes/navigation.php' ?>



  <div class="main">
    <div class="container">
      <!-- BEGIN SIDEBAR & CONTENT -->
      <div class="row margin-bottom-40">
        <!-- BEGIN CONTENT -->
        <div class="col-md-12 col-sm-12">
          <h1>Shopping cart</h1>
          <div class="goods-page">
            <div class="goods-data clearfix">
              <div class="table-wrapper-responsive">
                <table summary="Shopping cart">
                  <thead>
                    <tr>
                      <th class="goods-page-image">Image</th>
                      <th class="goods-page-description">Description</th>
                      <th class="goods-page-ref-no">SKU</th>
                      <th class="goods-page-quantity">Quantity</th>
                      <th class="goods-page-price">Unit price</th>
                      <th class="goods-page-total" colspan="2">Total</th>
                    </tr>
                  </thead>
                  <tbody id="cart-items">
                    <!-- Cart items will be inserted here by JavaScript -->
                  </tbody>
                </table>
              </div>

              <div class="shopping-total">
                <ul>
                  <li>
                    <em>Sub total</em>
                    <strong class="price" id="cart-subtotal"><span>₱ </span>0.00</strong>
                  </li>
                  <li>
                    <em>Delivery cost</em>
                    <strong class="price" id="cart-shipping"><span>₱ </span>35.00</strong>
                  </li>
                  <li class="shopping-total-price">
                    <em>Total</em>
                    <strong class="price" id="cart-total"><span>₱ </span>0.00</strong>
                  </li>
                </ul>
              </div>
            </div>
            <a href="/blutmedical/index.php" class="btn btn-default" type="submit">Continue shopping <i
                class="fa fa-shopping-cart"></i></a>
            <?php include './../../modals/checkout_modal.php' ?>
            <!-- Update this button to trigger the modal -->
            <button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#checkoutModal"
              id="checkout-button">Checkout <i class="fa fa-check"></i></button>


          </div>
        </div>
        <!-- END CONTENT -->
      </div>
      <!-- END SIDEBAR & CONTENT -->

      <h1>Recommendation for you</h1>
      <div class="col-md-9 col-sm-8 col-xs-7 special-product">
        <?php
        // Fetch random 3 products from the product table
        $productQuery = "SELECT * FROM product ORDER BY RAND() LIMIT 3";
        $productResult = $conn->query($productQuery);

        if ($productResult->num_rows > 0) {
          while ($row = $productResult->fetch_assoc()) {
            $product_image = basename($row['product_image']);
            $image_url = './../../uploads/' . $product_image;
            ?>
            <div class="col-xs-12 col-sm-6 col-md-4" style="margin-bottom: 2rem;">
              <div class="product-item"
                style="display: flex; flex-direction: column; padding-bottom: 1rem; border: 1px solid #ddd; border-radius: 10px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2); transition: transform 0.3s; overflow: hidden;">
                <div class="pi-img-wrapper" style="text-align: center; position: relative;">
                  <img src="<?php echo $image_url; ?>" class="img-responsive"
                    alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                    style="width: 100%; height: auto; border-radius: 8px; transition: transform 0.3s;">
                </div>
                <h3 style="text-align: center; color: #333; font-size: 1.8rem; font-weight: bold;">
                  <?php echo htmlspecialchars($row['product_name']); ?>
                </h3>
                <div class="pi-price" style="text-align: center; color: #2E8B57; font-size: 1.5rem;">
                  ₱<?php echo number_format($row['product_sellingprice'], 2); ?>
                </div>

                <div class="pi-price" style="text-align: center; color: #2E8B57; font-size: 1.5rem;">
                  Stocks: <?php echo $row['product_stocks']; ?>
                </div>
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
                <br>
                <br>
                <a href="javascript:void(0)" class="btn btn-default add-to-cart add2cart"
                  data-product-id="<?php echo $row['product_id']; ?>"
                  style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); width: 90%; text-align: center; padding: 0.5rem 0; background-color: #4682B4; color: #fff; font-weight: bold; border-radius: 5px; transition: background-color 0.3s;">
                  Add to Order
                </a>
              </div>
            </div>
            <?php
          }
        }
        ?>
      </div>


    </div>
  </div>

  <script src="./../../assets/user/plugins/jquery.min.js" type="text/javascript"></script>
  <script src="./../../assets/user/plugins/jquery-migrate.min.js" type="text/javascript"></script>
  <script src="./../../assets/user/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="./../../assets/user/corporate/scripts/back-to-top.js" type="text/javascript"></script>
  <script src="./../../assets/user/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <!-- END CORE PLUGINS -->

  <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
  <script src="./../../assets/user/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
  <!-- pop up -->
  <script src="./../../assets/user/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
  <!-- Quantity -->
  <script src="./../../assets/user/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
  <script src="./../../assets/user/plugins/rateit/src/jquery.rateit.js" type="text/javascript"></script>

  <script src="./../../assets/user/corporate/scripts/layout.js" type="text/javascript"></script>

  <!-- Add Toastify CSS and JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


  <script type="text/javascript">
    jQuery(document).ready(function () {
      Layout.init();
      Layout.initOWL();
      Layout.initTwitter();
      Layout.initImageZoom();
      Layout.initTouchspin();
      Layout.initUniform();
      Layout.initSliderRange();
    });
  </script>
  <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>

<script type="text/javascript">
  $(document).ready(function () {

    // Function to update the cart content
    function updateCart() {
      $.ajax({
        url: '/blutmedical/controllers/users/fetch_cart_process.php',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          console.log('Cart Data:', response);

          if (response.success) {
            var cartContent = '';
            var totalPrice = response.total_price;

            // Clear the existing cart items
            $('#cart-items').empty();
            if (response.items.length === 0) {
              cartContent = '<tr><td colspan="7" class="text-center">Cart is empty</td></tr>';
            } else {
              // Iterate over cart items and generate HTML content
              $.each(response.items, function (index, item) {
                var productPrice = parseFloat(item.product_sellingprice) || 0;
                var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
                var baseURL = "./../uploads/";

                cartContent += '<tr>';
                cartContent += '<td class="goods-page-image"><a href="javascript:;"><img src="' + baseURL + item.product_image + '" alt="' + item.product_name + '" style="width: 90px; height: 100px;" /></a></td>';
                cartContent += '<td class="goods-page-description"><h3><a href="javascript:;"></a></h3><p><strong>' + item.product_name + '</strong></p><em>' + item.product_description + '</em></td>';
                cartContent += '<td class="goods-page-ref-no">' + item.product_sku + '</td>';
                cartContent += '<td class="goods-page-quantity"><div class="product-quantity"><input type="text" value="' + cartQuantity + '" readonly class="form-control input-sm"></div></td>';
                cartContent += '<td class="goods-page-price"><strong><span>₱ </span>' + productPrice.toFixed(2) + '</strong></td>';
                cartContent += '<td class="goods-page-total"><strong><span>₱ </span>' + (productPrice * cartQuantity).toFixed(2) + '</strong></td>';
                cartContent += '<td class="del-goods-col"><a class="del-goods" href="javascript:;" data-product-id="' + item.product_id + '">&nbsp;</a></td>';
                cartContent += '</tr>';
              });
            }

            // Update the DOM with the new cart content
            $('#cart-items').html(cartContent);

            // Update the subtotal and total price
            $('#cart-subtotal').text('₱ ' + response.total_price.toFixed(2));
            $('#cart-total').text('₱ ' + (response.total_price + 35).toFixed(2));

            // Toggle the checkout button visibility based on cart contents
            if (response.items.length > 0) {
              $('#checkout-button').show();
            } else {
              $('#checkout-button').hide();
            }
          } else {
            console.error('Failed to fetch cart data:', response.message);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    // Delete cart item function
    function deleteCartItem(productId) {
      $.ajax({
        url: '/blutmedical/controllers/users/delete_cart_process.php',
        method: 'POST',
        data: {
          product_id: productId
        },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            Toastify({
              text: "Item removed from cart.",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
              duration: 3000
            }).showToast();

            // Refresh the cart after successful deletion
            updateCart();
          } else {
            Toastify({
              text: response.message || "Failed to remove item.",
              backgroundColor: "linear-gradient(to right, #FF5F6D, #FFC371)",
              duration: 3000
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    // Bind the delete button click event
    $(document).on('click', '.del-goods', function () {
      var productId = $(this).data('product-id');
      deleteCartItem(productId);
    });

    // Show/Hide proof of payment based on selected payment method
    $('input[name="paymentCategory"]').change(function () {
      var selectedPayment = $('input[name="paymentCategory"]:checked').val();
      if (selectedPayment === 'GCash') {
        $('#proof-of-payment-field').show();
        $('#proofOfPayment').prop('required', true); // Set required attribute
      } else {
        $('#proof-of-payment-field').hide();
        $('#proofOfPayment').prop('required', false); // Remove required attribute
      }
    });

    // Handle checkout form submission
    $('#submitCheckout').click(function (e) {
      e.preventDefault(); // Prevent default form submission

      var selectedPayment = $('input[name="paymentCategory"]:checked').val();
      var proofOfPayment = $('#proofOfPayment').val();

      // Validate payment method selection
      if (!selectedPayment) {

        return;
      }

      // If GCash is selected, validate proof of payment
      if (selectedPayment === 'GCash' && !proofOfPayment) {

        return;
      }

      // Serialize form data
      var formData = new FormData($('#checkoutForm')[0]);

      // Send the form data via AJAX
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/checkout_process.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (typeof response === 'string') {
            try {
              response = JSON.parse(response);
            } catch (e) {
              console.error("Response is not valid JSON:", response);
              Toastify({
                text: "Invalid response format.",
                duration: 3000,
                backgroundColor: "#dc3545" // Red for error
              }).showToast();
              return;
            }
          }

          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" // Green for success
            }).showToast();

            // Close modal and reset form
            $('#checkoutModal').modal('hide');
            $('.modal-backdrop').remove();
            $('#checkoutForm').trigger('reset');
            $('#proof-of-payment-field').hide();

            // Refresh the cart
            updateCart();
          } else {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)" // Red for error
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error);
          Toastify({
            text: "An error occurred while processing your request.",
            duration: 3000,
            backgroundColor: "#dc3545", // Red for error
            close: true
          }).showToast();
        }
      });
    });

    // Initial cart update when the page loads
    updateCart();
  });

  function filterProducts(categoryId) {
    document.querySelectorAll('.category-products').forEach(categoryProducts => {
      categoryProducts.style.display = categoryProducts.getAttribute('data-category-id') === categoryId || categoryId === '' ? 'block' : 'none';
    });
  }

  document.addEventListener('DOMContentLoaded', () => filterProducts(''));


  // Handle quantity increase and decrease buttons
  $(document).on('click', '.add-btn', function () {
    var quantityInput = $(this).siblings('.cart_quantity');
    var currentValue = parseInt(quantityInput.val());
    quantityInput.val(currentValue + 1);
  });

  $(document).on('click', '.minus-btn', function () {
    var quantityInput = $(this).siblings('.cart_quantity');
    var currentValue = parseInt(quantityInput.val());
    if (currentValue > 1) {
      quantityInput.val(currentValue - 1);
    }
  });

  $(document).ready(function () {
    $('#categorySelect').change(function () {
      var categoryId = $(this).val();
      fetchProducts(categoryId);
      // fetchSpecialProducts(categoryId);
    });

    // Function to fetch regular products based on selected category
    function fetchProducts(categoryId) {
      $.ajax({
        url: '/blutmedical/controllers/users/fetch_products_process.php',
        type: 'GET',
        data: {
          category_id: categoryId
        },
        success: function (data) {
          $('#allProducts .row').html(data);
        },
        error: function (xhr, status, error) {
          console.error("Error fetching products:", error);
        }
      });
    }

    // Add to Cart button click handler
    $('.add-to-cart').click(function () {
      var productId = $(this).data('product-id');
      var $button = $(this);
      var quantityInput = $button.closest('.product-item').find('.cart_quantity');
      var quantity = parseInt(quantityInput.val()) || 1; // Default to 1 if no valid quantity

      // Make AJAX call to add_cart_process.php
      $.ajax({
        url: '/blutmedical/controllers/users/add_cart_process.php',
        method: 'POST',
        data: {
          product_id: productId,
          cart_quantity: quantity
        },
        success: function (response) {
          try {
            var res = JSON.parse(response);
            if (res.success) {
              // Show success message using Toastify
              Toastify({
                text: res.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4CAF50", // Green background for success
              }).showToast();

              // Update the cart in real-time after adding the item
              updateCart(); // This calls the function to update the cart display
            } else {
              if (res.message === 'You are not logged in.') {
                // Show the modal instead of Toast
                $('#loginModal').modal('show');
              } else {
                // Show error message using Toastify
                Toastify({
                  text: res.message,
                  duration: 3000,
                  close: true,
                  gravity: "top",
                  position: "right",
                  backgroundColor: "#FF0000", // Red background for error
                }).showToast();
              }
            }
          } catch (e) {
            console.error("Invalid JSON response:", response);
            Toastify({
              text: "An unexpected error occurred.",
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "#FF0000", // Red background for error
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.log('Error: ' + error);
          Toastify({
            text: "An error occurred. Please try again.",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#FF0000", // Red background for error
          }).showToast();
        }
      });
    });

    // Function to update the cart in real-time
    function updateCart() {
      $.ajax({
        url: '/blutmedical/controllers/users/fetch_cart_process.php',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          console.log('Cart Data:', response);

          if (response.success) {
            var cartContent = '';
            var totalPrice = response.total_price;

            // Clear the existing cart items
            $('#cart-items').empty();
            if (response.items.length === 0) {
              cartContent = '<tr><td colspan="7" class="text-center">Cart is empty</td></tr>';
            } else {
              // Iterate over cart items and generate HTML content
              $.each(response.items, function (index, item) {
                var productPrice = parseFloat(item.product_sellingprice) || 0;
                var cartQuantity = parseInt(item.cart_quantity, 10) || 0;
                var baseURL = "./../uploads/";

                cartContent += '<tr>';
                cartContent += '<td class="goods-page-image"><a href="javascript:;"><img src="' + baseURL + item.product_image + '" alt="' + item.product_name + '" style="width: 90px; height: 100px;" /></a></td>';
                cartContent += '<td class="goods-page-description"><h3><a href="javascript:;"></a></h3><p><strong>' + item.product_name + '</strong></p><em>' + item.product_description + '</em></td>';
                cartContent += '<td class="goods-page-ref-no">' + item.product_sku + '</td>';
                cartContent += '<td class="goods-page-quantity"><div class="product-quantity"><input type="text" value="' + cartQuantity + '" readonly class="form-control input-sm"></div></td>';
                cartContent += '<td class="goods-page-price"><strong><span>₱ </span>' + productPrice.toFixed(2) + '</strong></td>';
                cartContent += '<td class="goods-page-total"><strong><span>₱ </span>' + (productPrice * cartQuantity).toFixed(2) + '</strong></td>';
                cartContent += '<td class="del-goods-col"><a class="del-goods" href="javascript:;" data-product-id="' + item.product_id + '">&nbsp;</a></td>';
                cartContent += '</tr>';
              });
            }

            // Update the DOM with the new cart content
            $('#cart-items').html(cartContent);

            // Update the subtotal and total price
            $('#cart-subtotal').text('₱ ' + response.total_price.toFixed(2));
            $('#cart-total').text('₱ ' + (response.total_price + 35).toFixed(2));

            // Toggle the checkout button visibility based on cart contents
            if (response.items.length > 0) {
              $('#checkout-button').show();
            } else {
              $('#checkout-button').hide();
            }
          } else {
            console.error('Failed to fetch cart data:', response.message);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    $('#checkoutModal').on('show.bs.modal', function () {
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/fetch_cart_last_process.php',
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            var cartItemsHtml = '';
            var totalPrice = 0;

            // Loop through cart items and create table rows
            response.cartItems.forEach(function (item) {
              cartItemsHtml += '<tr>';
              cartItemsHtml += '<td>' + item.product_name + '</td>';
              cartItemsHtml += '<td>' + item.cart_quantity + '</td>';
              cartItemsHtml += '<td>₱ ' + item.product_sellingprice.toFixed(2) + '</td>';
              cartItemsHtml += '<td>₱ ' + (item.cart_quantity * item.product_sellingprice).toFixed(2) + '</td>';
              cartItemsHtml += '</tr>';
              totalPrice += item.cart_quantity * item.product_sellingprice + 35;
            });

            // Update the cart content and total price in the modal
            $('#order-summary').html(cartItemsHtml);
            $('#total-amount').text('₱ ' + totalPrice.toFixed(2));
          } else {
            alert('Error fetching cart items');
          }
        },
        error: function (xhr, status, error) {
          console.error('Error fetching cart:', error);
          alert('An error occurred while fetching the cart.');
        }
      });
    });


  });
</script>