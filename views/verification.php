<?php
include '../connections/connections.php';

session_start();

// Check if the email verification flag is set
if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
	header("Location: ../views/login.php");
	exit();
}

// Unset the session flag to prevent re-access
unset($_SESSION['email_verified']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Blut Medical | Registration Successful</title>

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
										<h1 class="h4 text-gray-900 mb-4">Registration Successful!</h1>
										<p class="mb-4">Thank you for registering. A verification email has been sent to your email address.
										</p>
										<p class="mb-4">Please verify your account to log in.</p>
									</div>
									<a href="./login.php" class="btn btn-primary btn-user btn-block">
										Back to Login Page
									</a>
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