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
          <h1>Received Orders</h1>
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

            </div>

          </div>
        </div>
        <!-- END CONTENT -->
      </div>
      <!-- END SIDEBAR & CONTENT -->

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
    function updateCart() {
      $.ajax({
        url: '/blutmedical/controllers/users/fetch_received_orders_process.php',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            var cartContent = '';
            var totalPrice = response.total_price;

            $('#cart-items').empty();

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

              // Check the cart status and modify the cancel button accordingly
              if (item.cart_status === 'Delivered') {
                cartContent += '<td class="del-goods-col">Delivered</td>'; // Display status
              } else {
                cartContent += '<td class="del-goods-col"><button class="btn btn-primary cancel-order-btn" data-product-id="' + item.product_id + '" data-cart-quantity="' + cartQuantity + '" data-order-id="' + item.cart_id + '">Cancel</button></td>';
              }

              cartContent += '</tr>';
            });

            $('#cart-items').html(cartContent);
            $('#cart-subtotal').text('₱ ' + response.total_price.toFixed(2));
            $('#cart-total').text('₱ ' + (response.total_price + 35).toFixed(2));

            // Attach event handler to Cancel buttons
            $('.cancel-order-btn').on('click', function () {
              var productId = $(this).data('product-id');
              var cartQuantity = $(this).data('cart-quantity');
              var orderId = $(this).data('order-id');

              cancelOrder(productId, cartQuantity, orderId);
            });
          } else {
            console.error('Failed to fetch cart data:', response.message);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    function cancelOrder(productId, cartQuantity, orderId) {
      $.ajax({
        url: '/blutmedical/controllers/users/cancel_order_process.php',
        method: 'POST',
        data: {
          product_id: productId,
          cart_quantity: cartQuantity,
          cart_id: orderId
        },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "green"
            }).showToast();
            // Reload the cart after successful cancellation
            updateCart();
          } else {
            Toastify({
              text: response.message,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "right",
              backgroundColor: "red"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', error);
        }
      });
    }

    // Initial cart update
    updateCart();
  });
</script>