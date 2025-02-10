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

if (isset($_POST['user_type_id'])) {
  $user_type_id = $_POST['user_type_id'];
  $sql = "SELECT * FROM usertype WHERE user_type_id = '$user_type_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="fetchDataUsertypeModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update User Type Details ID: <?php echo $row['user_type_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_type_id" value="<?php echo $row['user_type_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="user_type_name">User Type Name:</label>
                    <input type="text" class="form-control" id="user_type_name" name="user_type_name"
                      placeholder="Enter User Type" value="<?php echo $row['user_type_name']; ?>" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>Client Order</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-7">
                            <label for="ship_order">Ship Orders:</label>
                          </div>
                          <div class="col-5">
                            <input type="checkbox" id="ship_order" name="ship_order" value="1" <?php echo $row['ship_order'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>
                        <div class="row">
                          <div class="col-7">
                            <label for="view_order">View Orders:</label>
                          </div>
                          <div class="col-5">
                            <input type="checkbox" id="view_order" name="view_order" value="1" <?php echo $row['view_order'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>

                        <div class="row">
                          <div class="col-7">
                            <label for="client_order_module">Client Order Module:</label>
                          </div>
                          <div class="col-5">
                            <input type="checkbox" id="client_order_module" name="client_order_module" value="1" <?php echo $row['client_order_module'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>
                      </div>
                    </div>
                  </div>

                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>Shipped Order:</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-8">
                            <label for="complete_order">Complete Order:</label>
                          </div>
                          <div class="col-4">
                            <input type="checkbox" id="complete_order" name="complete_order" value="1" <?php echo $row['complete_order'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>

                        <div class="row">
                          <div class="col-8">
                            <label for="view_shipped_order">View Shipped Order:</label>
                          </div>
                          <div class="col-4">
                            <input type="checkbox" id="view_shipped_order" name="view_shipped_order" value="1" <?php echo $row['view_shipped_order'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>

                        <div class="row">
                          <div class="col-8">
                            <label for="shipped_order_module">Shipped Order Module:</label>
                          </div>
                          <div class="col-4">
                            <input type="checkbox" id="shipped_order_module" name="shipped_order_module" value="1" <?php echo $row['shipped_order_module'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>Transactions:</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-9">
                            <label for="view_transaction_module">View Transaction Module:</label>
                          </div>
                          <div class="col-3">
                            <input type="checkbox" id="view_transaction_module" name="view_transaction_module" value="1" <?php echo $row['view_transaction_module'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="form-row">
                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>Reports:</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-9">
                            <label for="sales_report_module">Sales Report Module:</label>
                          </div>
                          <div class="col-3">
                            <input type="checkbox" id="sales_report_module" name="sales_report_module" value="1" <?php echo $row['sales_report_module'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>
                      </div>
                    </div>
                  </div>

                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>Product Setup:</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-8">
                            <label for="product_setup_module">Product Setup Module:</label>
                          </div>
                          <div class="col-3">
                            <input type="checkbox" id="product_setup_module" name="product_setup_module" value="1" <?php echo $row['product_setup_module'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>

                      </div>
                    </div>
                  </div>

                  <div class="card col-md-4 form-group">
                    <div class="card-body">
                      <div class="form-group">
                        <h5><strong>User Setup:</strong></h5>
                        <hr>
                        <div class="row">
                          <div class="col-8">
                            <label for="user_setup">User Setup Module:</label>
                          </div>
                          <div class="col-3">
                            <input type="checkbox" id="user_setup" name="user_setup" value="1" <?php echo $row['user_setup'] == 1 ? 'checked' : ''; ?>>
                          </div>
                        </div>
                        <div style="margin-bottom: 5px;"></div>

                      </div>
                    </div>
                  </div>
                </div>






                <input type="hidden" name="edit_user_type" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
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
  // Save Button in Edit User Type
  $(document).ready(function () {
    $('#fetchDataUsertypeModal form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/edit_usertype_process.php',
        data: formData,
        success: function (response) {
          console.log(response); // Log the response for debugging
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            // Optionally, close the modal
            $('#fetchDataUsertypeModal').modal('hide');
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
            text: "Error occurred while editing user type. Please try again later.",
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