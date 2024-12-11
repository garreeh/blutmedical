<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }

  .modal-body img {
    max-width: 100%;
    /* Ensure the image fits within the modal */
    height: auto;
    max-height: 300px;
    /* Limit the image height */
    object-fit: contain;
    /* Maintain aspect ratio */
  }

  .file-info {
    margin-top: 10px;
  }
</style>

<?php
include './../../connections/connections.php';

if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Delete Product ID: <?php echo $row['product_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <h2>Are you sure you want to delete this?</h2>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="delete_product" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveProductButton">Yes</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

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

      <?php
    }
  }
}
?>

<script>
  $(document).ready(function () {
    $('#deleteProductModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var $form = $(this);

      // Create a FormData object to handle file uploads
      var formData = new FormData($form[0]);

      // Change button text to "Saving..." and disable it
      var $saveButton = $('#saveProductButton');
      $saveButton.text('Saving...');
      $saveButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/delete_product_process.php',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Let the browser set the content type for the FormData
        success: function (response) {
          console.log(response); // Log the response for debugging
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $('#deleteProductModal').modal('hide');
            window.reloadDataTable();

          } else {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while editing product. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $saveButton.text('Save');
          $saveButton.prop('disabled', false);
        }
      });


    });
  });
</script>