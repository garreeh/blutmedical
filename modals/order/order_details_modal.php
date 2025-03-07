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
          LEFT JOIN product ON cart.product_id = product.product_id
          LEFT JOIN variations ON cart.variation_id = variations.variation_id
				  LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
           WHERE cart_id = '$cart_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
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
                  <label for="customer_fullname">Product Code:</label>
                  <input type="text" class="form-control" id="customer_fullname" name="customer_fullname"
                    value="<?php echo !empty($row['product_code']) ? htmlspecialchars($row['product_code']) : '-'; ?>"
                    readonly>

                </div>
                <div class="form-group col-md-12">
                  <label for="customer_address">Product Name:</label>
                  <input type="text" class="form-control" id="customer_fullname" name="customer_fullname"
                    value="<?php echo !empty($row['product_name']) ? htmlspecialchars($row['product_name']) : '-'; ?>"
                    readonly>

                </div>
                <div class="form-group col-md-12">
                  <label for="customer_contact_no">Variation:</label>
                  <input type="text" class="form-control" id="customer_fullname" name="customer_fullname"
                    value="<?php echo !empty($row['value']) ? htmlspecialchars($row['value']) : 'No Variation'; ?>" readonly>
                </div>
                <div class="form-group col-md-12">
                  <label for="customer_email">Color:</label>
                  <input type="text" class="form-control" id="customer_fullname" name="customer_fullname"
                    value="<?php echo !empty($row['color']) ? htmlspecialchars($row['color']) : 'No Color'; ?>" readonly>
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