<?php
include './../../connections/connections.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

// Initialize product name
$product_name = 'Unknown Product';

if ($product_id) {
  // Directly query the database
  $query = "SELECT product_name FROM product WHERE product_id = " . intval($product_id);
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $product_name = $row['product_name'];
  }
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


  <title>Admin | Variation</title>

  <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <link href="./../../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link href="./../../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
    integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />


</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include './../../includes/admin/admin_nav.php'; ?>
    <!-- End of Sidebar -->

    <!-- Modal for Adding and Editing Supplier -->
    <?php include './../../modals/product_image/modal_add_product_image.php'; ?>

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
            <h1 class="h3 mb-0 text-gray-800">Images for <?php echo $row['product_name'] ?></h1>
          </div>

          <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-4" data-toggle="modal"
            data-target="#addProductImageModal"> <i class="fas fa-plus"></i> Add Image</a>
          <!-- <a href="./../../excels/supplier_export.php" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-4"><i class="fas fa-file-excel"></i> Export Excel</a> -->

          <div class="row">
            <div class="col-xl-12 col-lg-12">
              <div class="tab-pane fade show active" id="aa" role="tabpanel" aria-labelledby="aa-tab">

                <div class="table-responsive">
                  <div id="modalContainerProductImages"></div>
                  <!-- <div id="modalContainerVariationDelete"></div> -->


                  <table class="table custom-table table-hover" name="product_images_table" id="product_images_table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Product Images</th>
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

  <!-- COPY THESE WHOLE CODE WHEN IMPORT SELECT -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
    integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
    integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

  <script>
    $(document).ready(function () {
      $('select').selectize({
        sortField: 'text'
      });
    });
  </script>
  <!-- END OF SELECT -->



</body>

</html>

<script>
  $('#sidebarToggle').click(function () {
    $('#product_images_table').css('width', '100%');
    // console.log(table) //This is for testing only
  });

  //Table for Product
  $(document).ready(function () {
    var product_images_table = $('#product_images_table').DataTable({
      "pagingType": "numbers",
      "processing": true,
      "serverSide": true,
      "ajax": {
        url: "./../../controllers/tables/product_images_table.php",
        type: "GET",
        data: function (d) {
          d.product_id = <?php echo $product_id; ?>; // Pass client_id
        }
      }
    });

    window.reloadDataTable = function () {
      product_images_table.ajax.reload();
    };

  });

  $(document).ready(function () {
    // Function to handle click event on datatable rows
    $('#product_images_table').on('click', 'tr td:nth-child(2) .fetchDataProductImage', function () {
      var product_image_id = $(this).closest('tr').find('td').first().text(); // Get the product_image_id from the clicked row

      $.ajax({
        url: './../../modals/product_image/modal_view_image_product.php', // Path to PHP script to fetch modal content
        method: 'POST',
        data: {
          product_image_id: product_image_id
        },
        success: function (response) {
          $('#modalContainerProductImages').html(response);
          $('#viewProductImageModal').modal('show');
          console.log("#viewProductImageModal" + product_image_id);
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });

  $(document).ready(function () {
    // Function to handle click event on datatable rows
    $('#product_images_table').on('click', 'tr td:nth-child(3) .fetchDataProductImagesEdit', function () {
      var product_image_id = $(this).closest('tr').find('td').first().text(); // Get the product_id from the clicked row

      $.ajax({
        url: './../../modals/product_image/modal_edit_product_image.php', // Path to PHP script to fetch modal content
        method: 'POST',
        data: {
          product_image_id: product_image_id
        },
        success: function (response) {
          $('#modalContainerProductImages').html(response);
          $('#editProductImageModal').modal('show');
          console.log("#editProductImageModal" + product_image_id);
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });


  });


</script>