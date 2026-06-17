<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


$months = [];
$sales = [];

$sql = "
SELECT
    MONTH(cart.updated_at) AS month_num,
    SUM(product.product_sellingprice * cart.cart_quantity) AS total_sales
FROM cart
LEFT JOIN product ON product.product_id = cart.product_id
WHERE cart.cart_status = 'Delivered'
AND YEAR(cart.updated_at) = YEAR(CURDATE())
GROUP BY MONTH(cart.updated_at)
ORDER BY MONTH(cart.updated_at)
";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $months[] = date("F", mktime(0, 0, 0, $row['month_num'], 10));
  $sales[] = $row['total_sales'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Inventory | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- Custom fonts for this template-->
  <link href="./../../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="./../../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include './../../includes/admin/admin_nav.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php include './../../includes/admin/admin_topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          </div>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div id="clockAndDate" class="h1 mb-0 font-weight-bold text-gray-800"></div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <?php include './../../modals/dashboard_sales/modal_sales_graph.php'; ?>
            <!-- DAILY -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2 sales-card" style="cursor:pointer;"
                onclick="loadSalesGraph('daily')">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Daily Sales
                      </div>

                      <?php include './../../controllers/admin/daily_sales_process.php'; ?>

                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $ <?= number_format($daily_sales, 2); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- MONTHLY -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2 sales-card" style="cursor:pointer;"
                onclick="loadSalesGraph('monthly')">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Monthly Sales
                      </div>

                      <?php include './../../controllers/admin/monthly_sales_process.php'; ?>

                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        ₱ <?= number_format($monthly_sales, 2); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ANNUAL -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2 sales-card" style="cursor:pointer;"
                onclick="loadSalesGraph('annual')">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Annual Sales
                      </div>

                      <?php include './../../controllers/admin/annual_sales_process.php'; ?>

                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        ₱ <?= number_format($annual_sales, 2); ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- END ROW 1 -->


          <!-- SECOND ROW (TOP PRODUCTS + FUTURE GRAPH SPACE) -->
          <div class="row">

            <!-- TOP 5 PRODUCTS -->
            <div class="col-xl-6 col-lg-6 mb-4">

              <div class="card shadow h-100">

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Top 5 Selling Products
                  </h6>
                </div>

                <div class="card-body">

                  <?php
                  $sql = "
        SELECT
            product.product_name,
            SUM(cart.cart_quantity) AS total_sold,
            SUM(product.product_sellingprice * cart.cart_quantity) AS total_sales
        FROM cart
        LEFT JOIN product ON product.product_id = cart.product_id
        WHERE cart.cart_status = 'Delivered'
        GROUP BY product.product_id
        ORDER BY total_sold DESC
        LIMIT 5
        ";

                  $result = mysqli_query($conn, $sql);
                  ?>

                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">

                      <thead class="thead-light">
                        <tr>
                          <th>Product</th>
                          <th>Sold</th>
                          <th>Total Sales</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                          <tr>
                            <td><?= $row['product_name']; ?></td>
                            <td><?= $row['total_sold']; ?></td>
                            <td>$<?= number_format($row['total_sales'], 2); ?></td>
                          </tr>
                        <?php } ?>
                      </tbody>

                    </table>
                  </div>

                </div>
              </div>

            </div>

            <!-- PLACEHOLDER FOR MONTHLY TREND GRAPH -->
            <div class="col-xl-6 col-lg-6 mb-4">

              <div class="card shadow h-100">

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">
                    Monthly Sales Trend
                  </h6>
                </div>

                <div class="card-body">
                  <canvas id="salesChartMonthlyTrend"></canvas>
                </div>

              </div>

            </div>

          </div>


        </div>

      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

  </div>
  <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="./../../assets/admin/vendor/jquery/jquery.min.js"></script>
  <script src="./../../assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="./../../assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="./../../assets/admin/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>

<!-- Running Clock Script -->
<script>
  function updateClockAndDate() {
    var now = new Date();

    var hours = now.getHours();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;

    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    var monthNames = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

    var month = monthNames[now.getMonth()];
    var day = now.getDate();
    var year = now.getFullYear();

    var formattedTime =
      hours + ":" +
      (minutes < 10 ? "0" : "") + minutes + ":" +
      (seconds < 10 ? "0" : "") + seconds + " " + ampm;

    var formattedDate = month + " " + day + ", " + year;

    document.getElementById("clockAndDate").innerText =
      formattedTime + " | " + formattedDate;

    setTimeout(updateClockAndDate, 1000);
  }

  updateClockAndDate();


  // Chart for monthly trend
  const ctx = document.getElementById('salesChartMonthlyTrend');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($months); ?>,
      datasets: [{
        label: 'Monthly Sales',
        data: <?= json_encode($sales); ?>,
        borderColor: '#4e73df',
        backgroundColor: 'rgba(78,115,223,0.1)',
        borderWidth: 3,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Sales Graph: Daily, Monthly, Annual

  let chart = null;

  function loadSalesGraph(type) {

    let title = '';

    if (type === 'daily') title = 'Daily Sales (Hourly)';
    if (type === 'monthly') title = 'Monthly Sales (Daily)';
    if (type === 'annual') title = 'Annual Sales (Monthly)';

    document.getElementById('salesTitle').innerText = title;

    fetch('./../../controllers/admin/sales_graph_data.php?type=' + type)
      .then(res => res.json())
      .then(res => {

        const ctx = document.getElementById('salesChart');

        // ✅ FORCE DESTROY OLD CHART (SAFE RESET)
        if (chart !== null) {
          chart.destroy();
          chart = null;
        }

        // ✅ CLEAR CANVAS (IMPORTANT FIX)
        ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);

        chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: res.labels,
            datasets: [{
              label: 'Sales',
              data: res.data,
              borderColor: '#4e73df',
              backgroundColor: 'rgba(78,115,223,0.1)',
              fill: true,
              tension: 0.3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });

        // ✅ BOOTSTRAP 5 MODAL OPEN
        let modal = new bootstrap.Modal(document.getElementById('salesModal'));
        modal.show();

      });
  }


</script>