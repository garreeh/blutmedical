<?php
include './connections/connections.php';

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

$sql = "SELECT * FROM subcategory";
$resultSubCategory = mysqli_query($conn, $sql);

?>

<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo/blut_logo.png" alt="Blut Logo" style="height: 10rem; width: auto;">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
      aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsFurni">
      <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
        <li>
          <a class="nav-link" href="index.php" style="color:black !important; opacity: 100%;">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false" style="color:black !important; opacity: 100%;">
            Shop
          </a>

          <ul class="dropdown-menu" aria-labelledby="userDropdown">

            <!-- PRODUCTS SECTION -->
            <!-- <li class="dropdown-header"><strong>Products</strong></li> -->

            <li>
              <a style="color: black !important;" class="dropdown-item" href="products.php">
                All Products
              </a>
            </li>

            <!-- CATEGORIES SECTION -->
            <!-- <li class="dropdown-header"><strong>Categories</strong></li> -->

            <li>
              <hr class="dropdown-divider">
            </li>

            <!-- SUBCATEGORIES SECTION -->
            <li class="dropdown-header"><strong>Shop by Category</strong></li>

            <?php while ($row = mysqli_fetch_assoc($resultSubCategory)): ?>
              <li>
                <a style="color: black !important;" class="dropdown-item"
                  href="/blutmedical/subcategory.php?subcategory_id=<?= $row['subcategory_id']; ?>">
                  <?= htmlspecialchars($row['subcategory_name']); ?>
                </a>
              </li>
            <?php endwhile; ?>

          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false" style="color:black !important; opacity: 100%;">
            Services
          </a>

          <ul class="dropdown-menu" aria-labelledby="userDropdown">


            <li>
              <a style="color: black !important;" class="dropdown-item" href="#">
                Be a Distributor
              </a>
            </li>

            <li>
              <a style="color: black !important;" class="dropdown-item" href="#">
                Lease a Machine
              </a>
            </li>

          </ul>
        </li>


        <!-- <li><a class="nav-link" href="products.php">Shop</a></li> -->
        <li><a class="nav-link" href="about.php" style="color:black !important; opacity: 100%;">About us</a></li>
        <li><a class="nav-link" href="contact.php" style="color:black !important; opacity: 100%;">Contact us</a></li>
      </ul>

      <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false" style="color:black !important; opacity: 100%;  font-size: large;">
            <i class="fa-solid fa-user"></i>
          </a>
          <?php if (isset($_SESSION['user_id'])): ?>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li></li>
              <li><a class="dropdown-item" href="/blutmedical/client_orders_module.php"
                  style="color:black !important; opacity: 100%;">My Orders</a></li>
              <li><a class="dropdown-item" href="/blutmedical/account_settings.php"
                  style="color:black !important; opacity: 100%;">My Account</a></li>
              <li><a class="dropdown-item" href="/blutmedical/completed_orders_module.php"
                  style="color:black !important; opacity: 100%;">Completed Orders</a></li>
              <li><a class="dropdown-item" href="/blutmedical/controllers/logout_process.php"
                  style="color:black !important; opacity: 100%;">Sign-out</a></li>
            </ul>
          <?php else: ?>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
              <li></li>
              <li><a class="dropdown-item" href="/blutmedical/views/login.php"
                  style="color:black !important; opacity: 100%;">Log In</a></li>
              <li><a class="dropdown-item" href="/blutmedical/views/register.php"
                  style="color:black !important; opacity: 100%;">Register</a></li>
            </ul>
          <?php endif; ?>
        </li>

        <!-- Cart Icon -->
        <li>
          <a class="nav-link position-relative" href="/blutmedical/cart.php"
            style="color:black !important; opacity: 100%; font-size: large;">
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

<style>
  .custom-navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1030;
    transition: transform 0.3s ease-in-out;
  }

  .custom-navbar.nav-hidden {
    transform: translateY(-100%);
  }

  body {
    padding-top: 170px;
    /* Adjust if your navbar height differs */
  }
</style>

<script>
  // Function to fetch cart count and update the badge
  function updateCartBadge() {
    var isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

    if (isLoggedIn) {
      fetch('/blutmedical/controllers/users/get_cart_count.php')
        .then((response) => response.json())
        .then((data) => {
          document.getElementById('cart-badge').textContent = data.cart_count;
        })
        .catch((error) => {
          console.error('Error fetching cart count:', error);
        });
    } else {
      var guestCart = JSON.parse(localStorage.getItem('guestCart')) || [];
      var cartCount = guestCart.length;
      document.getElementById('cart-badge').textContent = cartCount;
    }
  }

  updateCartBadge();

  // ==========================
  // Smart Hide/Show Navbar
  // ==========================
  let lastScrollTop = 0;
  const navbar = document.querySelector('.custom-navbar');
  const scrollThreshold = 50; // Hide only after scrolling down 50px

  window.addEventListener('scroll', function () {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    // Always show navbar near the top
    if (currentScroll <= 10) {
      navbar.classList.remove('nav-hidden');
      lastScrollTop = currentScroll;
      return;
    }

    // Scrolling down
    if (
      currentScroll > lastScrollTop &&
      currentScroll > scrollThreshold
    ) {
      navbar.classList.add('nav-hidden');
    }
    // Scrolling up
    else {
      navbar.classList.remove('nav-hidden');
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
  });
</script>