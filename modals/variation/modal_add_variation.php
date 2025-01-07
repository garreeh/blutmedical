<?php
include './../../connections/connections.php';

// Fetch user types from the database
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

$product_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $product_names[] = $row;
  }
}
?>
<div class="modal fade" id="addVariationModel" tabindex="-1" role="dialog" aria-labelledby="addVariationModelLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVariationModelLabel">Add Variation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="product_id">Products:</label>
              <select class="form-control" id="product_id" name="product_id" required>
                <option value="">Select Products</option>
                <?php foreach ($product_names as $product_rows): ?>
                  <option value="<?php echo $product_rows['product_id']; ?>">
                    <?php echo $product_rows['product_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group col-md-12">
              <label for="attribute">Attributes:</label>
              <input type="text" class="form-control" id="attribute" name="attribute" placeholder="Enter Attribute"
                required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="value">Value:</label>
              <input type="text" class="form-control" id="value" name="value" placeholder="Enter Product Description"
                required>
            </div>
          </div>

          <!-- Add a hidden input field to submit the form with the button click -->
          <input type="hidden" name="add_variation" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="addProductButton">Add</button>
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
    $('#addVariationModel form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = new FormData($form[0]);

      // Change button text to "Adding..." and disable it
      var $addButton = $('#addProductButton');
      $addButton.text('Adding...');
      $addButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_variation_process.php',
        data: formData,
        contentType: false,
        processData: false,
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

            // Optionally, reset the form
            $form.trigger('reset');

            $('#product_id')[0].selectize.clear();
            // Optionally, close the modal
            $('#addVariationModel').modal('hide');
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
            text: "Error occurred while adding product. Please try again later.",
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

    $('#addVariationModel').on('hidden.bs.modal', function () {

      $('#product_id')[0].selectize.clear();

    });
  });
</script>