<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
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
      ?>
      <div class="modal fade" id="fetchDataCarouselDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Delete Carousel Details ID: <?php echo $row['carousel_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="carousel_id" value="<?php echo $row['carousel_id']; ?>">

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <h2>Are you sure you want to delete this carousel?</h2>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="delete_carousel" value="1">

                <div class="modal-footer">
                  <button type="submit" id="deleteCarouselButton" class="btn btn-primary">
                    Yes
                  </button>

                  <button type="button" class="btn btn-danger" data-dismiss="modal">
                    No
                  </button>
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

  $(document).ready(function () {
    $('#fetchDataCarouselDeleteModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = new FormData($form[0]);

      // Change button text to "Adding..." and disable it
      var $deleteButton = $('#deleteCarouselButton');
      $deleteButton.html(`
  <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
  Deleting...
`);
      $deleteButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/delete_carousel_process.php',
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
            $('#fetchDataCarouselDeleteModal').modal('hide');
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
            text: "Error occurred while deleting carousel. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $deleteButton.text('Yes');
          $deleteButton.prop('disabled', false);
        }
      });
    });
  });
</script>