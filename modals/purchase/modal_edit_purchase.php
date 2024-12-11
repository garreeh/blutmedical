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

if (isset($_POST['purchase_order_id'])) {
  $purchase_order_id = $_POST['purchase_order_id'];
  $sql = "SELECT * FROM purchase_order WHERE purchase_order_id = '$purchase_order_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="editPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update Purchase ID: <?php echo $row['purchase_order_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="purchase_order_id" value="<?php echo $row['purchase_order_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity"
                      value="<?php echo $row['quantity']; ?>" required>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_purchase" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save</button>
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
  // Save Button in Edit Purchase Order
  $(document).ready(function () {
    $('#editPurchaseModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission
      var $form = $(this);
      var $button = $form.find('button[type="submit"]'); // Reference to the submit button

      // Change button text to 'Saving...' and disable it
      $button.text('Saving...').prop('disabled', true);

      // Serialize form data
      var formData = $form.serialize();

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/edit_purchase_process.php',
        data: formData,
        success: function (response) {
          console.log(response);
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $('#editPurchaseModal').modal('hide');
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
            text: "Error occurred while editing purchase. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $button.text('Save').prop('disabled', false);
        }
      });
    });
  });
</script>