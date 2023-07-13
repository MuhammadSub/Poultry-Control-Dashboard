<?php
session_start();
include('configure/config.php');
include('configure/checklogin.php');
check_login();
$aid=$_SESSION['user_id'];

// Fetch all users from the database
$selectUsers = "SELECT * FROM users";
$result = $mysqli->query($selectUsers);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    
    <!--Head Code-->
    <?php include("assets/inc/head.php");?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include('assets/inc/sidebar.php');?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Control Poultry System</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                       <div class="container">
						<?php
						$usersPerPage = 12; // Number of users to display per page

						// Filter out admin users
						$regularUsers = array_filter($users, function($user) {
							return !$user['is_admin'];
						});

						$totalUsers = count($regularUsers);
						$totalPages = ceil($totalUsers / $usersPerPage);

						$currentPage = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
						$startIndex = ($currentPage - 1) * $usersPerPage;
						$endIndex = min($startIndex + $usersPerPage, $totalUsers);

						$usersToDisplay = array_slice($regularUsers, $startIndex, $endIndex - $startIndex);
						?>

						<div class="row">
							<?php foreach ($usersToDisplay as $user) { ?>
								<div class="col-md-4">
									<div class="card user-card">
										<div class="card-body text-center">
											<div class="user-avatar">
												<div class="avatar-img">
													<img src="assets/images/users/<?php echo $user['pic']; ?>" alt="User Avatar" class="img-fluid rounded-circle" width="100" height="100">
												</div>
											</div>
											<h5 class="card-title mt-3"><?php echo $user['f_name'] . ' ' . $user['l_name']; ?></h5>
											<p class="card-text">User ID: <?php echo $user['id']; ?></p>
											<div class="user-actions">
												<a href="devices_show.php?user_id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">View Details</a>

												<a href="manage_user.php" class="btn btn-outline-danger btn-sm">Manage User</a>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>

						<?php if ($totalPages > 1) { ?>
							<nav class="mt-4">
								<ul class="pagination justify-content-center">
									<?php if ($currentPage > 1) { ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
												<span aria-hidden="true">&laquo;</span>
												<span class="sr-only">Previous</span>
											</a>
										</li>
									<?php } ?>

									<?php for ($page = 1; $page <= $totalPages; $page++) { ?>
										<li class="page-item <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
											<a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
										</li>
									<?php } ?>

									<?php if ($currentPage < $totalPages) { ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
												<span aria-hidden="true">&raquo;</span>
												<span class="sr-only">Next</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</nav>
						<?php } ?>
					</div>
					 <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <!-- ... Rest of the code remains the same ... -->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script>

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
    </body>
</html>
