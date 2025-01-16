<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title" id="checkoutModalLabel">Checkout</p>
      </div>
      <div class="modal-body">
        <!-- Order Summary Section -->
        <h5>Order Summary</h5>
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
          <h3>Total: <span id="total-amount">₱ 0.00</span></h3>
        </div>
        <hr>

        <!-- Payment Section -->
        <form id="checkoutForm">
          <div class="form-group">
            <label for="payment-category">Select Payment Method:</label>
            <div>
              <label><input type="radio" name="paymentCategory" value="Paypal"> Paypal</label>
              <label><input type="radio" name="paymentCategory" value="Cash on Delivery"> Cash on Delivery (COD)</label>
            </div>
          </div>

          <div id="guest-details-section" style="display: none;">
            <hr>

            <p style="margin-top: 10px; font-size: 12px; color: #555;">
              Note: The PayPal button will remain disabled until all the below fields are filled in.
            </p>

            <div class="form-group col-md-12" style="margin-bottom: 0.5rem;">
              <input type="text" class="form-control" id="delivery_guest_fullname" name="delivery_guest_fullname"
                placeholder="Enter Fullname">
            </div>

            <div class="form-group col-md-12" style="margin-bottom: 0.5rem;">
              <input type="text" class="form-control" id="delivery_address" name="delivery_address"
                placeholder="Enter Full Address">
            </div>

            <div class="form-group col-md-12" style="margin-bottom: 0.5rem;">
              <input type="text" class="form-control" id="delivery_guest_contact_number"
                name="delivery_guest_contact_number" placeholder="Enter Contact Number">
            </div>

            <div class="form-group col-md-12" style="margin-bottom: 0.5rem;">
              <input type="email" class="form-control" id="delivery_guest_email" name="delivery_guest_email"
                placeholder="Enter Email">
            </div>
          </div>
          <div id="paypal-button-container" style="display: none;"></div>



        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-sm py-1" id="submitCheckout">Confirm Payment</button>
        <button type="button" class="btn btn-secondary btn-sm py-1" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

<!-- <script src="https://www.paypal.com/sdk/js?client-id=AYwMIA4BQ3ThhTRprUJQMbfrjA4ZyiXwaMh5mZ28cKJAo_wngfye9Bsq1JK4SbJhuWxn0MNx6iynWRzR&currency=PHP"></script> -->

<script
  src="https://www.paypal.com/sdk/js?client-id=AfcJOedIT9WM3IBgUd8D4uEiAXppkMsftrR2DRtcm8CUco5sptEShId2hujHrtNd_FK7gzOyzbV53zsX"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $('#checkoutModal').on('hidden.bs.modal', function () {
    console.log('Modal is fully hidden now');
  });
  const userId = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
  // Toggle guest details section
  if (!userId) {
    // Show guest details for guests
    $('#guest-details-section').show();

    // Require guest details fields
    $('#delivery_guest_fullname, #delivery_address, #delivery_guest_contact_number, #delivery_guest_email').prop(
      'required',
      true
    );
  } else {
    // Hide guest details for logged-in users
    $('#guest-details-section').hide();

    // Remove 'required' attribute for guest details fields
    $('#delivery_guest_fullname, #delivery_address, #delivery_guest_contact_number, #delivery_guest_email').prop(
      'required',
      false
    );
  }

  if (!userId) {
    $('#submitCheckout').on('click', function (e) {
      e.preventDefault();
      var $button = $(this); // Cache the button element

      // Show 'Saving...' when the button is clicked
      $button.text('Saving...'); // Change button text
      // Get selected payment category
      const paymentCategory = $('input[name="paymentCategory"]:checked').val();

      // Determine the URL based on payment category
      let url;
      if (paymentCategory === 'Cash on Delivery') {
        url = '/blutmedical/controllers/users/checkout_guest_process.php';
      } else {
        Toastify({
          text: 'Please select a payment method.',
          duration: 3000,
          gravity: 'top',
          position: 'right',
          backgroundColor: '#f44336', // Red for error
        }).showToast();
        $button.text('Confirm Payment');

        return; // Exit if no payment method is selected
      }

      // Retrieve localStorage contents
      const cartData = JSON.parse(localStorage.getItem('guestCart')) || [];
      let localStorageItems = [];

      cartData.forEach(item => {
        localStorageItems.push({
          product_id: item.product_id,
          cart_quantity: item.cart_quantity,
          variation_id: item.variation_id,
          product_sellingprice: item.product_sellingprice,
          price: item.price,

        });
      });

      // Gather form data
      const formData = {
        payment_category: paymentCategory,
        localStorageItems: localStorageItems
      };

      // If user is a guest, add guest details to form data
      if (!userId) {
        formData.fullname = $('#delivery_guest_fullname').val();
        formData.address = $('#delivery_address').val();
        formData.contact_number = $('#delivery_guest_contact_number').val();
        formData.email = $('#delivery_guest_email').val();

        // Validate guest details
        if (!formData.fullname || !formData.address || !formData.contact_number || !formData.email) {
          Toastify({
            text: 'Please fill out all guest details.',
            duration: 3000,
            gravity: 'top',
            position: 'right',
            backgroundColor: '#f44336', // Red for error
          }).showToast();
          $button.text('Confirm Payment');

          return;
        }
      }
      $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify(formData), // JSON.stringify encodes the formData into a string
        contentType: 'application/json', // Set header to send JSON
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            Toastify({
              text: response.message,
              duration: 3000,
              gravity: 'top',
              position: 'right',
              backgroundColor: '#4CAF50', // Green for success
            }).showToast();

            setTimeout(function () {
              updateCart();
              updateCartBadge();
            }, 500); // Give the DOM time to update

            $('#checkoutModal').modal('hide');
            // Optionally, clear the localStorage after successful checkout
            localStorage.removeItem('guestCart');
          } else {
            Toastify({
              text: response.message || 'An error occurred.',
              duration: 3000,
              gravity: 'top',
              position: 'right',
              backgroundColor: '#f44336', // Red for error
            }).showToast();
          }
          $button.text('Confirm Payment');
        },
        error: function (xhr, status, error) {
          console.error('XHR Status:', status); // Log the status
          console.error('Error:', error); // Log the actual error
          console.error('Server Response:', xhr.responseText); // Log the server response text

          Toastify({
            text: 'Something went wrong. Please try again.',
            duration: 3000,
            gravity: 'top',
            position: 'right',
            backgroundColor: '#f44336', // Red for error
          }).showToast();

          $button.text('Confirm Payment');
        },

      });
    });
  } else {
    $('#submitCheckout').click(function (e) {
      e.preventDefault(); // Prevent default form submission
      // Serialize form data
      var formData = new FormData($('#checkoutForm')[0]);

      const paymentCategory = $('input[name="paymentCategory"]:checked').val();

      let url;
      if (paymentCategory === 'Cash on Delivery') {
        url = '/blutmedical/controllers/users/checkout_process.php';
      } else {
        Toastify({
          text: 'Please select a payment method.',
          duration: 3000,
          gravity: 'top',
          position: 'right',
          backgroundColor: '#f44336', // Red for error
        }).showToast();
        return; // Exit if no payment method is selected
      }

      // Send the form data via AJAX
      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (typeof response === 'string') {
            try {
              response = JSON.parse(response);
            } catch (e) {
              console.error("Response is not valid JSON:", response);
              Toastify({
                text: "Invalid response format.",
                duration: 3000,
                backgroundColor: "#dc3545" // Red for error
              }).showToast();
              return;
            }
          }

          if (response.success) {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" // Green for success
            }).showToast();

            // Close modal and reset form
            $('#checkoutModal').modal('hide');
            $('.modal-backdrop').remove();
            $('#checkoutForm').trigger('reset');
            $('#proof-of-payment-field').hide();

            // Refresh the cart
            updateCart();
            updateCartBadge();

          } else {
            Toastify({
              text: response.message,
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)" // Red for error
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error);
          Toastify({
            text: "An error occurred while processing your request.",
            duration: 3000,
            backgroundColor: "#dc3545", // Red for error
            close: true
          }).showToast();
        }
      });
    });
  }

  if (!userId) {
    function checkFormFields() {
      const fullname = $('#delivery_guest_fullname').val();
      const address = $('#delivery_address').val();
      const contactNumber = $('#delivery_guest_contact_number').val();
      const email = $('#delivery_guest_email').val();

      // Enable/Disable the submit button based on field values
      if (fullname && address && contactNumber && email) {
        $('#paypal-button-container').css('pointer-events', 'auto'); // Enable PayPal container clicks
        $('#paypal-button-container').css('opacity', '1'); // Reset opacity
      } else {
        $('#paypal-button-container').css('pointer-events', 'none'); // Disable PayPal container clicks
        $('#paypal-button-container').css('opacity', '0.5'); // Optional: to visually indicate it's disabled
      }
    }

    // Monitor input fields for changes
    $('#delivery_guest_fullname, #delivery_address, #delivery_guest_contact_number, #delivery_guest_email').on('input', function () {
      checkFormFields(); // Recheck fields whenever user inputs something
    });

    // Initial check in case the form is pre-filled or already has data
    checkFormFields();
  }

  //Paypal Checkout here do not remove
  $('input[name="paymentCategory"]').on('change', function () {
    const selectedPayment = $(this).val();

    if (selectedPayment === 'Paypal') {
      $('#paypal-button-container').show(); // Show PayPal button container



      $('#submitCheckout').hide(); // Hide the Confirm Payment button
      renderPayPalButton(); // Render the PayPal button
    } else {
      $('#paypal-button-container').hide(); // Hide PayPal button container
      $('#submitCheckout').show(); // Hide the Confirm Payment button

    }
  });

  if (!userId) {
    function renderPayPalButton() {
      $('#paypal-button-container').empty(); // Clear existing button to avoid duplicates

      paypal.Buttons({
        createOrder: function (data, actions) {
          // Retrieve and process cart data from localStorage
          const cartData = JSON.parse(localStorage.getItem('guestCart')) || [];
          let totalAmount = 0; // Initialize totalAmount to 0
          let localStorageItems = [];

          cartData.forEach(item => {
            const price = item.variation_id === '-' ?
              parseFloat(item.product_sellingprice) :
              parseFloat(item.price);
            totalAmount += price * item.cart_quantity;

            localStorageItems.push({
              product_id: item.product_id,
              cart_quantity: item.cart_quantity,
              variation_id: item.variation_id,
              product_sellingprice: item.product_sellingprice,
              price: item.price,
            });
          });

          return actions.order.create({
            purchase_units: [{
              amount: {
                value: totalAmount.toFixed(2) // Convert total to 2 decimal places
              }
            }]
          });
        },

        onApprove: function (data, actions) {
          return actions.order.capture().then(function (details) {
            console.log('Transaction completed: ', details);
            const cartData = JSON.parse(localStorage.getItem('guestCart')) || [];
            let totalAmount = 0; // Initialize totalAmount to 0
            let localStorageItems = [];

            cartData.forEach(item => {
              const price = item.variation_id === '-' ?
                parseFloat(item.product_sellingprice) :
                parseFloat(item.price);
              totalAmount += price * item.cart_quantity;

              localStorageItems.push({
                product_id: item.product_id,
                cart_quantity: item.cart_quantity,
                variation_id: item.variation_id,
                product_sellingprice: item.product_sellingprice,
                price: item.price,
              });
            });

            // Prepare formData
            const formData = {
              localStorageItems: localStorageItems,
              totalAmount: totalAmount.toFixed(2),
              orderID: data.orderID,
              payerID: details.payer.payer_id,
              paypalPayerName: details.payer.name.given_name + ' ' + details.payer.name.surname,
              paypalPayerEmail: details.payer.email_address,
              paypalPayerContact: details.payer.phone ? details.payer.phone.phone_number.national_number : null,
              paypalPayerAddress: details.payer.address ? details.payer.address.address_line_1 + ', ' + details.payer.address.admin_area_2 + ', ' + details.payer.address.country_code : null,
              transaction_id: details.id // PayPal transaction ID
            };

            if (!userId) {
              formData.fullname = $('#delivery_guest_fullname').val();
              formData.address = $('#delivery_address').val();
              formData.contact_number = $('#delivery_guest_contact_number').val();
              formData.email = $('#delivery_guest_email').val();
            }

            $.ajax({
              type: 'POST',
              url: '/blutmedical/controllers/users/checkout_guest_paypal_process.php',
              data: JSON.stringify(formData), // Send the correct formData here
              contentType: 'application/json', // Set header to send JSON
              dataType: 'json',
              success: function (response) {
                if (response.status === 'success') {
                  Toastify({
                    text: response.message,
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#4CAF50', // Green for success
                  }).showToast();

                  setTimeout(function () {
                    updateCart();
                    updateCartBadge();
                  }, 500); // Give the DOM time to update

                  $('#checkoutModal').modal('hide');
                  // Optionally, clear the localStorage after successful checkout
                  localStorage.removeItem('guestCart');
                } else {
                  Toastify({
                    text: response.message || 'An error occurred.',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#f44336', // Red for error
                  }).showToast();
                }
              },
              error: function (xhr, status, error) {
                console.error('XHR Status:', status); // Log the status
                console.error('Error:', error); // Log the actual error
                console.error('Server Response:', xhr.responseText); // Log the server response text

                Toastify({
                  text: 'Something went wrong. Please try again.',
                  duration: 3000,
                  gravity: 'top',
                  position: 'right',
                  backgroundColor: '#f44336', // Red for error
                }).showToast();

                $button.text('Confirm Payment');
              },
            });
          });
        }
      }).render('#paypal-button-container'); // Render the PayPal button
    }
  }
  else {
    function renderPayPalButton() {
      $('#paypal-button-container').empty(); // Clear existing button to avoid duplicates

      paypal.Buttons({
        createOrder: function (data, actions) {
          return $.ajax({
            url: '/blutmedical/controllers/users/fetch_cart_process.php',
            method: 'GET',
            dataType: 'json',
          }).then(function (response) {
            if (response.success) {
              let totalAmount = 0;

              // Calculate total amount based on cart data
              response.items.forEach(function (item) {
                const price = item.variation_id ? parseFloat(item.price) : parseFloat(item.product_sellingprice);
                totalAmount += price * parseInt(item.cart_quantity, 10);
              });

              // Create the PayPal order
              return actions.order.create({
                purchase_units: [{
                  amount: {
                    value: totalAmount.toFixed(2), // Convert total to 2 decimal places
                  },
                }],
              });
            } else {
              console.error('Failed to fetch cart items:', response.message || 'Unknown error');
              return Promise.reject(new Error('Failed to fetch cart data'));
            }
          }).catch(function (error) {
            console.error('AJAX error while fetching cart data:', error);
            return Promise.reject(new Error('Failed to fetch cart data'));
          });
        },


        onApprove: function (data, actions) {
          return actions.order.capture().then(function (details) {
            // Serialize form data
            var formData = new FormData($('#checkoutForm')[0]);


            // Send the form data via AJAX
            $.ajax({
              type: 'POST',
              url: '/blutmedical/controllers/users/checkout_paypal_process.php',
              data: formData,
              contentType: false,
              processData: false,
              success: function (response) {
                if (typeof response === 'string') {
                  try {
                    response = JSON.parse(response);
                  } catch (e) {
                    console.error("Response is not valid JSON:", response);
                    Toastify({
                      text: "Invalid response format.",
                      duration: 3000,
                      backgroundColor: "#dc3545" // Red for error
                    }).showToast();
                    return;
                  }
                }

                if (response.success) {
                  Toastify({
                    text: response.message,
                    duration: 2000,
                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)" // Green for success
                  }).showToast();

                  // Close modal and reset form
                  $('#checkoutModal').modal('hide');
                  $('.modal-backdrop').remove();
                  $('#checkoutForm').trigger('reset');
                  $('#proof-of-payment-field').hide();

                  // Refresh the cart
                  updateCart();
                  updateCartBadge();

                } else {
                  Toastify({
                    text: response.message,
                    duration: 2000,
                    backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)" // Red for error
                  }).showToast();
                }
              },
              error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                Toastify({
                  text: "An error occurred while processing your request.",
                  duration: 3000,
                  backgroundColor: "#dc3545", // Red for error
                  close: true
                }).showToast();
              }
            });
          });
        }
      }).render('#paypal-button-container'); // Render the PayPal button
    }
  }

</script>