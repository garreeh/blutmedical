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

$sql = "SELECT * FROM subcategory";
$resultSubCategory = mysqli_query($conn, $sql);

$subcategory_names = [];
if ($resultSubCategory) {
  while ($row = mysqli_fetch_assoc($resultSubCategory)) {
    $subcategory_names[] = $row;
  }
}

if (isset($_POST['category_id'])) {
  $category_id = $_POST['category_id'];
  $sql = "SELECT * FROM category WHERE category_id = '$category_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

      $category_image = basename($row['category_image']);

      $image_url = '../../uploads/category/' . $category_image; // Construct the image URL

      ?>
      <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Category ID: <?php echo $row['category_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="category_name">Category Name:</label>
                    <input type="text" class="form-control" id="category_name" name="category_name"
                      placeholder="Enter Category Name" value="<?php echo $row['category_name']; ?>" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="subcategory_id">Shop Category:</label>
                    <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                      <option value="" disabled>Select Shop Category</option>
                      <?php
                      // Loop through category names to populate the dropdown
                      foreach ($subcategory_names as $subcategory_rows) {
                        // Set selected if the category_id matches
                        $selected = ($subcategory_rows['subcategory_id'] == $row['subcategory_id']) ? 'selected' : '';
                        echo "<option value='" . $subcategory_rows['subcategory_id'] . "' $selected>" . $subcategory_rows['subcategory_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="fileToUpload">Category Image:</label>
                    <input type="file" class="form-control" id="fileToUpload" name="fileToUpload">
                    <!-- Display existing image filename -->
                    <div class="file-info">
                      <?php if (!empty($category_image) && file_exists('../../uploads/category/' . $category_image)): ?>
                        <p><strong>Current Image:</strong> <?php echo $category_image; ?></p>
                      <?php else: ?>
                        <p>No image available.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_supplier" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveCategoryButton">Save</button>
                  <!-- <input type="hidden" name="item_id" value="</?php echo $row['category_id']; ?>"> -->
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
    $('#editCategoryModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var formData = new FormData(this); // Use FormData to include file inputs

      var $saveButton = $('#saveCategoryButton');
      $saveButton.text('Saving...');
      $saveButton.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/edit_category_process.php',
        data: formData,
        processData: false,  // Required for FormData
        contentType: false,  // Required for FormData
        success: function (response) {
          console.log(response);
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $('#editCategoryModal').modal('hide');
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
            text: "Error occurred while editing category. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          $saveButton.text('Save');
          $saveButton.prop('disabled', false);
        }
      });
    });
  });

</script>