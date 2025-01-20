<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }

  .modal-body img {
    max-width: 100%;
    /* Ensure the image fits within the modal */
    height: auto;
    max-height: 300px;
    /* Limit the image height */
    object-fit: contain;
    /* Maintain aspect ratio */
  }
</style>

<?php
include './../../connections/connections.php';

if (isset($_POST['cart_id'])) {
  $cart_id = $_POST['cart_id'];
  $sql = "SELECT * FROM cart
          LEFT JOIN users ON users.user_id = cart.user_id
           WHERE cart_id = '$cart_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $fullname = isset($row['user_fullname']) ? $row['user_fullname'] : $row['delivery_guest_fullname'];
      $address = isset($row['user_address']) ? $row['user_address'] : $row['delivery_address'];
      $contact = isset($row['user_contact']) ? $row['user_contact'] : $row['delivery_guest_contact_number'];
      $email = isset($row['user_email']) ? $row['user_email'] : $row['delivery_guest_email'];
      ?>
      <div class="modal fade" id="showPhoto" tabindex="-1" role="dialog" aria-labelledby="showPhotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Customer Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="customer_fullname">Customer Fullname:</label>
                  <input type="text" class="form-control" id="customer_fullname" name="customer_fullname"
                    value="<?php echo $fullname; ?>" readonly>
                </div>
                <div class="form-group col-md-12">
                  <label for="customer_address">Customer Address:</label>
                  <input type="text" class="form-control" id="customer_address" name="customer_address"
                    value="<?php echo $address; ?>" readonly>
                </div>
                <div class="form-group col-md-12">
                  <label for="customer_contact_no">Customer Contact #:</label>
                  <input type="text" class="form-control" id="customer_contact_no" name="customer_contact_no"
                    value="<?php echo $contact; ?>" readonly>
                </div>
                <div class="form-group col-md-12">
                  <label for="customer_email">Customer Email Address:</label>
                  <input type="text" class="form-control" id="customer_email" name="customer_email"
                    value="<?php echo $email; ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
    }
  }
}
?>