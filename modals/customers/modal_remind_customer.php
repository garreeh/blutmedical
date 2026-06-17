<?php
include './../../connections/connections.php';

// Fetch user types from the database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

$user_names = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $user_names[] = $row;
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

<div class="modal fade" id="remindCustomerModal" tabindex="-1" role="dialog" aria-labelledby="remindCustomerModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="remindCustomerModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-group col-md-12">
            <h3>Do you want to <strong>Remind</strong> this <strong>Customer</strong>?</h3>

          </div>

          <!-- Add a hidden input field to submit the form with the button click -->
          <input type="hidden" name="cart_id" id="cart_id" value="">

          <input type="hidden" name="user_id" id="user_id" value="">


          <input type="hidden" name="remind_specific_customer" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="remindButton">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#remindCustomerModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      // Store a reference to $(this)
      var $form = $(this);

      // Serialize form data
      var formData = $form.serialize();

      // Change button text to "Adding..." and disable it
      var $addButton = $('#remindButton');
      $addButton.text('Reminding...');
      $addButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/remind_customer_process.php',
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

            // Optionally, reset the form
            $form.trigger('reset');

            // Optionally, close the modal
            $('#remindCustomerModal').modal('hide');
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
            text: "Error occurred while sending email. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $addButton.text('Yes');
          $addButton.prop('disabled', false);
        }
      });
    });
  });
</script>