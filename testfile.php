<style>
  .modal-body label {
    color: #333;
    font-weight: bolder;
  }

  .modal-body img {
    max-width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
  }

  .order-box {
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 10px;
    background: #fafafa;
  }

  .ref-badge {
    background: #17a2b8;
    color: white;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 12px;
    margin-left: 10px;
  }
</style>

<?php
include './../../connections/connections.php';

if (isset($_POST['cart_id'])) {

  $cart_id = $_POST['cart_id'];

  /*
  |--------------------------------------------------------------------------
  | GET REFERENCE NO
  |--------------------------------------------------------------------------
  */
  $refQuery = mysqli_query($conn, "
      SELECT reference_no 
      FROM cart 
      WHERE cart_id = '$cart_id'
      LIMIT 1
  ");

  $refRow = mysqli_fetch_assoc($refQuery);
  $reference_no = $refRow['reference_no'];

  $sql = "
    SELECT 
      cart.total_price,
      cart.reference_no,
      product.product_name,
      variations.value,
      variations_colors.color
    FROM cart
    LEFT JOIN product ON cart.product_id = product.product_id
    LEFT JOIN variations ON cart.variation_id = variations.variation_id
    LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
    WHERE cart.reference_no = '$reference_no'
  ";

  $result = mysqli_query($conn, $sql);

  $total = 0;
  ?>

  <div class="modal fade" id="showPhoto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">
            Order Details
            <span class="ref-badge">
              REF: <?php echo htmlspecialchars($reference_no); ?>
            </span>
          </h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <?php while ($row = mysqli_fetch_assoc($result)) {

            $total += $row['total_price'];
            ?>

            <div class="order-box">


              <div class="form-group">
                <label>Product Name:</label>
                <input type="text" class="form-control" value="<?php echo $row['product_name'] ?: '-'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Variation:</label>
                <input type="text" class="form-control" value="<?php echo $row['value'] ?: 'No Variation'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Color:</label>
                <input type="text" class="form-control" value="<?php echo $row['color'] ?: 'No Color'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Price:</label>
                <input type="text" class="form-control" value="₱ <?php echo number_format($row['total_price'], 2); ?>"
                  readonly>
              </div>

            </div>

          <?php } ?>

          <!-- TOTAL -->
          <div class="order-box" style="background:#e9f7ef;">
            <strong>Total Order Amount:</strong>
            <span style="float:right; font-weight:bold;">
              ₱ <?php echo number_format($total, 2); ?>
            </span>
          </div>

        </div>

      </div>
    </div>
  </div>

<?php } ?>