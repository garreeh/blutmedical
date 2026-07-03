<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if the user is not logged in
  header("Location: /blutmedical/views/login.php");
  exit();
}

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== "1") {
  // If the user is not an admin (is_admin is not set or not "1"), redirect to the user dashboard
  header("Location: /blutmedical/index.php"); // Adjust the redirect location as needed
  exit();
}

if (!isset($_SESSION['user_type_id'])) {
  // Handle the case when user_type_id is not set, e.g., redirect to login
  echo "User type is not set. Please log in.";
  exit; // Exit if the user is not logged in
}

$user_type_id = $_SESSION['user_type_id']; // Assume this is set upon login

// Query the database to get permissions for this user_type_id
$sql = "SELECT *
        FROM usertype 
        WHERE user_type_id = '$user_type_id'";
$result = mysqli_query($conn, $sql);

if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
    </head>

    <body id="page-top">
      <!-- Sidebar -->
      <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
          href="/blutmedical/views/admin/dashboard.php">
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
          </div>
          <div class="sidebar-brand-text mx-3">Blut <sup>Medical</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
          <a class="nav-link" data-module="dashboard" href="/blutmedical/views/admin/dashboard.php?module=dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Admin Panel
        </div>

        <?php if ($row['client_order_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="orders" href="/blutmedical/views/admin/orders_module.php?module=orders">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Client Orders</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['shipped_order_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="deliveries"
              href="/blutmedical/views/admin/deliveries_module.php?module=deliveries"">
              <i class=" fas fa-fw fa-money-bill"></i>
              <span>Shipped Orders</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['view_transaction_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="transactions"
              href="/blutmedical/views/admin/transaction_module.php?module=transactions">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Transactions</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['customer_cart_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="customer_cart"
              href="/blutmedical/views/admin/customer_cart_module.php?module=customer_cart">
              <i class="fas fa-fw fa-shopping-cart"></i>
              <span>Customer Cart</span></a>
          </li>
        <?php endif; ?>

        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
          Reports, Product & Setup
        </div>



        <!-- Reports Collapse -->
        <li class="nav-item" data-module="reports">

          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true"
            aria-controls="collapse1">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Reports</span>
          </a>

          <div id="collapse1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

              <?php if ($row['sales_report_module'] == 1): ?>
                <a class="collapse-item" data-submodule="sales_report"
                  href="/blutmedical/views/admin/sales_report_module.php?module=reports&submodule=sales_report">
                  Sales Report
                </a>
              <?php endif; ?>

              <?php if ($row['report_product_ranking'] == 1): ?>
                <a class="collapse-item" data-submodule="product_ranking"
                  href="/blutmedical/views/admin/product_rank_module.php?module=reports&submodule=product_ranking">
                  Product Ranking
                </a>
              <?php endif; ?>

              <?php if ($row['report_customer_details'] == 1): ?>
                <a class="collapse-item" data-submodule="customers"
                  href="/blutmedical/views/admin/customer_module.php?module=reports&submodule=customers">
                  Customer Details
                </a>
              <?php endif; ?>

            </div>
          </div>
        </li>

        <!-- Products Setup Collapse -->
        <li class="nav-item" data-module="product_setup">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true"
            aria-controls="collapse2">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Product Setup</span>
          </a>
          <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <!-- <h6 class="collapse-header">Setup:</h6> -->

              <?php if ($row['supplier_module'] == 1): ?>
                <a class="collapse-item" data-submodule="suppliers"
                  href="/blutmedical/views/admin/supplier_module.php?module=product_setup&submodule=suppliers">
                  Suppliers
                </a>
              <?php endif; ?>

              <?php if ($row['shop_category_module'] == 1): ?>
                <a class="collapse-item" data-submodule="shop_category"
                  href="/blutmedical/views/admin/subcategory_module.php?module=product_setup&submodule=shop_category">
                  Shop Category
                </a>
              <?php endif; ?>

              <?php if ($row['item_category_module'] == 1): ?>
                <a class="collapse-item" data-submodule="item_category"
                  href="/blutmedical/views/admin/category_module.php?module=product_setup&submodule=item_category">
                  Item Category
                </a>
              <?php endif; ?>

              <?php if ($row['product_setup_module'] == 1): ?>
                <a class="collapse-item" data-submodule="products"
                  href="/blutmedical/views/admin/product_module.php?module=product_setup&submodule=products">
                  Products
                </a>
              <?php endif; ?>

            </div>

          </div>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
          Settings
        </div>
        <?php if ($row['peso_currency_settings'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="peso" href="/blutmedical/views/admin/dollar_module.php?module=peso">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Peso Currency Settings</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['discount_module'] == 1): ?>

          <li class="nav-item">
            <a class="nav-link" data-module="discount" href="/blutmedical/views/admin/discount_module.php?module=discount">
              <i class="fas fa-fw fa-percentage"></i> <!-- Percentage discount -->
              <span>Voucher Settings</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['carousel_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" data-module="carousel" href="/blutmedical/views/admin/carousel_module.php?module=carousel">
              <i class="fas fa-fw fa-images"></i>
              <span>Carousel Settings</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['user_setup'] == 1): ?>
          <li class="nav-item" data-module="user_setup">

            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse4" aria-expanded="true"
              aria-controls="collapse4">
              <i class="fas fa-fw fa-cogs"></i>
              <span>User</span>
            </a>

            <div id="collapse4" class="collapse" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item" data-submodule="user_type"
                  href="/blutmedical/views/admin/user_type_module.php?module=user_setup&submodule=user_type">
                  User Type List
                </a>

                <a class="collapse-item" data-submodule="users"
                  href="/blutmedical/views/admin/user_module.php?module=user_setup&submodule=users">
                  Users List
                </a>

              </div>
            </div>
          </li>
        <?php endif; ?>

        <!-- <li class="nav-item">
          <a class="nav-link" href="/blutmedical/views/admin/billing_module.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Account Setting</span></a>
        </li> -->

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <li class="nav-item">
          <a class="nav-link" href="/blutmedical/controllers/logout_process.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Sign Out</span></a>
        </li>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
      </ul>
      <!-- End of Sidebar -->
    </body>

    </html>

    <?php
  }
}
?>

<script>
  document.addEventListener('DOMContentLoaded', function () {

    const params = new URLSearchParams(window.location.search);

    const currentModule = params.get('module') || 'dashboard';
    const currentSubmodule = params.get('submodule');

    // Handle modules on nav-item (Product Setup, Reports, etc.)
    document.querySelectorAll('.nav-item[data-module]').forEach(item => {

      const isActive = item.dataset.module === currentModule;

      item.classList.toggle('active', isActive);

      if (isActive) {
        const collapse = item.querySelector('.collapse');
        if (collapse) {
          collapse.classList.add('show');
        }
      }
    });

    // Handle modules on nav-link (Dashboard, Orders, etc.)
    document.querySelectorAll('.nav-link[data-module]').forEach(link => {

      const isActive = link.dataset.module === currentModule;

      if (isActive) {
        link.closest('.nav-item')?.classList.add('active');
      }
    });

    // Handle submodules
    document.querySelectorAll('.collapse-item[data-submodule]').forEach(item => {

      item.classList.toggle(
        'active',
        item.dataset.submodule === currentSubmodule
      );

    });

  });
</script>