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

if (isset($_POST['voucher_id'])) {
  $voucher_id = $_POST['voucher_id'];
  $sql = "SELECT * FROM voucher WHERE voucher_id = '$voucher_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Voucher Details ID: <?php echo $row['voucher_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="voucher_id" value="<?php echo $row['voucher_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="voucher_code">Voucher Code:</label>
                    <input type="text" class="form-control" id="voucher_code_edit" name="voucher_code"
                      placeholder="Enter Voucher Code" value="<?php echo $row['voucher_code']; ?>" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="voucher_percentage">Voucher Percentage:</label>

                    <div class="input-group">
                      <input type="text" class="form-control" id="voucher_percentage_edit" name="voucher_percentage"
                        placeholder="1 - 100" value="<?php echo $row['voucher_percentage']; ?>" autocomplete="off" required>

                      <div class="input-group-append">
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="voucher_status">Voucher Status:</label>

                    <select class="form-control" id="voucher_status" name="voucher_status" required>
                      <option value="Active" <?php if ($row['voucher_status'] == 'Active')
                        echo 'selected'; ?>>
                        🟢 Active
                      </option>

                      <option value="Inactive" <?php if ($row['voucher_status'] == 'Inactive')
                        echo 'selected'; ?>>
                        ⚪ Inactive
                      </option>
                    </select>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_voucher" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveButton">Update</button>
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
    $('#editDiscountModal form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/edit_voucher_process.php',
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
            $('#editDiscountModal').modal('hide');
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
            text: "Error occurred while editing voucher. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $saveButton.text('Update');
          $saveButton.prop('disabled', false);
        }
      });
    });
  });

  $(document).ready(function () {

    $('#voucher_code_edit').on('input', function () {

      let value = $(this).val().toUpperCase();
      value = value.replace(/[^A-Z0-9]/g, '');
      value = value.substring(0, 8);

      $(this).val(value);
    });

    $('#voucher_percentage_edit').on('keypress', function (e) {
      if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    });

    $('#voucher_percentage_edit').on('input', function () {
      let value = this.value.replace(/[^0-9]/g, ''); // Numbers only

      if (value === '') {
        this.value = '';
        return;
      }

      value = parseInt(value, 10);

      if (value > 100) {
        value = 100;
      }

      this.value = value;
    });
  });
</script>