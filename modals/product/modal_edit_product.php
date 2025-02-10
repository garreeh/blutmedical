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

if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
  $result = mysqli_query($conn, $sql);

  $sql = "SELECT * FROM variations WHERE product_id = '$product_id'";
  $result_variations = mysqli_query($conn, $sql);

  $variations = [];
  if ($result_variations) {
    while ($row = mysqli_fetch_assoc($result_variations)) {
      $variations[] = $row;
    }
  }

  $sql = "SELECT * FROM product_image WHERE product_id = '$product_id'";
  $result_images = mysqli_query($conn, $sql);

  $images = [];
  if ($result_images) {
    while ($row = mysqli_fetch_assoc($result_images)) {
      $images[] = $row;
    }
  }

  $sql_colors = "SELECT * FROM variations_colors WHERE product_id = '$product_id'";
  $result_colors = mysqli_query($conn, $sql_colors);

  $colors = [];
  if ($result_colors) {
    while ($row = mysqli_fetch_assoc($result_colors)) {
      $colors[] = $row;
    }
  }


  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $product_image = basename($row['product_image']);

      $image_url = '../../uploads/' . $product_image; // Construct the image URL
      ?>
      <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Product ID: <?php echo $row['product_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="product_sku">Product SKU:</label>
                    <input type="text" class="form-control" id="product_sku" name="product_sku"
                      placeholder="Enter Product SKU" value="<?php echo $row['product_sku']; ?>" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="product_name">Product Name:</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                      placeholder="Enter Product Name" value="<?php echo $row['product_name']; ?>" required>
                  </div>
                </div>



                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="product_sellingprice">Main Product Selling Price:</label>
                    <input type="text" class="form-control" id="product_sellingprice_update" name="product_sellingprice"
                      placeholder="Enter Product Selling Price" value="<?php echo $row['product_sellingprice']; ?>" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="product_image">Main Product Image:</label>
                    <input type="file" class="form-control" id="product_image" name="fileToUpload">
                    <!-- Display existing image filename -->
                    <div class="file-info">
                      <?php if (!empty($product_image) && file_exists('../../uploads/' . $product_image)): ?>
                        <p><strong>Current Image:</strong> <?php echo $product_image; ?></p>
                      <?php else: ?>
                        <p>No image available.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="product_description">Product Description:</label>
                    <textarea class="form-control" id="product_description" name="product_description"
                      placeholder="Enter Product Description" required><?php echo $row['product_description']; ?></textarea>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="supplier_id">Supplier:</label>
                    <select class="form-control" id="supplier_id" name="supplier_id" required>
                      <option value="" disabled>Select Supplier</option>
                      <?php
                      // Loop through the supplier names to populate the dropdown
                      foreach ($supplier_names as $supplier_rows) {
                        // Set selected if the supplier_id matches
                        $selected = ($supplier_rows['supplier_id'] == $row['supplier_id']) ? 'selected' : '';
                        echo "<option value='" . $supplier_rows['supplier_id'] . "' $selected>" . $supplier_rows['supplier_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>


                  <div class="form-group col-md-6">
                    <label for="category_id">Category:</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                      <option value="" disabled>Select Category</option>
                      <?php
                      // Loop through category names to populate the dropdown
                      foreach ($category_names as $category_rows) {
                        // Set selected if the category_id matches
                        $selected = ($category_rows['category_id'] == $row['category_id']) ? 'selected' : '';
                        echo "<option value='" . $category_rows['category_id'] . "' $selected>" . $category_rows['category_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <hr>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Variations :</label>

                    <div id="variations-container_update">
                      <?php if (!empty($variations)): ?>
                        <?php foreach ($variations as $variation): ?>
                          <div class="form-row mt-2">
                            <input type="hidden" name="variation_id[]" value="<?php echo $variation['variation_id']; ?>">

                            <div class="form-group col-md-4">
                              <label>Variation Name:</label>

                              <input type="text" class="form-control" name="value[]" value="<?php echo $variation['value']; ?>"
                                placeholder="Enter Variation Name" required>
                            </div>
                            <div class="form-group col-md-3">
                              <label>Product Code:</label>
                              <input type="text" class="form-control variation-product-code" name="product_code[]"
                                value="<?php echo $variation['product_code']; ?>" placeholder="Enter Variation Price" required>
                            </div>
                            <div class="form-group col-md-4">
                              <label>Variation Price:</label>
                              <input type="text" class="form-control variation-price" name="price[]"
                                value="<?php echo $variation['price']; ?>" placeholder="Enter Produt Code" required>
                            </div>
                            <div class="form-group col-md-1">
                              <label></label>

                              <button type="button" class="btn btn-danger remove-variation"
                                data-id="<?php echo $variation['variation_id']; ?>">Remove</button>

                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                      <button type="button" class="btn btn-secondary" id="add-variation-button_update">+ Add
                        Variation</button>
                    </div>
                  </div>
                </div>
                <hr>

                <div class="form-group">
                  <label>Product Color:</label>
                  <div id="color-container_update">
                    <?php foreach ($colors as $color): ?>
                      <div class="input-group mb-2">
                        <input type="hidden" name="variation_color_id[]" value="<?php echo $color['variation_color_id']; ?>">

                        <input type="text" class="form-control" name="color[]" value="<?php echo $color['color']; ?>"
                          placeholder="Enter Color" required>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-danger remove-color"
                            data-id="<?php echo $color['variation_color_id']; ?>">Remove</button>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <button type="button" id="add-color-button_update" class="btn btn-secondary mt-2">+ Add Color</button>
                </div>

                <hr>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label>Other Product Images:</label>

                    <div id="images-container_update">
                      <?php if (!empty($images)): ?>
                        <?php foreach ($images as $image): ?>
                          <div class="form-row">
                            <!-- This should likely reflect a proper value if `product_image_id` is defined -->
                            <input type="hidden" name="product_image_id[]" value="<?php echo $image['product_image_id']; ?>">


                            <div class="form-group col-md-11">
                              <input type="file" class="form-control" name="productImagePath[]" />
                              <div class="file-info">
                                <?php if (!empty($image['product_image_path']) && file_exists($image['product_image_path'])): ?>
                                  <p><strong>Current Image:</strong> <?php echo $image['product_image_path']; ?></p>
                                <?php else: ?>
                                  <p>No image available.</p>
                                <?php endif; ?>
                              </div>
                            </div>
                            <div class="form-group col-md-1">
                              <button type="button" class="btn btn-danger remove-image"
                                data-id="<?php echo $image['product_image_id']; ?>">Remove</button>

                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>

                      <button type="button" class="btn btn-secondary" id="add-image-button_update">+ Add Image</button>
                    </div>
                  </div>
                </div>
                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_product" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveProductButton">Save</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
  // Add Variation Functionality
  document.getElementById('add-variation-button_update').addEventListener('click', function () {
    const container = document.getElementById('variations-container_update');

    const newVariation = document.createElement('div');
    newVariation.classList.add('form-row', 'mt-2');
    newVariation.innerHTML = `
    <div class="form-group col-md-4">
      <label>Variation Name:</label>
      <input type="text" class="form-control" name="value[]" placeholder="Enter Variation Name" required>
    </div>
    <div class="form-group col-md-3">
      <label>Product Code:</label>
      <input type="text" class="form-control" name="product_code[]" placeholder="Enter Product Code" required>
    </div>
    <div class="form-group col-md-4">
      <label>Variation Price:</label>
      <input type="text" class="form-control variation-price" name="price[]" placeholder="Enter Variation Price" required>
    </div>
    <div class="form-group col-md-1">
      <label></label>
      <button type="button" class="btn btn-danger remove-variation">Remove</button>
    </div>
  `;

    container.appendChild(newVariation);

    // Add event listener to newly added Remove button
    // newVariation.querySelector('.remove-variation').addEventListener('click', function() {
    //   newVariation.remove();
    // });
    const priceInputs = document.querySelectorAll('.variation-price');
    priceInputs.forEach(function (priceInput) {
      priceInput.addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot
        if ((this.value.match(/\./g) || []).length > 1) {
          this.value = this.value.slice(0, -1); // Remove the last character if there's more than one dot
        }
      });
    });
  });

  // Remove Variation Functionality
  document.querySelectorAll('.remove-variation').forEach(function (button) {
    button.addEventListener('click', function () {
      this.parentElement.parentElement.remove();
    });
  });

  // Add Variation Functionality
  document.getElementById('add-color-button_update').addEventListener('click', function () {
    const container = document.getElementById('color-container_update');

    const newVariationColor = document.createElement('div');
    newVariationColor.classList.add('form-row', 'mt-2');
    newVariationColor.innerHTML = `
    <div class="form-group col-md-10">
      <input type="text" class="form-control" name="color[]" placeholder="Enter Color Name" required>
    </div>

    <div class="form-group col-md-2">
      <label></label>
      <button type="button" class="btn btn-danger remove-color">Remove</button>
    </div>
  `;

    container.appendChild(newVariationColor);

  });

  // Remove Variation Functionality
  document.querySelectorAll('.remove-color').forEach(function (button) {
    button.addEventListener('click', function () {
      this.parentElement.parentElement.remove();
    });
  });

  // Add Image Functionality
  document.getElementById('add-image-button_update').addEventListener('click', function () {
    const container = document.getElementById('images-container_update');

    const newImage = document.createElement('div');
    newImage.classList.add('form-row');
    newImage.innerHTML = `
    <div class="form-group col-md-11">
      <input type="file" class="form-control" name="productImagePath[]" />
      <div class="file-info">
        <p>No image available.</p>
      </div>
    </div>
    <div class="form-group col-md-1">
      <button type="button" class="btn btn-danger remove-image">Remove</button>
    </div>
  `;

    container.appendChild(newImage);

    // Add event listener to newly added Remove button
    newImage.querySelector('.remove-image').addEventListener('click', function () {
      newImage.remove();
    });
  });

  // Remove Image Functionality
  document.querySelectorAll('.remove-image').forEach(function (button) {
    button.addEventListener('click', function () {
      this.parentElement.parentElement.remove();
    });
  });

  // Varation Remove AJAX
  document.getElementById('product_sellingprice_update').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot
    if ((this.value.match(/\./g) || []).length > 1) {
      this.value = this.value.slice(0, -1); // Remove the last character if there's more than one dot
    }
  });

  document.querySelectorAll('.variation-price').forEach(function (priceInput) {
    priceInput.addEventListener('input', function (e) {
      this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters except dot
      if ((this.value.match(/\./g) || []).length > 1) {
        this.value = this.value.slice(0, -1); // Remove the last character if there's more than one dot
      }
    });
  });


  // Other Image Remove AJAX
  $(document).off('click', '.remove-image').on('click', '.remove-image', function () {
    var product_image_id = $(this).data('id');
    var $btn = $(this); // Reference to the button
    if (product_image_id) {
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="sr-only">Loading...</span>');
      // Send the image_id to the backend via AJAX for deletion
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/remove_product_image_process.php',

        data: {
          remove_image_id: [product_image_id]
        },
        success: function (response) {
          try {
            response = JSON.parse(response);

            if (response.success) {
              Toastify({
                text: 'Image removed successfully!',
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              // Remove the corresponding <div> from the UI
              $(this).closest('.image-container').remove(); // Fix: `this` needs to reference the current `remove-image` element
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
              text: "An error occurred while processing the image removal.",
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while removing image. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Hide spinner and re-enable the button after the request is complete
          $btn.prop('disabled', false).html('Remove');
        }
      });
    } else {
      // If no `image_id`, simply perform the deletion without AJAX
      $(this).closest('.image-container').remove();
      Toastify({
        text: 'Image removed successfully!',
        duration: 2000,
        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
      }).showToast();
    }
  });


  // Variation Remove AJAX
  $(document).off('click', '.remove-variation').on('click', '.remove-variation', function () {
    var variation_id = $(this).data('id');

    if (variation_id) {
      // Send the variation_id to the backend via AJAX for deletion
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/remove_varation_process.php',
        data: {
          remove_variation_id: [variation_id]
        },
        success: function (response) {
          try {
            response = JSON.parse(response);

            if (response.success) {
              Toastify({
                text: 'Variation removed successfully!',
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              // Remove the corresponding <div> from the UI
              $(this).closest('.form-row').remove(); // Fix: `this` needs to reference the current `remove-variation` element
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
              text: "An error occurred while processing the variation removal.",
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while removing variation. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        }
      });
    } else {
      // If no `variation_id`, simply perform the deletion without AJAX
      $(this).closest('.form-row').remove();
      Toastify({
        text: 'Variation removed successfully!',
        duration: 2000,
        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
      }).showToast();
    }
  });

  $(document).off('click', '.remove-color').on('click', '.remove-color', function () {
    var variation_color_id = $(this).data('id');

    if (variation_color_id) {
      // Send the variation_id to the backend via AJAX for deletion
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/remove_color_process.php',
        data: {
          remove_variation_id: [variation_color_id]
        },
        success: function (response) {
          try {
            response = JSON.parse(response);

            if (response.success) {
              Toastify({
                text: 'Color removed successfully!',
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              // Remove the corresponding <div> from the UI
              $(this).closest('.form-row').remove(); // Fix: `this` needs to reference the current `remove-variation` element
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
              text: "An error occurred while processing the variation removal.",
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
          Toastify({
            text: "Error occurred while removing variation. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        }
      });
    } else {
      // If no `variation_id`, simply perform the deletion without AJAX
      $(this).closest('.form-row').remove();
      Toastify({
        text: 'Variation removed successfully!',
        duration: 2000,
        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
      }).showToast();
    }
  });


  // Submit Button AJAX
  $(document).ready(function () {
    // Form submission handling
    $('#editProductModal form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/edit_product_process.php',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Let the browser set the content type for the FormData
        success: function (response) {
          try {
            response = JSON.parse(response); // Ensure response is fully parsed

            if (response.success) {
              Toastify({
                text: response.message,
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              $('#editProductModal').modal('hide');
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