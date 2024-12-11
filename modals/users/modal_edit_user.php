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

// Fetch user types from the database
$sql = "SELECT * FROM usertype";
$result = mysqli_query($conn, $sql);

$userTypes = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $userTypes[] = $row;
  }
}

if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
  $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="modal fade" id="fetchDataUserModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Update User Details ID: <?php echo $row['user_id']; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="user_fullname">Fullname:</label>
                    <input type="text" class="form-control" id="user_fullname" name="user_fullname"
                      placeholder="Enter fullname" value="<?php echo $row['user_fullname']; ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username"
                      value="<?php echo $row['username']; ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="user_address">Address:</label>
                    <input type="text" class="form-control" id="user_address" name="user_address" placeholder="Enter Address"
                      value="<?php echo $row['user_address']; ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="user_email">Email:</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter Email"
                      value="<?php echo $row['user_email']; ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="user_contact">Contact #:</label>
                    <input type="text" class="form-control" id="user_contact" name="user_contact"
                      placeholder="Enter Contact #" value="<?php echo $row['user_contact']; ?>" required>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="user_password">Password:</label>
                    <input type="password" class="form-control" id="user_confirm_password" name="user_confirm_password"
                      placeholder="Enter Password" value="<?php echo $row['user_confirm_password']; ?>" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="user_type_id">User Type:</label>
                    <select class="form-control" id="user_type_id" name="user_type_id" required>
                      <option value="">Select User Type</option>
                      <?php foreach ($userTypes as $userType): ?>
                        <option value="<?php echo $userType['user_type_id']; ?>" <?php echo ($userType['user_type_id'] == $row['user_type_id']) ? 'selected' : ''; ?>>
                          <?php echo $userType['user_type_name']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="edit_user" value="1">

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Save</button>
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
    $('#fetchDataUserModal form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/edit_user_process.php',
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

            $('#fetchDataUserModal').modal('hide');
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
            text: "Error occurred while editing user. Please try again later.",
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