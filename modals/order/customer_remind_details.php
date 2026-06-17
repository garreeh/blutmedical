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

if (isset($_POST['user_id'])) {

  $user_id = (int) $_POST['user_id'];

  /*
  |---------------------------------------------
  | QUERY (GROUP BY USER CART ITEMS)
  |---------------------------------------------
  */
  $sql = "
    SELECT
      cart.cart_id,
      cart.total_price,
      cart.user_id,
      product.product_name,
      variations.value,
      variations_colors.color
    FROM cart
    LEFT JOIN product ON cart.product_id = product.product_id
    LEFT JOIN variations ON cart.variation_id = variations.variation_id
    LEFT JOIN variations_colors ON cart.variation_color_id = variations_colors.variation_color_id
    WHERE cart.user_id = $user_id
      AND cart.cart_status = 'Cart'
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
              No cart items found for this user.
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
            Cart Details
            <span class="ref-badge">
              USER ID: <?php echo $user_id; ?>
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

          <div class="order-box" style="background:#e9f7ef;">
            <strong>Total:</strong>
            <span style="float:right;font-weight:bold;">
              ₱ <?php echo number_format($total, 2); ?>
            </span>
          </div>

        </div>

      </div>
    </div>
  </div>

<?php } ?>