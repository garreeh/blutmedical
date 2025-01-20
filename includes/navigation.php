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
              <li></li>
              <li><a class="dropdown-item" href="/blutmedical/client_orders_module.php">My Orders</a></li>

              <li><a class="dropdown-item" href="/blutmedical/completed_orders_module.php">Completed Orders</a></li>
              <li><a class="dropdown-item" href="/blutmedical/controllers/logout_process.php">Sign-out</a></li>
            </ul>
          <?php else: ?>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="/blutmedical/views/login.php">Log In</a></li>
              <li><a class="dropdown-item" href="/blutmedical/views/register.php">Register</a></li>
            </ul>
          <?php endif; ?>
        </li>

        <!-- Cart Icon -->
        <li>
          <a class="nav-link position-relative" href="/blutmedical/cart.php">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cart-badge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0 <!-- This will be dynamically updated -->
            </span>
          </a>
        </li>


      </ul>


    </div>
  </div>

</nav>
<!-- End Header/Navigation -->

<script>
  // Function to fetch cart count and update the badge
  function updateCartBadge() {
    // Check if the user is logged in
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      // User is logged in, fetch cart count from the server
      fetch('/blutmedical/controllers/users/get_cart_count.php')
        .then((response) => response.json())
        .then((data) => {
          // Update the cart badge with the fetched cart count
          document.getElementById('cart-badge').textContent = data.cart_count;
        })
        .catch((error) => {
          console.error('Error fetching cart count:', error);
        });
    } else {
      // User is not logged in, fetch cart count from localStorage
      var guestCart = JSON.parse(localStorage.getItem('guestCart')) || [];
      var cartCount = guestCart.length; // Get the number of items in the guest cart
      // Update the cart badge with the guest cart count
      document.getElementById('cart-badge').textContent = cartCount;
    }
  }

  // Call the function to update the cart badge when the page loads
  updateCartBadge();
</script>