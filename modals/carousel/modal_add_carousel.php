<?php
include './../../connections/connections.php';

?>
<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<div class="modal fade" id="addCarouselModal" tabindex="-1" role="dialog" aria-labelledby="addCarouselModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCarouselModalLabel">Add Carousel images or video</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">

          <div class="form-group col-md-12">
            <label for="fileToUpload">Carousel Image / Video:</label>

            <input type="file" class="form-control" id="fileToUpload" name="fileToUpload"
              accept="image/jpeg,image/png,image/gif,image/webp,video/mp4" required>

            <small class="text-muted">
              Allowed: JPG, PNG, GIF, WEBP, MP4
            </small>
          </div>


          <!-- Add a hidden input field to submit the form with the button click -->
          <input type="hidden" name="add_carousel" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="addCarouselButton">Add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>

  $(document).ready(function () {
    $('#addCarouselModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = new FormData($form[0]);

      // Change button text to "Adding..." and disable it
      var $addButton = $('#addCarouselButton');
      $addButton.text('Adding...');
      $addButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_carousel_process.php',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json', // add this
        success: function (response) {
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

            // Optionally, reset the form
            $form.trigger('reset');

            // Optionally, close the modal
            $('#addCarouselModal').modal('hide');
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
          // Handle error response
          console.log("STATUS:", status);
          console.log("ERROR:", error);
          console.log("RESPONSE:", xhr.responseText);
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while adding carousel. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $addButton.text('Add');
          $addButton.prop('disabled', false);
        }
      });
    });
  });
</script>