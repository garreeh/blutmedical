<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Blut Medical | Forgot Password</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <!-- Custom fonts for this template -->
  <link href="./../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="./../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
  <br>
  <br>
  <br>
  <br>
  <br>

  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Forgot Password - Enter your email</h1>
                    <br>
                    <form class="user" id="resetPasswordForm" onsubmit="submitResetPasswordEmail(); return false;">
                      <div class="form-group">
                        <input type="email" class="form-control form-control-user" placeholder="Enter your email"
                          name="user_email" id="reset_user_email" required>
                      </div>

                      <button type="button" class="btn btn-primary btn-user btn-block"
                        onclick="submitResetPasswordEmail()">Send Temporary Password</button>
                      <hr>
                    </form>

                    </br>
                    <a class="small" href="./login.php">Already have an account? Login here</a>
                    </br>
                    <a class="small" href="./../index.php">Home Page</a>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="./../assets/admin/vendor/jquery/jquery.min.js"></script>
  <script src="./../assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="./../assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="./../assets/admin/js/sb-admin-2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <div id="loaderContainer" class="loader-container">
    <div class="loader"></div>
  </div>
</body>

</html>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('togglePassword').addEventListener('click', function() {
      var passwordInput = document.getElementById('user_password');
      var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      var icon = document.querySelector('#togglePassword i');
      icon.classList.toggle('fa-eye-slash');
    });
  });


  document.getElementById('registerForm').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      submitResetPasswordEmail();
    }
  });

  function showToast(message) {
    Toastify({
      text: message,
      duration: 3000,
      close: true,
      gravity: 'top',
      position: 'right',
      backgroundColor: 'red',
    }).showToast();
  }

  function submitResetPasswordEmail() {
    // Show loader
    document.getElementById('loaderContainer').style.display = 'flex';

    var email = document.getElementById('reset_user_email').value;

    var data = {
      user_email: email
    };

    $.ajax({
      type: 'POST',
      url: '../controllers/reset_password_process.php', // your backend script
      data: data,
      dataType: 'json',
      success: function(response) {
        document.getElementById('loaderContainer').style.display = 'none';
        if (response.success) {
          // Redirect to success page
          window.location.href = "./forgot_password_success.php";
        } else {
          showToast(response.message);
        }
      },
      error: function(xhr, status, error) {
        document.getElementById('loaderContainer').style.display = 'none';
        showToast('Error occurred while processing the request.');
      }
    });
  }
</script>

<style>
  #togglePassword {
    cursor: pointer;
  }

  .custom-form-container {
    border: 1px solid #ced4da;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: whitesmoke;
    padding: 20px;
    margin-top: 50px;
  }

  /* Custom style to make the toast red */
  #incorrectPasswordToast,
  #userNotFoundToast {
    position: fixed;
    background-color: #dc3545;
    color: #fff;
  }

  @media (max-width: 576px) {
    #passwordMismatchToast {
      width: 100%;
      right: 0;
      margin: 0;
      transform: none;
      top: 70px;
      /* Adjust as needed */
    }
  }

  /* Loader Styles */
  .loader-container {
    display: none;
    /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    /* Dimmed background */
    z-index: 9999;
    /* Make sure it stays on top */
    justify-content: center;
    align-items: center;
  }

  .loader {
    border: 8px solid #f3f3f3;
    /* Light grey */
    border-top: 8px solid #3498db;
    /* Blue */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>