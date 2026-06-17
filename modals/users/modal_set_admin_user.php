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
      <div class="modal fade" id="fetchDataUserModalSetAdmin" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-l" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Set Admin Customer:
                <?php echo $row['user_fullname']; ?>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="form-row">

                <div class="form-group col-md-12">
                    <label for="is_admin">Administrative Access:</label>
                    <select class="form-control" id="is_admin" name="is_admin" required>
                        <option value="1" <?php echo ($row['is_admin'] == 1) ? 'selected' : ''; ?>>
                            Yes
                        </option>
                        <option value="0" <?php echo ($row['is_admin'] == 0) ? 'selected' : ''; ?>>
                            No
                        </option>
                    </select>
                    <small class="form-text text-muted">
                        Grant this user administrative privileges.
                    </small>
                </div>

                <div class="form-group col-md-12" id="userTypeContainer"
                    style="<?php echo ($row['is_admin'] == 1) ? '' : 'display:none;'; ?>">
                    <label for="user_type_id">Administrator Role:</label>
                    <select class="form-control" id="user_type_id" name="user_type_id">
                        <option value="">Select Administrator Role</option>
                        <?php foreach ($userTypes as $userType): ?>
                            <option value="<?php echo $userType['user_type_id']; ?>"
                                <?php echo ($userType['user_type_id'] == $row['user_type_id']) ? 'selected' : ''; ?>>
                                <?php echo $userType['user_type_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                </div>

                <!-- Add a hidden input field to submit the form with the button click -->
                <input type="hidden" name="set_admin_user" value="1">

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
    $('#fetchDataUserModalSetAdmin form').submit(function (event) {
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
        url: '/blutmedical/controllers/admin/edit_set_admin_process.php',
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

            $('#fetchDataUserModalSetAdmin').modal('hide');
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

  $(document).ready(function () {
      const isAdmin = $('#is_admin');
      const userTypeContainer = $('#userTypeContainer');
      const userType = $('#user_type_id');

      console.log('isAdmin found:', isAdmin.length);
      console.log('userTypeContainer found:', userTypeContainer.length);
      console.log('userType found:', userType.length);

      function toggleUserType() {
          console.log('Current value:', isAdmin.val());

          if (isAdmin.val() == '1') {
              console.log('SHOW');
              userTypeContainer.show();
              userType.prop('required', true);
          } else {
              console.log('HIDE');
              userTypeContainer.hide();
              userType.prop('required', false);
              userType.val('');
          }
      }
      toggleUserType();
      isAdmin.on('change', function () {
          console.log('Changed:', $(this).val());
          toggleUserType();
      });

  });
</script>