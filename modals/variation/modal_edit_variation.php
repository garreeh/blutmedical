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
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

// Fetch user types from the database
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

$product_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $product_names[] = $row;
  }
}

if (isset($_POST['variation_id'])) {
  $variation_id = $_POST['variation_id'];
  $sql = "SELECT * FROM variations WHERE variation_id = '$variation_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="editVariationModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Variation ID: <?php echo $row['variation_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="variation_id" value="<?php echo $row['variation_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <input type="hidden" class="form-control" id="product_id" name="product_id" placeholder="Enter Product ID"
                      value="<?php echo $row['product_id']; ?>" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="Enter price for variation"
                      value="<?php echo $row['price']; ?>" required>
                  </div>
                </div>

                <div class="form-row">

                  <div class="form-group col-md-12">
                    <label for="value">Value:</label>
                    <input type="text" class="form-control" id="value" name="value" placeholder="Enter Value"
                      value="<?php echo $row['value']; ?>" required>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_variation" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveVariationButton">Save</button>
                  <!-- <input type="hidden" name="item_id" value="</?php echo $row['variation_id']; ?>"> -->
                  <button type="button" class="btn btn btn-danger" data-dismiss="modal">Close</button>
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
  // Save Button in Edit Category
  $(document).ready(function () {
    $('#editVariationModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission
      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = $form.serialize();

      // Change button text to "Saving..." and disable it
      var $saveButton = $('#saveVariationButton');
      $saveButton.text('Saving...');
      $saveButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/edit_variation_process.php',
        data: formData,
        success: function (response) {
          // Handle success response
          console.log(response); // Log the response for debugging
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            // Optionally, close the modal
            $('#editVariationModal').modal('hide');
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
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while editing category. Please try again later.",
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