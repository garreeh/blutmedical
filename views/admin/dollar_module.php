<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$sql = "SELECT * FROM currency WHERE dollar_id = 1";
$result = mysqli_query($conn, $sql);

$dollar_currency = null;
if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $dollar_currency = $row['dollar_currency'];
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


  <title>Admin | Shop Category</title>

  <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


  <link href="./../../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link href="./../../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include './../../includes/admin/admin_nav.php'; ?>
    <!-- End of Sidebar -->

    <!-- Modal for Adding and Editing Supplier -->

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
            <h1 class="h3 mb-0 text-gray-800">Currency Module</h1>
          </div>



          <div class="row">
            <div class="col-xl-12 col-lg-12">
              <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">
                <?php if ($dollar_currency !== null): ?>
                  <div class="card shadow-sm border-0 rounded">
                    <div class="card-body text-center">
                      <h5 class="card-title">Peso Currency</h5>
                      <p id="dollarValue" class="card-text" style="font-size: 1.5rem; font-weight: bold;">
                        <?php echo number_format($dollar_currency, 2); ?>
                      </p>
                      <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-4"
                        data-toggle="modal" data-target="#currencyRateModal">
                        <i class="fas fa-plus"></i> Update Currency Rate
                      </a>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="alert alert-warning text-center">
                    No currency data found.
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Update Currency Modal -->
          <div class="modal fade" id="currencyRateModal" tabindex="-1" aria-labelledby="currencyRateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Update Currency Rate</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="updateCurrencyForm">
                    <div class="form-group">
                      <label for="newCurrency">Enter New Rate</label>
                      <input type="number" step="0.01" class="form-control" id="newCurrency" name="newCurrency"
                        required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveCurrencyButton">Save</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <script>
            $(document).ready(function () {
              $('#updateCurrencyForm').submit(function (event) {
                event.preventDefault(); // Prevent form reload

                var formData = {
                  newCurrency: $('#newCurrency').val()
                };

                var $saveButton = $('#saveCurrencyButton');
                $saveButton.text('Saving...').prop('disabled', true);

                $.ajax({
                  type: 'POST',
                  url: 'currency_process.php', // Adjust this path if necessary
                  data: formData,
                  success: function (response) {
                    response = JSON.parse(response);

                    if (response.success) {
                      Toastify({
                        text: response.message,
                        duration: 2000,
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                      }).showToast();

                      // ✅ Update displayed currency value in real-time
                      $('#dollarValue').text(parseFloat(response.new_currency).toFixed(2));

                      // Close the modal
                      $('#currencyRateModal').modal('hide');
                    } else {
                      Toastify({
                        text: response.message,
                        duration: 2000,
                        backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
                      }).showToast();
                    }
                  },
                  error: function () {
                    Toastify({
                      text: "Error updating currency. Please try again.",
                      duration: 2000,
                      backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
                    }).showToast();
                  },
                  complete: function () {
                    $saveButton.text('Save').prop('disabled', false);
                  }
                });
              });
            });
          </script>


          <!-- /.container-fluid -->
        </div>

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

  <script src="./../../assets/admin/vendor/jquery/jquery.min.js"></script>
  <script src="./../../assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./../../assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="./../../assets/admin/js/sb-admin-2.min.js"></script>

  <!-- Data tables -->
  <link rel="stylesheet" type="text/css" href="./../../assets/datatables/datatables.min.css" />
  <script type="text/javascript" src="./../../assets/datatables/datatables.min.js"></script>



</body>

</html>