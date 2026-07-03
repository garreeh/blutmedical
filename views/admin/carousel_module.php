<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$user_type_id = $_SESSION['user_type_id']; // Assume this is set upon login

$sql = "SELECT *
        FROM usertype 
        WHERE user_type_id = '$user_type_id'";
$result = mysqli_query($conn, $sql);


if ($result) {
  while ($row_permission = mysqli_fetch_assoc($result)) {
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


      <title>Admin | Carousel</title>

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

        <?php include './../../modals/carousel/modal_add_carousel.php'; ?>

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
                <h1 class="h3 mb-0 text-gray-800">Carousel</h1>
              </div>

              <?php if ($row_permission['carousel_add'] == 1): ?>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-4" data-toggle="modal"
                  data-target="#addCarouselModal">
                  <i class="fas fa-plus"></i> Add Carousel
                </a>
              <?php endif; ?>


              <p class="text-muted mb-3">
                <strong>Note:</strong> Maximum of 3 carousel items only. Please upload 3 images or videos (3 total files) to
                complete the carousel.
              </p>

              <div class="row">
                <div class="col-xl-12 col-lg-12">
                  <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">

                    <div class="table-responsive">
                      <div id="modalContainerCarousel"></div>

                      <?php if ($row_permission['carousel_module'] == 1): ?>
                        <table class="table custom-table table-hover" name="carousel_table" id="carousel_table">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Scene</th>
                              <th>Date Created</th>
                              <th>Manage</th>

                            </tr>
                          </thead>
                        </table>
                      <?php endif; ?>

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

    <script>
      $('#sidebarToggle').click(function () {
        $('#carousel_table').css('width', '100%');
        // console.log(table) //This is for testing only
      });

      //Table for Supplier
      $(document).ready(function () {
        var carousel_table = $('#carousel_table').DataTable({
          "pagingType": "numbers",
          "processing": true,
          "serverSide": true,
          "ajax": "./../../controllers/tables/carousel_table.php",
        });

        window.reloadDataTable = function () {
          carousel_table.ajax.reload();
        };

      });

      //Column 3
      $(document).ready(function () {
        // Function to handle click event on datatable rows
        $('#carousel_table').on('click', 'tr td:nth-child(4) .fetchDataCarousel', function () {
          event.preventDefault();
          var carousel_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row

          $.ajax({
            url: './../../modals/carousel/modal_edit_carousel.php', // Path to PHP script to fetch modal content
            method: 'POST',
            data: {
              carousel_id: carousel_id
            },
            success: function (response) {
              $('#modalContainerCarousel').html(response);
              $('#fetchDataCarouselModal').modal('show');
              console.log("#fetchDataCarouselModal" + carousel_id);
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        });
      });

      //Column 3
      $(document).ready(function () {

        // Function to handle click event on datatable rows
        $('#carousel_table').on('click', 'tr td:nth-child(4) .fetchDataCarouselDelete', function () {
          event.preventDefault();
          var carousel_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row

          $.ajax({
            url: './../../modals/carousel/modal_delete_carousel.php', // Path to PHP script to fetch modal content
            method: 'POST',
            data: {
              carousel_id: carousel_id
            },
            success: function (response) {
              $('#modalContainerCarousel').html(response);
              $('#fetchDataCarouselDeleteModal').modal('show');
              console.log("#fetchDataCarouselDeleteModal: " + carousel_id);
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        });
      });
    </script>

    <?php
  }
}
?>