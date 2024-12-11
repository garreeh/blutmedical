<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333; /* Darker label color */
    font-weight: bolder;
  }
</style>

<?php
include './../../connections/connections.php';

if (isset($_POST['supplier_id'])) {
  $supplier_id = $_POST['supplier_id'];
  $sql = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
  <div class="modal fade" id="fetchDataSupplierDetailsModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Supplier Details ID: <?php echo $row['supplier_id']; ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Enter Supplier Name" value="<?php echo $row['supplier_name']; ?>" readonly required>
              </div>
              <div class="form-group col-md-4">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="<?php echo $row['address']; ?>" readonly required>
              </div>
              <div class="form-group col-md-4">
                <label for="landline">Landline:</label>
                <input type="text" class="form-control" id="landline" name="landline" placeholder="Enter Landline" value="<?php echo $row['landline']; ?>" readonly required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="mobile">Mobile:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile" value="<?php echo $row['mobile_number']; ?>" readonly required>
              </div>
              <div class="form-group col-md-4">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo $row['email']; ?>" readonly required>
              </div>
              <div class="form-group col-md-4">
                <label for="tin">TIN:</label>
                <input type="text" class="form-control" id="tin" name="tin" placeholder="Enter TIN" value="<?php echo $row['tin']; ?>" readonly required>
              </div>
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