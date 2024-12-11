<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Order Summary Section -->
        <h2>Order Summary</h2>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Item</th>
              <th scope="col">Quantity</th>
              <th scope="col">Unit Price</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody id="order-summary">
            <!-- Dynamic cart items will be populated here -->
          </tbody>
        </table>
        <div class="text-right">
          <h1>Total: <span id="total-amount">₱ 0.00</span></h1>
        </div>
        <hr>

        <!-- Payment Section -->
        <form id="checkoutForm">
          <div class="form-group">
            <label for="payment-category">Select Payment Method:</label>
            <div>
              <label><input type="radio" name="paymentCategory" value="GCash"> Gcash</label>
              <label><input type="radio" name="paymentCategory" value="Cash On Delivery"> Cash on Delivery (COD)</label>
            </div>
          </div>
          <div class="form-group" id="proof-of-payment-field" style="display: none;">
            <label for="proofOfPayment">Upload Proof of Payment (GCash):</label>
            <input type="file" id="proofOfPayment" name="proofOfPayment" class="form-control-file" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="submitCheckout">Confirm Payment</button>
      </div>
    </div>
  </div>
</div>