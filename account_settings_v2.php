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

  <?php

  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $user_id = $_SESSION['user_id'];

  $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    while ($edit_user = mysqli_fetch_assoc($result)) {
  ?>
      <!-- Start Contact Form -->
      <div class="untree_co-section">
        <div class="container">

          <div class="block">
            <div class="row justify-content-center">


              <div class="col-md-8 col-lg-8 pb-4">
                <form id="contactForm">

                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label class="text-black" for="user_fullname">Full Name</label>
                        <input type="text" class="form-control" id="user_fullname_<?php echo $user_id; ?>" name="user_fullname" value="<?php echo $edit_user['user_fullname'] ?>" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="text-black" for="user_address">Address</label>
                    <input type="text" class="form-control" id="user_address_<?php echo $user_id; ?>" name="user_address" value="<?php echo $edit_user['user_address'] ?>" readonly>
                  </div>

                  <div class="form-group">
                    <label class="text-black" for="user_email">Email Address</label>
                    <input type="email" class="form-control" id="user_email_<?php echo $user_id; ?>" name="user_email" value="<?php echo $edit_user['user_email'] ?>" readonly>
                  </div>

                  <div class="form-group">
                    <label class="text-black" for="user_contact">Contact #</label>
                    <input type="text" class="form-control" id="user_contact_<?php echo $user_id; ?>" name="user_contact" value="<?php echo $edit_user['user_contact'] ?>" readonly>
                  </div>

                  <div class="form-group">
                    <label class="text-black" for="user_confirm_password">Password</label>
                    <input type="password" class="form-control" id="user_confirm_password_<?php echo $user_id; ?>" name="user_confirm_password" value="<?php echo $edit_user['user_confirm_password'] ?>" readonly>
                  </div>

                  <br>

                  <!-- <button type="submit" class="btn btn-primary-hover-outline" id="sendEmailMessage">Edit Details</button> -->

                  <?php
                  include './modal_change_password.php';
                  ?>


                  <!-- Update button -->
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <a href="#"
                        class="btn btn-sm btn-success shadow-sm mb-4"
                        data-toggle="modal" data-target="#fetchDataClientDetailsModal"
                        data-client-id="<?php echo $edit_user['user_id']; ?>">
                        Click to Update
                      </a>

                    </div>
                  </div>
                </form>


              </div>

            </div>

          </div>

        </div>


      </div>
      </div>

      <!-- End Contact Form -->
  <?php
    }
  }
  ?>


  <?php

  include './includes/footer.php';

  ?>

</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>