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

if (isset($_POST['cart_id'])) {
  $cart_id = $_POST['cart_id'];
  $sql = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Ensure only the filename is used
      $proof_of_payment = basename($row['proof_of_payment']);
      $image_url = '../../uploads/payments/' . $proof_of_payment; // Construct the image URL
    ?>
  <div class="modal fade" id="showPhoto" tabindex="-1" role="dialog" aria-labelledby="showPhotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body text-center">
          <?php if (!empty($proof_of_payment) && file_exists('../../uploads/payments/' . $proof_of_payment)): ?>
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