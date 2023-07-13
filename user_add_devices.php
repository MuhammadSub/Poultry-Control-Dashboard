<?php
session_start();
include('configure/config.php');

if (isset($_POST['add_device'])) {
    $device_name = $_POST['device_name'];
    $device_type = $_POST['device_type'];
    $userID = $_SESSION['user_id']; // Assign the user ID to $userID

    // SQL query to insert captured values
    $query = "INSERT INTO devices (device_name, device_type, user_id) VALUES (?, ?, ?)"; // Fix the VALUES clause
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sss', $device_name, $device_type, $userID); // Add the $userID parameter
    $stmt->execute();

    if ($stmt) {
        $success = "Device Details Added";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>

<!-- End Server Side -->
<!-- End Patient Registration -->

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php include('user_assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("user_assets/inc/nav.php"); ?>
        <!-- End Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("user_assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content -->
                <div class="container-fluid">

                    <!-- Start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Devices</a></li>
                                        <li class="breadcrumb-item active">Add Device</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Device Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End page title -->

                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!-- Add User Form -->
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">Device Name</label>
                                                <input type="text" required="required" name="device_name" class="form-control" id="inputEmail4">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Device Type</label>
                                                <input required="required" type="text" name="device_type" class="form-control" id="inputPassword4">
                                            </div>
                                        </div>
                                        
                                        <button type="submit" name="add_device" class="ladda-button btn btn-success" data-style="expand-right">Add Device</button>
                                    </form>
                                    <!-- End Device Form -->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div>
                    <!-- End row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- End Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay -->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js -->
    <script src="assets/js/pages/loading-btn.init.js"></script>

</body>

</html>
