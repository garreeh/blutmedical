<style>
  /* Custom CSS for label color */
  .modal-body label {
    color: #333;
    /* Darker label color */
    font-weight: bolder;
  }
</style>

<div class="modal fade" id="addDiscountModal" tabindex="-1" role="dialog" aria-labelledby="addDiscountModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-l" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addDiscountModalLabel">Add Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">

          <div class="form-row">

            <!-- Voucher Code -->
            <div class="form-group col-md-12">
              <label for="voucher_code">Voucher Code:</label>
              <input type="text" class="form-control" id="voucher_code" name="voucher_code"
                placeholder="Enter Voucher Code" maxlength="8" autocomplete="off" required>
            </div>

            <!-- Voucher Percentage -->
            <div class="form-group col-md-12">
              <label for="voucher_percentage">Voucher Percentage:</label>

              <div class="input-group">
                <input type="text" class="form-control" id="voucher_percentage" name="voucher_percentage"
                  placeholder="1 - 100" autocomplete="off" required>

                <div class="input-group-append">
                  <span class="input-group-text">%</span>
                </div>
              </div>
            </div>

          </div>

          <input type="hidden" name="add_discount" value="1">

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="addDiscountButton">
              Add
            </button>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              Close
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  $(document).ready(function () {
    $('#addDiscountModal form').submit(function (event) {
      event.preventDefault(); // Prevent default form submission

      var $form = $(this);
      var formData = new FormData(this); // Use FormData to handle file uploads

      var $addButton = $('#addDiscountButton');
      $addButton.text('Adding...');
      $addButton.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/admin/add_discount_process.php',
        data: formData,
        contentType: false,  // Important: Prevent jQuery from setting content type
        processData: false,  // Important: Prevent jQuery from converting data
        success: function (response) {
          console.log(response); // Debugging
          response = JSON.parse(response);

          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
            }).showToast();

            $form.trigger('reset');
            $('#addDiscountModal').modal('hide');
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
            text: "Error occurred while adding discount. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          $addButton.text('Add');
          $addButton.prop('disabled', false);
        }
      });
    });
  });


  $(document).ready(function () {

    $('#voucher_code').on('input', function () {

      let value = $(this).val().toUpperCase();
      value = value.replace(/[^A-Z0-9]/g, '');
      value = value.substring(0, 8);

      $(this).val(value);
    });

    $('#voucher_percentage').on('keypress', function (e) {
      if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
      }
    });

    $('#voucher_percentage').on('input', function () {
      let value = this.value.replace(/[^0-9]/g, ''); // Numbers only

      if (value === '') {
        this.value = '';
        return;
      }

      value = parseInt(value, 10);

      if (value > 100) {
        value = 100;
      }

      this.value = value;
    });
  });
</script>