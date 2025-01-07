<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// if (isset($_SESSION['user_id'])) {
//   if (!isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == "1") {
//       // If the user is an admin, redirect to the admin dashboard
//       header("Location: /blutmedical/views/admin/dashboard.php.php");
//   } else {
//       // If the user is not an admin, redirect to the user dashboard
//       header("Location: /blutmedical/index.php");
//   }
//   exit();
// }

?>

<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo/blut_logo.png" alt="Blut Logo" style="height: 8rem; width: auto;">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
      aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsFurni">
      <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
        <li>
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li><a class="nav-link" href="products.php">Shop</a></li>
        <li><a class="nav-link" href="about.php">About us</a></li>
        <li><a class="nav-link" href="contact.php">Contact us</a></li>
      </ul>

      <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa-solid fa-user"></i>
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="/online_ordering/controllers/logout_process.php">Sign-out</a></li>
            </ul>
          <?php else: ?>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="views/login.php">Log In</a></li>
              <li><a class="dropdown-item" href="views/register.php">Register</a></li>
            </ul>
          <?php endif; ?>
        </li>

        <!-- Cart Icon -->
        <li>
          <a class="nav-link position-relative" href="cart.html">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cart-badge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              3 <!-- Replace 3 with your cart count dynamically -->
            </span>
          </a>
        </li>

      </ul>


    </div>
  </div>

</nav>
<!-- End Header/Navigation -->