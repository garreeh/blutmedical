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
?>

<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="user_fullname">Fullname:</label>
              <input type="text" class="form-control" id="user_fullname" name="user_fullname"
                placeholder="Enter fullname" required>
            </div>

            <div class="form-group col-md-6">
              <label for="username">Username:</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username"
                required>
            </div>

            <div class="form-group col-md-6">
              <label for="user_address">Address:</label>
              <input type="text" class="form-control" id="user_address" name="user_address" placeholder="Enter Address"
                required>
            </div>

            <div class="form-group col-md-6">
              <label for="user_email">Email:</label>
              <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter Email"
                required>
            </div>

            <div class="form-group col-md-6">
              <label for="user_contact">Contact #:</label>
              <input type="text" class="form-control" id="user_contact" name="user_contact"
                placeholder="Enter Contact #" required>
            </div>

            <div class="form-group col-md-6">
              <label for="user_password">Password:</label>
              <input type="password" class="form-control" id="user_password" name="user_password"
                placeholder="Enter Password" required>
            </div>
          </div>

          <!-- User Type Dropdown -->
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="user_type_id">User Type:</label>
              <select class="form-control" id="user_type_id" name="user_type_id" required>
                <option value="">Select User Type</option>
                <?php foreach ($userTypes as $userType): ?>
                  <option value="<?php echo $userType['user_type_id']; ?>">
                    <?php echo $userType['user_type_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>


          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add</button>
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
    $('#addUserModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var $form = $(this);
      var $button = $form.find('button[type="submit"]'); // Reference to the submit button

      // Change button text to 'Adding...' and disable it
      $button.text('Adding...').prop('disabled', true);

      // Serialize form data
      var formData = $form.serialize();

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_user_process.php',
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

            $form.trigger('reset');
            $('#addUserModal').modal('hide');
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
            text: "Error occurred while adding user. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $button.text('Add').prop('disabled', false);
        }
      });
    });
  });
</script>