<?php
include './../../connections/connections.php';

// Fetch user types from the database
$sql = "SELECT * FROM supplier";
$result = mysqli_query($conn, $sql);

$supplier_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $supplier_names[] = $row;
  }
}

// Fetch user types from the database
$sql = "SELECT * FROM category";
$resultCategory = mysqli_query($conn, $sql);

$category_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($resultCategory)) {
    $category_names[] = $row;
  }
}
?>
<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="product_sku">Product SKU:</label>
              <input type="text" class="form-control" id="product_sku" name="product_sku"
                placeholder="Enter Product SKU" required>
            </div>
            <div class="form-group col-md-6">
              <label for="product_name">Product Name:</label>
              <input type="text" class="form-control" id="product_name" name="product_name"
                placeholder="Enter Product Name" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="product_description">Product Description:</label>
              <input type="text" class="form-control" id="product_description" name="product_description"
                placeholder="Enter Product Description" required>
            </div>
            <div class="form-group col-md-6">
              <label for="product_unitprice">Product Unit Price:</label>
              <input type="number" class="form-control" id="product_unitprice" name="product_unitprice"
                placeholder="Enter Product Unit Price" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="product_sellingprice">Product Selling Price:</label>
              <input type="number" class="form-control" id="product_sellingprice" name="product_sellingprice"
                placeholder="Enter Product Selling Price" required>
            </div>
            <div class="form-group col-md-6">
              <label for="product_image">Product Image:</label>
              <input type="file" class="form-control" id="product_image" name="fileToUpload" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="supplier_id">Supplier:</label>
              <select class="form-control" id="supplier_id" name="supplier_id" required>
                <option value="">Select Supplier</option>
                <?php foreach ($supplier_names as $supplier_rows): ?>
                  <option value="<?php echo $supplier_rows['supplier_id']; ?>">
                    <?php echo $supplier_rows['supplier_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="category_id">Category:</label>
              <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($category_names as $category_rows): ?>
                  <option value="<?php echo $category_rows['category_id']; ?>">
                    <?php echo $category_rows['category_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Add a hidden input field to submit the form with the button click -->
          <input type="hidden" name="add_product" value="1">

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
    $('#addProductModal form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/add_product_process.php',
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

            // Optionally, reset select for selectized
            $('#category_id')[0].selectize.clear();
            $('#supplier_id')[0].selectize.clear();
            // Optionally, close the modal
            $('#addProductModal').modal('hide');
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

    $('#addProductModal').on('hidden.bs.modal', function () {

      // Reset the dropdowns to their default states
      $('#category_id')[0].selectize.clear();

      $('#supplier_id')[0].selectize.clear();

    });
  });
</script>