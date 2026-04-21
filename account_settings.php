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
  <title>Blüt Medical | Contact us</title>
</head>

<body>

  <?php

  include './includes/navigation.php';
  include './connections/connections.php';

  ?>

  <!-- Start Hero Section -->
  <!-- <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1 style="color:black !important; opacity: 100%;">Contact</h1>
            <p class="mb-4" style="color:black !important; opacity: 100%;">We are a provider of innovative premium quality products that will elevate any medical
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
  </div> -->
  <!-- jQuery first -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <!-- Then Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


  <!-- Start Contact Form -->
  <div class="untree_co-section">
    <div class="container">

      <div class="block">
        <div class="row justify-content-center">


          <div class="col-md-8 col-lg-8 pb-4">


            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">

                  <div class="table-responsive">
                    <div id="modalContainerChangePassword"></div>

                    <table class="table custom-table table-hover" name="my_account_table" id="my_account_table">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Fullname</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Manage</th>

                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>


          </div>

        </div>

      </div>

    </div>


  </div>
  </div>

  <!-- End Contact Form -->



  <?php

  include './includes/footer.php';

  ?>

</body>

</html>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Data tables -->
<link rel="stylesheet" type="text/css" href="assets/datatables/datatables.min.css" />
<script type="text/javascript" src="assets/datatables/datatables.min.js"></script>

<script>
  $('#sidebarToggle').click(function() {
    $('#my_account_table').css('width', '100%');
    // console.log(table) //This is for testing only
  });

  $(document).ready(function() {
    var my_account_table = $('#my_account_table').DataTable({
      "pagingType": "numbers",
      "processing": true,
      "serverSide": true,
      "ajax": "controllers/tables/my_account_table.php",
      "lengthChange": false, // removes "Show X entries" dropdown
      "searching": false, // removes search box
      "ordering": false // removes sort/filter arrows
    });

    window.reloadDataTable = function() {
      my_account_table.ajax.reload();
    };
  });



  //Bridge for Modal Backend to Frontend
  $(document).ready(function() {
    // Function to handle click event on datatable rows
    $('#my_account_table').on('click', 'tr td:nth-child(5) .fetchDataPassword', function() {
      var user_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row
      console.log('Button clicked, User ID: ' + user_id);

      $.ajax({
        url: 'modal_change_password.php', // Path to PHP script to fetch modal content
        method: 'POST',
        data: {
          user_id: user_id
        },
        success: function(response) {
          $('#modalContainerChangePassword').html(response);
          $('#fetchDataUserModal').modal('show');
          console.log("Modal content loaded for User ID: " + user_id);
        },
        error: function(xhr, status, error) {
          console.error("Error: " + xhr.responseText);
        }
      });
    });
  });
</script>