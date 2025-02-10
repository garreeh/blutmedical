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
              <label for="product_sellingprice">Main Product Selling Price:</label>
              <input type="text" class="form-control" id="product_sellingprice" name="product_sellingprice"
                placeholder="Enter Product Selling Price" required>
            </div>
            <div class="form-group col-md-6">
              <label for="product_image">Main Product Image:</label>
              <input type="file" class="form-control" id="product_image" name="fileToUpload" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="product_description">Product Description:</label>
              <textarea class="form-control" id="product_description" name="product_description"
                placeholder="Enter Product Description" rows="4" required></textarea>
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
          <hr>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Variations :</label>
              <div id="variations-container">
                <!-- Placeholder for variations -->
              </div>
              <button type="button" class="btn btn-secondary mt-2" id="add-variation-button">+ Add Variation</button>
            </div>
          </div>

          <hr>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Variation Colors:</label>
              <div id="variations-colors-container">
                <!-- Placeholder for variation colors -->
              </div>
              <button type="button" class="btn btn-secondary mt-2" id="add-variation-color-button">+ Add Color</button>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Other Product Images:</label>
              <div id="images-container">
                <!-- Placeholder for additional images -->
              </div>
              <button type="button" class="btn btn-secondary mt-2" id="add-image-button">+ Add Image</button>
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
  const addVariationButton = document.getElementById('add-variation-button');
  addVariationButton.addEventListener('click', function () {
    const container = document.getElementById('variations-container');

    const row = document.createElement('div');
    row.className = 'form-row mt-2';

    // Variation name field
    const variationNameCol = document.createElement('div');
    variationNameCol.className = 'form-group col-md-4';
    variationNameCol.innerHTML = `
    <input type="text" class="form-control" name="value[]" placeholder="Enter Variation Name" required>
  `;

    // Product code field
    const productCodeCol = document.createElement('div');
    productCodeCol.className = 'form-group col-md-3';
    productCodeCol.innerHTML = `
    <input type="text" class="form-control" name="product_code[]" placeholder="Enter Product Code" required>
  `;

    // Variation price field
    const variationPriceCol = document.createElement('div');
    variationPriceCol.className = 'form-group col-md-4';
    variationPriceCol.innerHTML = `
    <input type="text" class="form-control variation-price" name="price[]" placeholder="Enter Variation Price" required>
  `;

    // Remove button
    const removeCol = document.createElement('div');
    removeCol.className = 'form-group col-md-1';
    removeCol.innerHTML = `
    <button type="button" class="btn btn-danger btn-block remove-add-variation">Remove</button>
  `;

    row.appendChild(variationNameCol);
    row.appendChild(productCodeCol);
    row.appendChild(variationPriceCol);
    row.appendChild(removeCol);

    container.appendChild(row);

    // Add the event listener for the newly created price input
    const priceInput = row.querySelector('.variation-price');
    priceInput.addEventListener('input', restrictPriceInput);

    // Add event listener for remove button
    const removeButton = row.querySelector('.remove-add-variation');
    removeButton.addEventListener('click', function () {
      container.removeChild(row);
    });
  });

  // Function to restrict input to numbers and a single dot
  function restrictPriceInput(e) {
    this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot
    if ((this.value.match(/\./g) || []).length > 1) {
      this.value = this.value.slice(0, -1); // Remove the last character if there's more than one dot
    }
  }

  // Attach to existing inputs (if any)
  document.querySelectorAll('.variation-price').forEach(function (input) {
    input.addEventListener('input', restrictPriceInput);
  });


  // Add Variation Color functionality with remove button
  const addVariationColorButton = document.getElementById('add-variation-color-button');
  addVariationColorButton.addEventListener('click', function () {
    const container = document.getElementById('variations-colors-container');

    const row = document.createElement('div');
    row.className = 'form-row mt-2';

    // Variation color field
    const variationColorCol = document.createElement('div');
    variationColorCol.className = 'form-group col-md-11';
    variationColorCol.innerHTML = `
    <input type="text" class="form-control" name="color[]" placeholder="Enter Color Name" required>
  `;

    // Remove button
    const removeCol = document.createElement('div');
    removeCol.className = 'form-group col-md-1';
    removeCol.innerHTML = `
    <button type="button" class="btn btn-danger btn-block remove-add-color">Remove</button>
  `;

    row.appendChild(variationColorCol);
    row.appendChild(removeCol);

    container.appendChild(row);

    // Add event listener for remove button
    const removeButton = row.querySelector('.remove-add-color');
    removeButton.addEventListener('click', function () {
      container.removeChild(row);
    });
  });


  // Add Image functionality with remove button
  const addImageButton = document.getElementById('add-image-button');
  addImageButton.addEventListener('click', function () {
    const container = document.getElementById('images-container');

    const row = document.createElement('div');
    row.className = 'form-row mt-2';

    const imageCol = document.createElement('div');
    imageCol.className = 'form-group col-md-11';
    imageCol.innerHTML = `
    <input type="file" class="form-control" name="productImagePath[]" required>
  `;

    // Remove button
    const removeCol = document.createElement('div');
    removeCol.className = 'form-group col-md-1';
    removeCol.innerHTML = `
    <button type="button" class="btn btn-danger btn-block remove-add-image">Remove</button>
  `;

    row.appendChild(imageCol);
    row.appendChild(removeCol);

    container.appendChild(row);

    // Add event listener for remove button
    const removeButton = row.querySelector('.remove-add-image');
    removeButton.addEventListener('click', function () {
      container.removeChild(row);
    });
  });


  document.getElementById('product_sellingprice').addEventListener('input', function (e) {
    // Allow only numbers and dots, and ensure only one dot
    this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot
    if ((this.value.match(/\./g) || []).length > 1) {
      this.value = this.value.slice(0, -1); // Remove the last character if there's more than one dot
    }
  });



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