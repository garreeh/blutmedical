<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <?php include 'assets.php'; ?>
  <title>Blüt Medical | Contact us</title>
</head>

<body>

  <?php

  include './includes/navigation.php';
  include './connections/connections.php';

  ?>

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Contact</h1>
            <p class="mb-4">We are a provider of innovative premium quality products that will elevate any medical
              practice be it for veterinarians or human doctors.</p>
            <p><a href="products.php" class="btn btn-secondary me-2">Shop Now</a></p>
          </div>
        </div>
        <div class="col-lg-5 d-none d-md-block">
          <div class="hero-img-wrap">
            <img src="assets/logo/blutfront.png" class="img-fluid" style="max-width: 75%;">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->


  <!-- Start Contact Form -->
  <div class="untree_co-section">
    <div class="container">

      <div class="block">
        <div class="row justify-content-center">


          <div class="col-md-8 col-lg-8 pb-4">


            <div class="row mb-5">
              <div class="col-lg-4">
                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left"
                  data-aos-delay="0">
                  <div class="service-icon color-1 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                      <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                    </svg>
                  </div> <!-- /.icon -->
                  <div class="service-contents">
                    <p>107A Kalayaan avenue, Diliman, Quezon City, 1101 Metro Manila</p>
                  </div> <!-- /.service-contents-->
                </div> <!-- /.service -->
              </div>

              <div class="col-lg-4">
                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left"
                  data-aos-delay="0">
                  <div class="service-icon color-1 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-envelope-fill" viewBox="0 0 16 16">
                      <path
                        d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z" />
                    </svg>
                  </div> <!-- /.icon -->
                  <div class="service-contents">
                    <p>admin@blutmedical.com</p>
                  </div> <!-- /.service-contents-->
                </div> <!-- /.service -->
              </div>

              <div class="col-lg-4">
                <div class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left"
                  data-aos-delay="0">
                  <div class="service-icon color-1 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-telephone-fill" viewBox="0 0 16 16">
                      <path fill-rule="evenodd"
                        d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                    </svg>
                  </div> <!-- /.icon -->
                  <div class="service-contents">
                    <p>+639264753651</p>
                  </div> <!-- /.service-contents-->
                </div> <!-- /.service -->
              </div>
            </div>

            <form id="contactForm">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="text-black" for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="text-black" for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="text-black" for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <div class="form-group">
                <label class="text-black" for="contact">Contact #</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
              </div>

              <div class="form-group mb-5">
                <label class="text-black" for="message">Message</label>
                <textarea class="form-control" id="message" name="message" cols="30" rows="5" required></textarea>
              </div>

              <button type="submit" class="btn btn-primary-hover-outline" id="sendEmailMessage">Send Message</button>
            </form>


          </div>

        </div>

      </div>

    </div>


  </div>
  </div>

  <!-- End Contact Form -->



  <?php

  include './includes/footer.php';

  ?>

</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
  document.getElementById('contact').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9.]/g, ''); // Allow only numbers and dots
  });


  $(document).ready(function () {
    $('#contactForm').submit(function (event) {
      event.preventDefault(); // Prevent default form submission (important!)

      // Serialize form data
      var formData = $(this).serialize();

      // Change button text to "Sending..." and disable it
      var $sendButton = $('#sendEmailMessage');
      $sendButton.text('Sending...');
      $sendButton.prop('disabled', true);

      // Send AJAX request
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/send_email_process.php',
        data: formData,
        success: function (response) {
          // Parse JSON response
          try {
            response = JSON.parse(response);
            if (response.success) {
              Toastify({
                text: response.message,
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
              }).showToast();

              // Reset the form
              $('#contactForm').trigger('reset');
            } else {
              Toastify({
                text: response.message,
                duration: 2000,
                backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
              }).showToast();
            }
          } catch (e) {
            console.error("Invalid JSON response:", response);
            Toastify({
              text: "An error occurred. Please try again.",
              duration: 2000,
              backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
            }).showToast();
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX error:", xhr.responseText);
          Toastify({
            text: "An error occurred while processing your request. Please try again later.",
            duration: 2000,
            backgroundColor: "linear-gradient(to right, #ff6a00, #ee0979)"
          }).showToast();
        },
        complete: function () {
          // Reset button text and re-enable it
          $sendButton.text('Send Message');
          $sendButton.prop('disabled', false);
        }
      });
    });
  });
</script>