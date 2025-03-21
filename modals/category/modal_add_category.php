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
if ($result) {
  while ($row = mysqli_fetch_assoc($resultSubCategory)) {
    $subcategory_names[] = $row;
  }
}

?>
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="category_name">Category Name:</label>
              <input type="text" class="form-control" id="category_name" name="category_name"
                placeholder="Enter Supplier Name" required>
            </div>
            <div class="form-group col-md-12">
              <label for="subcategory_id">Shop Category:</label>
              <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                <option value="">Select Shop Category</option>
                <?php foreach ($subcategory_names as $subcategory_rows): ?>
                  <option value="<?php echo $subcategory_rows['subcategory_id']; ?>">
                    <?php echo $subcategory_rows['subcategory_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group col-md-12">
              <label for="fileToUpload">Category Image:</label>
              <input type="file" class="form-control" id="fileToUpload" name="fileToUpload">
            </div>
          </div>



          <!-- Add a hidden input field to submit the form with the button click -->
          <input type="hidden" name="add_category" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="addCategoryButton">Add</button>
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
    $('#addCategoryModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var $form = $(this);
      var formData = new FormData(this); // Use FormData to handle file uploads

      var $addButton = $('#addCategoryButton');
      $addButton.text('Adding...');
      $addButton.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_category_process.php',
        data: formData,
        contentType: false,  // Important: Prevent jQuery from setting content type
        processData: false,  // Important: Prevent jQuery from converting data
        success: function (response) {
          console.log(response); // Debugging
          response = JSON.parse(response);

          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $form.trigger('reset');
            $('#addCategoryModal').modal('hide');
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
            text: "Error occurred while adding category. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          $addButton.text('Add');
          $addButton.prop('disabled', false);
        }
      });
    });
  });

</script>