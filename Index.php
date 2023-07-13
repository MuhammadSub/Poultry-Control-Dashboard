<?php
	include('configure/config.php'); // get configuration file
	session_start();

	if (isset($_POST['email']) && isset($_POST['password'])) {
	  $email = $_POST['email'];
	  $password = $_POST['password'];

	  $stmt = $mysqli->prepare("SELECT id, email, is_admin FROM users WHERE email=? AND password=?"); // SQL to log in user
	  $stmt->bind_param('ss', $email, $password); // bind fetched parameters
	  $stmt->execute(); // execute bind
	  $stmt->bind_result($user_id, $user_email, $is_admin); // bind result
	  $rs = $stmt->fetch();

	  if ($rs) { // if login is successful
		  $_SESSION['user_id'] = $user_id; // assign session to user id

		  if ($is_admin) {
		      header("Location: admin_dashboard.php"); // redirect to admin dashboard
		      exit();
		  } 
		  else {
			  header("Location: user_dashboard.php?user_id=$user_id"); // redirect to user dashboard
			  exit();
		  }
	  } 
		else {
			$err = "Access Denied. Please Check Your Credentials";
		}
	}
?>


<!--End Login-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Control Poultry Farm</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta content="" name="description" />
		<meta content="" name="MartDevelopers" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!-- App favicon -->
		<link rel="shortcut icon" href="assets/images/favicon.ico">

		<!-- App css -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
		<!--Load Sweet Alert Javascript-->

		<script src="assets/js/swal.js"></script>
		<!--Inject SWAL-->
		<?php if(isset($success)) {?>
		<!--This code for injecting an alert-->
		<script>
			setTimeout(function () { 
				swal("Success","<?php echo $success;?>","success");
			}, 100);
		</script>
		<?php } ?>

		<?php if(isset($err)) {?>
		<!--This code for injecting an alert-->
		<script>
			setTimeout(function () { 
				swal("Failed","<?php echo $err;?>","Failed");
			}, 100);
		</script>
		<?php } ?>
	</head>
	<body class="authentication-bg authentication-bg-pattern" style="background-image: url('assets/images/bg1.jpg'); background-size: cover; background-repeat: no-repeat;"
		<div class="account-pages mt-5 mb-5">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6 col-xl-5">
						<div class="card bg-pattern">
							<div class="card-body p-4">
								<div class="text-center w-75 m-auto">
									<a href="index.php">
										<span><img src="assets/images/logo-dark.png" alt="" height="22"></span>
									</a>
									<p class="text-muted mb-4 mt-3">Enter your email address and password to access the admin and user panel.</p>
								</div>
								<form method="post">
									<div class="form-group mb-3">
										<label for="emailaddress">Email address</label>
										<input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
									</div>
									<div class="form-group mb-3">
										<label for="password">Password</label>
										<input class="form-control" name="password" type="password" required="" id="password" placeholder="Enter your password">
									</div>
									<div class="form-group mb-0 text-center">
										<button class="btn btn-primary btn-block" name="login" type="submit"> Log In </button>
									</div>
								</form>
							</div> <!-- end card-body -->
						</div>
						<!-- end card -->
					</div> <!-- end col -->
				</div>
				<!-- end row -->
			</div>
			<!-- end container -->
		</div>
		<!-- end page -->
		<?php include("assets/inc/footer1.php");?>
		<!-- Vendor js -->
		<script src="assets/js/vendor.min.js"></script>
		<!-- App js -->
		<script src="assets/js/app.min.js"></script>
	</body>
</html>
