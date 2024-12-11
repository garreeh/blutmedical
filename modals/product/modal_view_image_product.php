<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333; /* Darker label color */
    font-weight: bolder;
  }
  .modal-body img {
    max-width: 100%; /* Ensure the image fits within the modal */
    height: auto;
    max-height: 300px; /* Limit the image height */
    object-fit: contain; /* Maintain aspect ratio */
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
      // Ensure only the filename is used
      $product_image = basename($row['product_image']);
      $image_url = '../../uploads/' . $product_image; // Construct the image URL
    ?>
  <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body text-center">
          <?php if (!empty($product_image) && file_exists('../../uploads/' . $product_image)): ?>
            <img src="<?php echo $image_url; ?>" alt="Product Image">
          <?php else: ?>
            <p>No image available.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

<?php 
    }
  }
}
?>