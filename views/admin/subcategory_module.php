<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$shop_query = $conn->query("SELECT * FROM subcategory");
$shop_count = $shop_query->num_rows;

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
        <?php include './../../modals/category/modal_add_subcategory.php'; ?>

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
                <h1 class="h3 mb-0 text-gray-800">Shop Category Module</h1>
              </div>



              <!-- TOP ACTION BAR -->
              <div class="d-flex justify-content-between align-items-center mb-4">

                <!-- LEFT: Action Button -->
                <?php if ($row_permission['shop_category_add'] == 1): ?>
                  <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-4" data-toggle="modal"
                    data-target="#addCategoryModal"> <i class="fas fa-plus"></i> Add Shop Category</a>
                <?php endif; ?>


                <!-- RIGHT: KPI -->
                <div class="text-right">
                  <span class="text-muted small d-block">Total Shop Category</span>
                  <span class="h1 font-weight-bold text-success">
                    <?php echo $shop_count; ?>
                  </span>
                </div>

              </div>


              <div class="row">
                <div class="col-xl-12 col-lg-12">
                  <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">

                    <div class="table-responsive">
                      <div id="modalContainerCategory"></div>

                      <?php if ($row_permission['shop_category_module'] == 1): ?>
                        <table class="table custom-table table-hover" name="subcategory_table" id="subcategory_table">
                          <thead>
                            <tr>
                              <th>Shop Category ID</th>
                              <th>Shop Category Name</th>
                              <th>Date Created</th>
                              <th>Date Updated</th>
                              <?php if ($row_permission['shop_category_edit'] == 1): ?>
                                <th>Manage</th>
                              <?php endif; ?>

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
        $('#subcategory_table').css('width', '100%');
        // console.log(table) //This is for testing only
      });

      //Table for Supplier
      $(document).ready(function () {
        var subcategory_table = $('#subcategory_table').DataTable({
          "pagingType": "numbers",
          "processing": true,
          "serverSide": true,
          "ajax": "./../../controllers/tables/subcategory_table.php",
        });

        window.reloadDataTable = function () {
          subcategory_table.ajax.reload();
        };

      });

      //Column 5
      $(document).ready(function () {
        // Function to handle click event on datatable rows
        $('#subcategory_table').on('click', 'tr td:nth-child(5) .fetchDataCategory', function () {
          var subcategory_id = $(this).closest('tr').find('td').first().text(); // Get the user_id from the clicked row

          $.ajax({
            url: './../../modals/category/modal_edit_subcategory.php', // Path to PHP script to fetch modal content
            method: 'POST',
            data: {
              subcategory_id: subcategory_id
            },
            success: function (response) {
              $('#modalContainerCategory').html(response);
              $('#editCategoryModal').modal('show');
              console.log("#editCategoryModal" + subcategory_id);
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