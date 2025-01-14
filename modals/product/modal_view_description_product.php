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

if (isset($_POST['product_id'])) {
  $product_id = $_POST['product_id'];
  $sql = "SELECT * FROM product WHERE product_id = '$product_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
      <div class="modal fade" id="viewDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="viewDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Product Description</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body text-center">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <textarea class="form-control" id="product_description" name="product_description"
                    placeholder="Enter Product Description" rows="4" readonly><?php echo $row['product_description']; ?></textarea>
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