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
  <title>Furni Free Bootstrap 5 Template for Furniture and Interior Design Websites by Untree.co </title>
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
            <h1>About Us</h1>
            <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
              velit imperdiet dolor tempor tristique.</p>
            <p><a href="" class="btn btn-secondary me-2">Shop Now</a><a href="#"
                class="btn btn-white-outline">Explore</a></p>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="hero-img-wrap">
            <img src="images/couch.png" class="img-fluid">
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
          <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet
            dolor tempor tristique.</p>

          <div class="row my-5">
            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/truck.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Fast &amp; Free Shipping</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/bag.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Easy to Shop</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/support.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>24/7 Support</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
              </div>
            </div>

            <div class="col-6 col-md-6">
              <div class="feature">
                <div class="icon">
                  <img src="images/return.svg" alt="Image" class="imf-fluid">
                </div>
                <h3>Hassle Free Returns</h3>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
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

  <div class="we-help-section">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-7 mb-5 mb-lg-0">
          <div class="imgs-grid">
            <div class="grid grid-1"><img src="images/img-grid-1.jpg" alt="Untree.co"></div>
            <div class="grid grid-2"><img src="images/img-grid-2.jpg" alt="Untree.co"></div>
            <div class="grid grid-3"><img src="images/img-grid-3.jpg" alt="Untree.co"></div>
          </div>
        </div>
        <div class="col-lg-5 ps-lg-5">
          <h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
          <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac
            aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
            tristique senectus et netus et malesuada</p>

          <ul class="list-unstyled custom-list my-4">
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
            <li>Donec vitae odio quis nisl dapibus malesuada</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php

  include './includes/footer.php';

  ?>

</body>

</html>