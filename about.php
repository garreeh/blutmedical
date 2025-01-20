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
  <title>Blüt Medical | About us</title>
</head>

<body>

  <?php

  include './includes/navigation.php';
  include './connections/connections.php';

  ?>

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>About us</h1>
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



  <!-- Start Why Choose Us Section -->
  <div class="why-choose-section">
    <div class="container">
      <div class="row justify-content-between align-items-center">
        <div class="col-lg-6">
          <h2 class="section-title">Why Choose Us</h2>
          <p>We are dedicated to providing top-notch service and exceptional products. Our commitment ensures a seamless
            and enjoyable experience tailored to your needs.</p>

          <div class="row my-5">
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/truck.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Fast &amp; Free Shipping</h3>
                <p>Experience fast and reliable shipping at no extra cost. Your orders are in safe hands.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/bag.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Easy to Shop</h3>
                <p>Enjoy a seamless shopping experience. Our platform is designed with simplicity in mind.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/support.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>24/7 Support</h3>
                <p>We're here for you round the clock. Get assistance whenever you need it.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/return.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Quality Assurance</h3>
                <p>We take pride in delivering only the best products to you. Satisfaction guaranteed.</p>
              </div>
            </div>

          </div>
        </div>

        <div class="col-lg-5">
          <div class="img-wrap">
            <img src="images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- End Why Choose Us Section -->

  <?php

  include './includes/footer.php';

  ?>

</body>

</html>