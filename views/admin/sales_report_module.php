<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
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
  <link href="./../../assets/img/favicon.ico" rel="icon">


  <title>Admin | Sales Report</title>

  <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <link href="./../../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="./../../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

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
            <h1 class="h3 mb-0 text-gray-800">Sales Report Module</h1>
          </div>


          <div class="row mb-4">
            <div class="col-xl-12 col-lg-12">
              <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">
                <div class="form-row align-items-end">
                  <div class="col-auto">
                    <label for="dateFrom">Date From:</label>
                    <input type="date" id="dateFrom" name="dateFrom" class="form-control">
                  </div>
                  <div class="col-auto">
                    <label for="dateTo">Date To:</label>
                    <input type="date" id="dateTo" name="dateTo" class="form-control">
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-success shadow-sm mb-4" id="searchSalesReport">Search</button>
                  </div>

                  <div class="col-auto">
                    <!-- <a href="./../../excels/supplier_export.php" class="btn btn-success shadow-sm mb-4"><i class="fas fa-file-excel"></i> Export Excel</a> -->
                  </div>
                </div>

                <hr>
                <div id="dateRangeDisplay" class="mb-4">
                  <h4> From: <span id="displayFrom"> </span> </h4>
                  <br>
                  <h4> To: <span id="displayTo"> </span> </h4>
                  <hr>
                  <h4>Total Sales: <span id="totalSales"> </span></h4>
                </div>
                <br>

                <div id="modalContainerProvider"></div>

                <table class="table custom-table table-hover" name="sales_report_table" id="sales_report_table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Ref No.</th>
                      <th>Customer Name</th>
                      <th>Status</th>
                      <th>Total Payment</th>
                      <th>Payment Method</th>
                      <th>Proof of Payment</th>
                      <th>Date Created</th>
                      <th>Manage</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
      </div>

    </div>
    <!-- End of Main Content -->

  </div>
  <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="./../../assets/admin/vendor/jquery/jquery.min.js"></script>
  <script src="./../../assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./../../assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="./../../assets/admin/js/sb-admin-2.min.js"></script>

  <!-- Data tables -->
  <link rel="stylesheet" type="text/css" href="./../../assets/datatables/datatables.min.css" />
  <script type="text/javascript" src="./../../assets/datatables/datatables.min.js"></script>

  <!-- COPY THESE WHOLE CODE WHEN IMPORT SELECT -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

  <script>
    $(document).ready(function() {
      $('select').selectize({
        sortField: 'text'
      });
    });
  </script>
  <!-- END OF SELECT -->


</body>

</html>

<script>
  $('#sidebarToggle').click(function() {
    $('#sales_report_table').css('width', '100%');
  });

  $(document).ready(function() {
    // Function to handle click event on datatable rows
    $('#sales_report_table').on('click', 'tr td:nth-child(7) .ProofData', function() {
      var cart_id = $(this).closest('tr').find('td').first().text(); // Get the cart_id from the clicked row

      $.ajax({
        url: './../../modals/order/proof_modal.php', // Path to PHP script to fetch modal content
        method: 'POST',
        data: {
          cart_id: cart_id
        },
        success: function(response) {
          $('#modalContainerProvider').html(response);
          $('#showPhoto').modal('show');
          $('#cart_id').val(cart_id); // Set the cart_id here
          console.log("#showPhoto: " + cart_id);
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });

  $(document).ready(function() {
    var sales_report_table = $('#sales_report_table').DataTable({
      "pagingType": "numbers",
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "./../../controllers/tables/sales_report_table.php",
        "data": function(d) {
          d.date_from = $('#dateFrom').val();
          d.date_to = $('#dateTo').val();
        }
      },
    });

    $('#searchSalesReport').click(function() {
      $(this).text('Searching...').prop('disabled', true);
      // Get the selected dates
      var dateFrom = $('#dateFrom').val();
      var dateTo = $('#dateTo').val();

      // Update the displayed date range
      $('#displayFrom').text(dateFrom);
      $('#displayTo').text(dateTo);

      // Reload the table and fetch total sales
      sales_report_table.ajax.reload(function() {
        // Fetch total sales
        $.ajax({
          type: 'POST',
          url: './../../controllers/admin/sales_report_process.php', // Update with your PHP file path
          data: {
            searchSalesReport: true,
            date_from: $('#dateFrom').val(),
            date_to: $('#dateTo').val()
          },
          success: function(response) {
            const data = JSON.parse(response);
            // Update the total sales display
            $('#totalSales').text(data.total_sales);
            $('#searchSalesReport').text('Search').prop('disabled', false);
          }
        });
      });
    });
  });
</script>