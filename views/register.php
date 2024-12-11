<?php
include '../connections/connections.php';


if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (isset($_SESSION['user_id'])) {
	if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == "1") {
		// If the user is an admin, redirect to the admin dashboard
		header("Location: /blutmedical/views/admin/dashboard.php");
	} else {
		// If the user is not an admin, redirect to the user dashboard
		header("Location: /blutmedical/views/user/product_showcase.php");
	}
	exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Sterling | Register</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<!-- Custom fonts for this template-->
	<link href="./../assets/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="./../assets/admin/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

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
										<h1 class="h4 text-gray-900 mb-4">Account Registration</h1>
									</div>

									<form class="user" id="registerForm" onsubmit="submitForm(); return false;">
										<div class="form-group">
											<input type="text" class="form-control form-control-user" placeholder="Fullname"
												name="user_fullname" id="user_fullname" required>
										</div>
										<div class="form-group">
											<input type="text" class="form-control form-control-user" placeholder="Username" name="username"
												id="username" required>
										</div>
										<div class="form-group">
											<input type="email" class="form-control form-control-user" placeholder="Email" name="user_email"
												id="user_email" required>
										</div>
										<div class="form-group">
											<input type="text" class="form-control form-control-user" placeholder="Address"
												name="user_address" id="user_address" required>
										</div>
										<div class="form-group">
											<input type="text" class="form-control form-control-user" placeholder="Contact"
												name="user_contact" id="user_contact" required>
										</div>
										<div class="form-group">
											<div class="input-group">
												<input type="password" class="form-control form-control-user" placeholder="Password"
													name="user_password" id="user_password" required>
												<div class="input-group-append">
													<span class="input-group-text" id="togglePassword">
														<i class="fa fa-eye" aria-hidden="true"></i>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<input type="password" class="form-control form-control-user" placeholder="Confirm Password"
												name="user_confirm_password" id="user_confirm_password" required>
										</div>

										<button type="button" class="btn btn-primary btn-user btn-block"
											onclick="submitForm()">Register</button>
										<hr>
									</form>

									<div class="text-center">
										<a class="small" href="./login.php">Already have an account? Login here</a>
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
	document.addEventListener('DOMContentLoaded', function () {
		document.getElementById('togglePassword').addEventListener('click', function () {
			var passwordInput = document.getElementById('user_password');
			var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
			passwordInput.setAttribute('type', type);
			var icon = document.querySelector('#togglePassword i');
			icon.classList.toggle('fa-eye-slash');
		});
	});


	document.getElementById('registerForm').addEventListener('keydown', function (e) {
		if (e.key === 'Enter') {
			submitForm();
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

	function submitForm() {
		// Show the loader
		document.getElementById('loaderContainer').style.display = 'flex';

		// Get form data
		var fullname = document.getElementById('user_fullname').value;
		var username = document.getElementById('username').value;
		var email = document.getElementById('user_email').value;
		var contact = document.getElementById('user_contact').value;
		var user_address = document.getElementById('user_address').value;
		var password = document.getElementById('user_password').value;
		var confirm_password = document.getElementById('user_confirm_password').value;


		// Check if passwords match
		if (password !== confirm_password) {
			showToast("Passwords do not match.");
			document.getElementById('loaderContainer').style.display = 'none'; // Hide the loader if validation fails
			return;
		}

		// Create data object
		var data = {
			user_fullname: fullname,
			username: username,
			user_email: email,
			user_contact: contact,
			user_address: user_address,
			user_password: password,
			user_confirm_password: confirm_password

		};

		$.ajax({
			type: 'POST',
			url: '../controllers/register_process.php',
			data: data,
			dataType: 'json',
			success: function (response) {
				console.log(response);
				document.getElementById('loaderContainer').style.display = 'none'; // Hide the loader when request completes
				if (response.success) {
					window.location.href = "./verification.php"; // Redirect to verification page after successful registration
				} else {
					showToast(response.message);
				}
			},
			error: function (xhr, status, error) {
				document.getElementById('loaderContainer').style.display = 'none'; // Hide the loader when request completes
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