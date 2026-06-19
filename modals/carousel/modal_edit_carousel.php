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


if (isset($_POST['carousel_id'])) {
  $carousel_id = $_POST['carousel_id'];

  $sql = "SELECT * FROM carousel WHERE carousel_id = '$carousel_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $scene = basename($row['scene']);

      $image_url = '../../uploads/' . $scene; // Construct the image URL

      ?>
      <div class="modal fade" id="fetchDataCarouselModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Carousel Image / Video ID: <?php echo $row['carousel_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="carousel_id" value="<?php echo $row['carousel_id']; ?>">



                <div class="form-group col-md-12">

                  <label for="fileToUpload">
                    Carousel Image / Video:
                  </label>

                  <input type="file" class="form-control" id="fileToUpload" name="fileToUpload"
                    accept="image/jpeg,image/png,image/gif,image/webp,video/mp4">


                  <small class="text-muted">
                    Allowed: JPG, PNG, GIF, WEBP, MP4
                  </small>


                  <div class="file-info mt-2">

                    <?php if (!empty($scene)): ?>

                      <p>
                        <strong>Current File:</strong>
                        <?php echo htmlspecialchars($scene); ?>
                      </p>


                      <?php
                      $extension = strtolower(
                        pathinfo($scene, PATHINFO_EXTENSION)
                      );
                      ?>


                      <?php if ($extension === 'mp4'): ?>

                        <video width="300" controls>
                          <source src="../../uploads/<?php echo htmlspecialchars($scene); ?>" type="video/mp4">
                        </video>


                      <?php else: ?>


                        <img src="../../uploads/<?php echo htmlspecialchars($scene); ?>" width="300" class="img-thumbnail">


                      <?php endif; ?>


                    <?php else: ?>


                      <p>No file available.</p>


                    <?php endif; ?>

                  </div>

                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_carousel" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveProductButton">Save</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>


      <?php
    }
  }
}
?>

<script>
  // Submit Button AJAX
  $(document).ready(function () {
    // Form submission handling
    $('#fetchDataCarouselModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var $form = $(this);

      // Create a FormData object to handle file uploads
      var formData = new FormData($form[0]);

      // Change button text to "Saving..." and disable it
      var $saveButton = $('#saveProductButton');
      $saveButton.text('Saving...');
      $saveButton.prop('disabled', true);

      // Send AJAX request for product form submission
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/edit_carousel_process.php',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Let the browser set the content type for the FormData
        dataType: 'json', // add this

        success: function (response) {
          try {
            // Handle success response
            console.log(response);

            // REMOVE THIS:
            // response = JSON.parse(response);

            if (response.success) {
              Toastify({
                text: response.message,
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              $('#fetchDataCarouselModal').modal('hide');
              window.reloadDataTable();
            } else {
              Toastify({
                text: response.message,
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
              }).showToast();
            }
          } catch (error) {
            console.error('Error parsing response JSON:', error);
            Toastify({
              text: "An error occurred while processing the product update.",
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.log("STATUS:", status);
          console.log("ERROR:", error);
          console.log("RESPONSE:", xhr.responseText);
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