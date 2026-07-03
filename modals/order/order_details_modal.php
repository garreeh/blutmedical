<style>
  .modal-body label {
    color: #333;
    font-weight: bolder;
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
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
  }
</style>

<?php
include './../../connections/connections.php';

if (isset($_POST['reference_no'])) {

  $reference_no = trim(mysqli_real_escape_string($conn, $_POST['reference_no']));
  $paypal_order_id = $reference_no; // ✅ SAME VALUE (YOUR REQUEST)

  $sql = "
    SELECT
    
      cart.total_price,
      cart.reference_no,
      cart.paypal_order_id,
      product.product_name,
      variations.value,
      variations_colors.color,
      voucher.voucher_percentage

    FROM cart
    LEFT JOIN product ON cart.product_id = product.product_id
    LEFT JOIN variations ON cart.variation_id = variations.variation_id
    LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
    LEFT JOIN voucher ON voucher.voucher_id = cart.voucher_id
    WHERE TRIM(cart.reference_no) = '$reference_no'
       OR TRIM(cart.paypal_order_id) = '$paypal_order_id'
    ORDER BY cart.cart_id ASC

  ";

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Query Error: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) == 0) {
    echo '
    <div class="modal fade" id="showPhoto">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <div class="alert alert-warning">
              No order found for:
              <strong>' . $reference_no . '</strong>
            </div>
          </div>
        </div>
      </div>
    </div>';
    exit;
  }

  $total = 0;
  ?>

  <div class="modal fade" id="showPhoto" tabindex="-1" role="dialog">
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

            $total += (float) $row['total_price'];
            ?>

            <div class="order-box">

              <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" value="<?php echo $row['product_name'] ?: '-'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Variation</label>
                <input type="text" class="form-control" value="<?php echo $row['value'] ?: 'No Variation'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Color</label>
                <input type="text" class="form-control" value="<?php echo $row['color'] ?: 'No Color'; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Price</label>
                <input type="text" class="form-control" value="₱ <?php echo number_format($row['total_price'], 2); ?>"
                  readonly>
              </div>

            </div>

          <?php } ?>

          <div class="order-box" style="background:#e9f7ef; padding:10px;">

            <?php

            $total_query = "
                          SELECT
                              SUM(cart.total_price) AS subtotal,
                              MAX(voucher.voucher_percentage) AS voucher_percentage
                          FROM cart
                          LEFT JOIN voucher ON voucher.voucher_id = cart.voucher_id
                          WHERE TRIM(cart.reference_no) = '$reference_no'
                            OR TRIM(cart.paypal_order_id) = '$paypal_order_id'
                          ";

            $total_result = mysqli_query($conn, $total_query);
            $total_row = mysqli_fetch_assoc($total_result);

            $total = $total_row['subtotal'] ?? 0;
            $voucher = $total_row['voucher_percentage'] ?? 0;

            $discount_amount = ($total * $voucher) / 100;
            $final_total = $total - $discount_amount;

            ?>

            <div>
              <strong>Subtotal:</strong>
              <span style="float:right;">
                ₱ <?php echo number_format($total, 2); ?>
              </span>
            </div>

            <?php if ($voucher > 0): ?>
              <div style="color:#28a745; font-size:13px; margin-top:5px;">
                Discount (<?php echo $voucher; ?>% OFF):
                <span style="float:right;">
                  -₱ <?php echo number_format($discount_amount, 2); ?>
                </span>
              </div>
            <?php endif; ?>

            <hr>

            <div>
              <strong>Total:</strong>
              <span style="float:right; font-weight:bold;">
                ₱ <?php echo number_format($final_total, 2); ?>
              </span>
            </div>

          </div>

        </div>

      </div>
    </div>
  </div>

<?php } ?>