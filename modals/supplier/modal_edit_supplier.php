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

if (isset($_POST['supplier_id'])) {
  $supplier_id = $_POST['supplier_id'];
  $sql = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="fetchDataSupplierModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Supplier Details ID: <?php echo $row['supplier_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="supplier_name">Supplier Name:</label>
                    <input type="text" class="form-control" id="supplier_name" name="supplier_name"
                      placeholder="Enter Supplier Name" value="<?php echo $row['supplier_name']; ?>" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address"
                      value="<?php echo $row['address']; ?>">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="landline">Landline:</label>
                    <input type="text" class="form-control" id="landline" name="landline" placeholder="Enter Landline"
                      value="<?php echo $row['landline']; ?>">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="mobile">Mobile:</label>
                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile"
                      value="<?php echo $row['mobile_number']; ?>">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                      value="<?php echo $row['email']; ?>">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="tin">TIN:</label>
                    <input type="text" class="form-control" id="tin" name="tin" placeholder="Enter TIN"
                      value="<?php echo $row['tin']; ?>">
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_supplier" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
                  <!-- <input type="hidden" name="item_id" value="</?php echo $row['supplier_id']; ?>"> -->
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
  // Save Button in Edit Supplier
  $(document).ready(function () {
    $('#fetchDataSupplierModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission
      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = $form.serialize();

      // Change button text to "Saving..." and disable it
      var $saveButton = $('#saveButton');
      $saveButton.text('Saving...');
      $saveButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/edit_supplier_process.php',
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
            $('#fetchDataSupplierModal').modal('hide');
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
            text: "Error occurred while editing supplier. Please try again later.",
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