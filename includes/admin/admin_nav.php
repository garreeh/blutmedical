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
          <a class="nav-link" href="/blutmedical/views/admin/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Admin Panel
        </div>

        <?php if ($row['client_order_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" href="/blutmedical/views/admin/orders_module.php">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Client Orders</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['shipped_order_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" href="/blutmedical/views/admin/deliveries_module.php">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Shipped Orders</span></a>
          </li>
        <?php endif; ?>

        <?php if ($row['view_transaction_module'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link" href="/blutmedical/views/admin/transaction_module.php">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Transactions</span></a>
          </li>
        <?php endif; ?>

        <!-- <li class="nav-item">
      <a class="nav-link" href="/blutmedical/views/admin/billing_module.php">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>Voucher</span></a>
    </li> -->

        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
          Reports, Product & Setup
        </div>



        <?php if ($row['sales_report_module'] == 1): ?>
          <!-- Reports Collapse -->
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true"
              aria-controls="collapse1">
              <i class="fas fa-fw fa-clipboard-list"></i>
              <span>Reports</span>
            </a>
            <div id="collapse1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Setup:</h6> -->

                <a class="collapse-item" href="/blutmedical/views/admin/sales_report_module.php">Sales Report</a>

              </div>
            </div>
          </li>
        <?php endif; ?>
        <?php if ($row['product_setup_module'] == 1): ?>
          <!-- Products Setup Collapse -->
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true"
              aria-controls="collapse2">
              <i class="fas fa-fw fa-clipboard-list"></i>
              <span>Product Setup</span>
            </a>
            <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Setup:</h6> -->
                <a class="collapse-item" href="/blutmedical/views/admin/supplier_module.php">Suppliers</a>
                <a class="collapse-item" href="/blutmedical/views/admin/subcategory_module.php">Shop Category</a>
                <a class="collapse-item" href="/blutmedical/views/admin/category_module.php">Item Category</a>
                <a class="collapse-item" href="/blutmedical/views/admin/product_module.php">Products</a>
                <!-- <a class="collapse-item" href="/blutmedical/views/admin/variation_module.php">Variation</a> -->
                <!-- <a class="collapse-item" href="/blutmedical/views/admin/upload_image_module.php">Upload Image</a> -->


              </div>

            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/blutmedical/views/admin/dollar_module.php">
              <i class="fas fa-fw fa-money-bill"></i>
              <span>Peso Currency</span></a>
          </li>
        <?php endif; ?>

        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <div class="sidebar-heading">
          Settings
        </div>

        <?php if ($row['user_setup'] == 1): ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse4" aria-expanded="true"
              aria-controls="collapse4">
              <i class="fas fa-fw fa-cogs"></i>
              <span>User</span>
            </a>
            <div id="collapse4" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Setup:</h6> -->
                <a class="collapse-item" href="/blutmedical/views/admin/user_type_module.php">User Type List</a>
                <a class="collapse-item" href="/blutmedical/views/admin/user_module.php">Users List</a>
                <!-- <a class="collapse-item" href="/blutmedical/views/admin/customer_module.php">Customers</a> -->
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