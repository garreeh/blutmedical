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

if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];
  $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Ensure only the filename is used
      $user_confirm_password = basename($row['user_confirm_password']);
    ?>
  <div class="modal fade" id="fetchDataUserModal" tabindex="-1" role="dialog" aria-labelledby="fetchDataUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Customer Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body text-center">

        <form method="post" enctype="multipart/form-data">

          <div class="form-group col-md-12">
            <label for="user_confirm_password">Password:</label>
            <input type="text" class="form-control" id="user_confirm_password" name="user_confirm_password" value="<?php echo $row['user_confirm_password']; ?>" readonly>
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