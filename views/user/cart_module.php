<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sterling | Cart</title>

  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <meta content="Metronic Shop UI description" name="description">
  <meta content="Metronic Shop UI keywords" name="keywords">
  <meta content="keenthemes" name="author">

  <meta property="og:site_name" content="-CUSTOMER VALUE-">
  <meta property="og:title" content="-CUSTOMER VALUE-">
  <meta property="og:description" content="-CUSTOMER VALUE-">
  <meta property="og:type" content="website">
  <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
  <meta property="og:url" content="-CUSTOMER VALUE-">

  <link rel="shortcut icon" href="favicon.ico">

  <!-- Fonts START -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">  
  <!-- Fonts END -->

  <!-- Global styles START -->          
  <link href="assets/user/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/user/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="assets/user/pages/css/animate.css" rel="stylesheet">
  <link href="assets/user/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <link href="assets/user/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="assets/user/pages/css/components.css" rel="stylesheet">
  <link href="assets/user/pages/css/style-shop.css" rel="stylesheet" type="text/css">
  <link href="assets/user/corporate/css/style.css" rel="stylesheet">
  <link href="assets/user/corporate/css/style-responsive.css" rel="stylesheet">
  <link href="assets/user/corporate/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="assets/user/corporate/css/custom.css" rel="stylesheet">
  <!-- Theme styles END -->
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="ecommerce">

    <!-- This is the Header and navigation -->
    <?php include './includes/navigation.php'?>

    <div class="main">
      <div class="container">
        <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SALE PRODUCT -->
          <div class="col-md-12 sale-product">
              <h2>Showcase Product!</h2>
              <div class="row">
                  <?php
                  include './connections/connections.php';

                  $query = "SELECT * FROM product LEFT JOIN category ON product.category_id = category.category_id";
                  $result = $conn->query($query);
                  
                  while($row = $result->fetch_assoc()) {
                    $product_image = basename($row['product_image']);
                    $image_url = './uploads/' . $product_image; // Construct the image URL
                    
                    ?>

                    <div class="col-xs-12 col-sm-6 col-md-3">
                      <div class="product-item">
                          <div class="pi-img-wrapper">
                          <img src="<?php echo $image_url; ?>" class="img-responsive" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: auto; height: 17rem;">
                              <div>
                                  <a href="<?php echo $image_url; ?>" class="btn btn-default fancybox-button">Zoom</a>
                              </div>
                          </div>
                          <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                          <h3>In Stock: <?php echo htmlspecialchars($row['product_stocks']); ?></h3>

                          <div class="pi-price">₱<?php echo number_format($row['product_sellingprice'], 2); ?></div>
                          <input type="hidden" class="product-id" value="<?php echo $row['product_id']; ?>" />
                          <a href="javascript:void(0)" class="btn btn-default add-to-cart add2cart" data-product-id="<?php echo $row['product_id']; ?>">Add to cart</a>

                      </div>
                    </div>

                  <?php } // End of the loop ?>
                
              </div>
          </div>
          <!-- END SALE PRODUCT -->
        </div>
        <!-- END SALE PRODUCT & NEW ARRIVALS -->


      </div>
    </div>

    <?php include './includes/footer.php'?>
    <?php include './modals/modal_fast_view.php'?>


    <script src="assets/user/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/user/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="assets/user/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="assets/user/corporate/scripts/back-to-top.js" type="text/javascript"></script>
    <script src="assets/user/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="assets/user/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
    <script src="assets/user/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->
    <script src='assets/user/plugins/zoom/jquery.zoom.min.js' type="text/javascript"></script><!-- product zoom -->
    <script src="assets/user/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script><!-- Quantity -->

    <script src="assets/user/corporate/scripts/layout.js" type="text/javascript"></script>
    <script src="assets/user/pages/scripts/bs-carousel.js" type="text/javascript"></script>

    <!-- Add Toastify CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initImageZoom();
            Layout.initTouchspin();
            Layout.initTwitter();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

