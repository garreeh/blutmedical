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

  .currency-box {
    background: #e9f7ef;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
  }

  .currency-row {
    margin-bottom: 5px;
  }

  .currency-total {
    font-size: 18px;
    font-weight: bold;
  }
</style>

<?php

include './../../connections/connections.php';

if (isset($_POST['reference_no'])) {

  $reference_no = trim(
    mysqli_real_escape_string($conn, $_POST['reference_no'])
  );

  /*
   * Same value as reference_no.
   * This allows the query to also find PayPal orders.
   */
  $paypal_order_id = $reference_no;


  /*
   * ============================================================
   * GET USD TO PHP CONVERSION RATE
   * ============================================================
   *
   * currency.dollar_currecy
   * is assumed to contain the USD -> PHP conversion rate.
   *
   * Example:
   * dollar_currecy = 58.50
   *
   * $1 USD = ₱58.50
   */
  $currency_sql = "
    SELECT dollar_currency
    FROM currency
    LIMIT 1
";

  $currency_result = mysqli_query($conn, $currency_sql);

  if (!$currency_result) {
    die("Currency Query Error: " . mysqli_error($conn));
  }

  $currency_row = mysqli_fetch_assoc($currency_result);

  $dollar_currency = isset($currency_row['dollar_currency'])
    ? (float) $currency_row['dollar_currency']
    : 0;


  /*
   * ============================================================
   * GET ORDER ITEMS
   * ============================================================
   *
   * PRICE LOGIC:
   *
   * If variation exists:
   *      variations.price
   *
   * Otherwise:
   *      product.product_sellingprice
   *
   * Both prices are USD.
   */
  $sql = "
    SELECT

      cart.cart_id,
      cart.reference_no,
      cart.paypal_order_id,
      cart.product_id,
      cart.variation_id,

      product.product_name,
      product.product_sellingprice,

      variations.value,
      variations.price AS variation_price,

      variations_colors.color,

      voucher.voucher_percentage,

      /*
       * Use variation price when available.
       * Otherwise use product selling price.
       */
      CASE
        WHEN cart.variation_id IS NOT NULL
             AND variations.price IS NOT NULL
        THEN variations.price
        ELSE product.product_sellingprice
      END AS unit_price_usd,

      /*
       * cart_quantity
       */
      cart.cart_quantity

    FROM cart

    LEFT JOIN product
      ON cart.product_id = product.product_id

    LEFT JOIN variations
      ON cart.variation_id = variations.variation_id
      AND cart.product_id = variations.product_id

    LEFT JOIN variations_colors
      ON cart.variation_color_id = variations_colors.variation_color_id

    LEFT JOIN voucher
      ON voucher.voucher_id = cart.voucher_id

    WHERE TRIM(cart.reference_no) = '$reference_no'
       OR TRIM(cart.paypal_order_id) = '$paypal_order_id'

    ORDER BY cart.cart_id ASC
  ";

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Query Error: " . mysqli_error($conn));
  }


  /*
   * ============================================================
   * NO ORDER FOUND
   * ============================================================
   */
  if (mysqli_num_rows($result) == 0) {

    echo '
    <div class="modal fade" id="showPhoto">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-body">

            <div class="alert alert-warning">
              No order found for:
              <strong>' . htmlspecialchars($reference_no) . '</strong>
            </div>

          </div>

        </div>
      </div>
    </div>';

    exit;
  }


  /*
   * ============================================================
   * VARIABLES
   * ============================================================
   */
  $subtotal_usd = 0;
  $voucher_percentage = 0;


  /*
   * ============================================================
   * DISPLAY ORDER ITEMS
   * ============================================================
   */
  ?>

  <div class="modal fade" id="showPhoto" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

      <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header">

          <h5 class="modal-title">

            Order Details

            <span class="ref-badge">
              REF:
              <?php echo htmlspecialchars($reference_no); ?>
            </span>

          </h5>

          <button type="button" class="close" data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>


        <!-- BODY -->
        <div class="modal-body">

          <?php

          while ($row = mysqli_fetch_assoc($result)) {

            /*
             * ==================================================
             * GET cart_quantity
             * ==================================================
             */
            $cart_quantity = isset($row['cart_quantity'])
              ? (float) $row['cart_quantity']
              : 1;


            /*
             * ==================================================
             * GET UNIT PRICE
             * ==================================================
             */
            $unit_price_usd = (float) $row['unit_price_usd'];


            /*
             * ==================================================
             * COMPUTE ITEM SUBTOTAL
             * ==================================================
             */
            $item_subtotal_usd =
              $unit_price_usd * $cart_quantity;


            /*
             * Add item subtotal to order subtotal
             */
            $subtotal_usd += $item_subtotal_usd;


            /*
             * Get voucher percentage
             */
            if (
              isset($row['voucher_percentage']) &&
              (float) $row['voucher_percentage'] > $voucher_percentage
            ) {

              $voucher_percentage =
                (float) $row['voucher_percentage'];
            }


            /*
             * ==================================================
             * PHP CONVERSION
             * ==================================================
             */
            $item_subtotal_php =
              $item_subtotal_usd * $dollar_currency;

            ?>

            <div class="order-box">

              <!-- PRODUCT -->
              <div class="form-group">

                <label>Product Name</label>

                <input type="text" class="form-control" value="<?php
                echo htmlspecialchars(
                  $row['product_name'] ?: '-'
                );
                ?>" readonly>

              </div>


              <!-- VARIATION -->
              <div class="form-group">

                <label>Variation</label>

                <input type="text" class="form-control" value="<?php
                echo htmlspecialchars(
                  $row['value'] ?: 'No Variation'
                );
                ?>" readonly>

              </div>


              <!-- COLOR -->
              <div class="form-group">

                <label>Color</label>

                <input type="text" class="form-control" value="<?php
                echo htmlspecialchars(
                  $row['color'] ?: 'No Color'
                );
                ?>" readonly>

              </div>


              <!-- cart_quantity -->
              <div class="form-group">

                <label>Cart QTY</label>

                <input type="text" class="form-control" value="<?php
                echo number_format($cart_quantity, 0);
                ?>" readonly>

              </div>


              <!-- UNIT PRICE USD -->
              <div class="form-group">

                <label>Unit Price (USD)</label>

                <input type="text" class="form-control" value="$ <?php
                echo number_format(
                  $unit_price_usd,
                  2
                );
                ?>" readonly>

              </div>


              <!-- ITEM SUBTOTAL USD -->
              <div class="form-group">

                <label>Item Subtotal (USD)</label>

                <input type="text" class="form-control" value="$ <?php
                echo number_format(
                  $item_subtotal_usd,
                  2
                );
                ?>" readonly>

              </div>


              <!-- ITEM SUBTOTAL PHP -->
              <div class="form-group">

                <label>Item Subtotal (PHP)</label>

                <input type="text" class="form-control" value="₱ <?php
                echo number_format(
                  $item_subtotal_php,
                  2
                );
                ?>" readonly>

              </div>

            </div>

          <?php } ?>


          <?php

          /*
           * ====================================================
           * ORDER TOTAL COMPUTATION
           * ====================================================
           */

          /*
           * Discount in USD
           */
          $discount_usd =
            ($subtotal_usd * $voucher_percentage) / 100;


          /*
           * Final USD total
           */
          $final_total_usd =
            $subtotal_usd - $discount_usd;


          /*
           * Convert to PHP
           */
          $subtotal_php =
            $subtotal_usd * $dollar_currency;

          $discount_php =
            $discount_usd * $dollar_currency;

          $final_total_php =
            $final_total_usd * $dollar_currency;

          ?>


          <!-- =================================================
               CURRENCY SUMMARY
               ================================================= -->

          <div class="currency-box">

            <div class="currency-row">

              <strong>Exchange Rate:</strong>

              <span style="float:right;">
                $1 =
                ₱ <?php
                echo number_format(
                  $dollar_currency,
                  2
                );
                ?>
              </span>

            </div>


            <hr>


            <!-- USD SUBTOTAL -->
            <div class="currency-row">

              <strong>Subtotal (USD):</strong>

              <span style="float:right;">
                $
                <?php
                echo number_format(
                  $subtotal_usd,
                  2
                );
                ?>
              </span>

            </div>


            <!-- PHP SUBTOTAL -->
            <div class="currency-row">

              <strong>Subtotal (PHP):</strong>

              <span style="float:right;">
                ₱
                <?php
                echo number_format(
                  $subtotal_php,
                  2
                );
                ?>
              </span>

            </div>


            <?php if ($voucher_percentage > 0): ?>

              <div style="
                  color:#28a745;
                  font-size:13px;
                  margin-top:8px;
                ">

                Discount
                (<?php
                echo number_format(
                  $voucher_percentage,
                  2
                );
                ?>% OFF):

                <span style="float:right;">

                  -$
                  <?php
                  echo number_format(
                    $discount_usd,
                    2
                  );
                  ?>

                </span>

              </div>


              <div style="
                  color:#28a745;
                  font-size:13px;
                  margin-top:5px;
                ">

                Discount (PHP):

                <span style="float:right;">

                  -₱
                  <?php
                  echo number_format(
                    $discount_php,
                    2
                  );
                  ?>

                </span>

              </div>

            <?php endif; ?>


            <hr>


            <!-- FINAL USD -->
            <div class="currency-row currency-total">

              <strong>Total (USD):</strong>

              <span style="float:right;">

                $
                <?php
                echo number_format(
                  $final_total_usd,
                  2
                );
                ?>

              </span>

            </div>


            <!-- FINAL PHP -->
            <div class="currency-row currency-total" style="margin-top:8px;">

              <strong>Total (PHP):</strong>

              <span style="float:right;">

                ₱
                <?php
                echo number_format(
                  $final_total_php,
                  2
                );
                ?>

              </span>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>


  <?php

}

?>