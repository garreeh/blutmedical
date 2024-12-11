<?php
include './../../connections/connections.php';

// Fetch products with supplier names
$sql = "SELECT *
        FROM product 
        LEFT JOIN supplier ON product.supplier_id = supplier.supplier_id";
$resultProduct = mysqli_query($conn, $sql);
$products = [];
if ($resultProduct) {
  while ($row = mysqli_fetch_assoc($resultProduct)) {
    $products[] = $row;
  }
}
?>

<style>
  .modal-body label {
    color: #333;
    font-weight: bolder;
  }
</style>

<div class="modal fade" id="addPurchaseOrderModal" tabindex="-1" role="dialog"
  aria-labelledby="addPurchaseOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPurchaseOrderModalLabel">Add Purchase Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="purchase_number">Purchase Number:</label>
              <input type="number" class="form-control" id="purchase_number" name="purchase_number"
                placeholder="Enter Purchase Number" required>
            </div>
            <div class="form-group col-md-6">
              <label for="quantity">Quantity:</label>
              <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity"
                required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="product_id">Product:</label>
              <select class="form-control" id="product_id" name="product_id" onchange="inheritSupplierDetails()"
                required>
                <option value="">Select Product</option>
                <?php foreach ($products as $product): ?>
                  <option
                    value="<?php echo $product['product_id']; ?> - <?php echo $product['supplier_name']; ?> - <?php echo $product['supplier_id']; ?>">
                    <?php echo $product['product_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="supplier_name">Supplier:</label>
              <input type="text" class="form-control" id="supplier_name" name="supplier_name" readonly>
            </div>

            <div class="form-group col-md-6">
              <input type="hidden" class="form-control" id="supplier_id" name="supplier_id" readonly>
            </div>
          </div>

          <input type="hidden" name="add_purchase" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  function inheritSupplierDetails() {
    var selectedProduct = document.getElementById('product_id').value;
    console.log('Selected Product Value:', selectedProduct);

    var details = selectedProduct.split(' - ');
    console.log('Split Details Array:', details);

    var supplierName = details[1];
    var supplierId = details[2];

    document.getElementById('supplier_name').value = supplierName;
    document.getElementById('supplier_id').value = supplierId;

    console.log('Supplier Name:', supplierName);
    console.log('Supplier ID:', supplierId);
  }

  $(document).ready(function () {
    // Handle form submission
    $('#addPurchaseOrderModal form').on('submit', function (event) {
      event.preventDefault();

      var $form = $(this);
      var $submitButton = $form.find('button[type="submit"]');
      var originalButtonText = $submitButton.text();

      // Change button text to "Adding..."
      $submitButton.text('Adding...').prop('disabled', true);

      var formData = new FormData($form[0]);

      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_purchase_process.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          console.log(response);
          response = JSON.parse(response);
          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $form.trigger('reset');
            $('#addPurchaseOrderModal').modal('hide');
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
            text: "Error occurred while adding product. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Revert button text and re-enable it
          $submitButton.text(originalButtonText).prop('disabled', false);
        }
      });
    });
  });
</script>