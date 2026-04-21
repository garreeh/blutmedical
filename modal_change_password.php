<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<?php
include './connections/connections.php';

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id']; // ← GET is not neede

  $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
      <div class="modal fade" id="fetchDataClientDetailsModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">User Details ID: <?php echo $row['user_id']; ?></h5>

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <form id="clientDetailsForm" method="post" enctype="multipart/form-data">

                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="user_fullname">Full Name:</label>
                    <input type="text" class="form-control" id="user_fullname" name="user_fullname"
                      placeholder="Enter Fullname" value="<?php echo $row['user_fullname']; ?>" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="user_address">Address:</label>
                    <input type="text" class="form-control" id="user_address" name="user_address" placeholder="Enter Address"
                      value="<?php echo $row['user_address']; ?>" required>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="user_contact">Contact #:</label>
                    <input type="text" class="form-control" id="user_contact" name="user_contact"
                      placeholder="Enter Mobile Number" value="<?php echo $row['user_contact']; ?>" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="user_email">Email:</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter Email"
                      value="<?php echo $row['user_email']; ?>" required>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="user_confirm_password">Password:</label>
                    <input type="password" class="form-control" id="user_confirm_password" name="user_confirm_password"
                      value="<?php echo $row['user_confirm_password']; ?>" required>
                  </div>
                </div>
                <input type="hidden" name="edit_customers" value="1">

                <br>

                <div class="form-row">
                  <div class="col-md-12 text-center">

                    <!-- <button type="button" class="btn btn-primary" id="saveUpdateButton">Save Update</button> -->
                    <button type="submit" id="submitClientDetails" class="btn btn-primary">Save Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                  </div>
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
        $(document).ready(function() {
          // Handle form submission with AJAX for a specific form
          $('#contactForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Store the button and show "Saving..." while processing
            var $submitButton = $('#submitClientDetails');
            $submitButton.text('Saving...').prop('disabled', true);

            // Create a FormData object
            var formData = new FormData(this);

            // AJAX request
            $.ajax({
              type: 'POST',
              url: '/blutmedical/controllers/users/edit_acc_process.php',

              data: formData,
              contentType: false,
              processData: false,
              success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                  updateClientDetails(data.users); // Update the UI with the new client data
                  Toastify({
                    text: data.message,
                    duration: 2000,
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                  }).showToast();

                  $('#fetchDataClientDetailsModal').modal('hide');

                } else {
                  Toastify({
                    text: data.message,
                    duration: 3000,
                    backgroundColor: "linear-gradient(to right, #FF3D00, #FF9A00)"
                  }).showToast();
                }
              },

              error: function(jqXHR, textStatus, errorThrown) {
                console.error(jqXHR.responseText);
                Toastify({
                  text: "Error occurred while editing client details. Please try again later.",
                  duration: 2000,
                  backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
                }).showToast();
              },
              complete: function() {
                // Revert the button back to "Save Update" and re-enable it after the request completes
                $submitButton.text('Save Update').prop('disabled', false);
              }
            });
          });
        });

        // Function to update the client details on the main page
        function updateClientDetails(users) {
          console.log(users); // <-- see the full object
          console.log('User ID:', users.user_id); // log specific property

          document.getElementById(`user_fullname_${users.user_id}`).value = users.user_fullname;
          document.getElementById(`user_address_${users.user_id}`).value = users.user_address;
          document.getElementById(`user_email_${users.user_id}`).value = users.user_email;
          document.getElementById(`user_contact_${users.user_id}`).value = users.user_contact;
          document.getElementById(`user_confirm_password_${users.user_id}`).value = users.user_confirm_password;
        }
      </script>

<?php
    }
  }
}
?>