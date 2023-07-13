<?php
session_start();
include('configure/config.php');
include('configure/checklogin.php');
check_login();
$aid = $_SESSION['user_id'];
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

	// Delete temperature data associated with the device
    $deleteTemperatureData = "DELETE FROM temperature_data WHERE device_id IN (SELECT id FROM devices WHERE user_id=?)";
    $stmt = $mysqli->prepare($deleteTemperatureData);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // Delete humidity data associated with the device
    $deleteHumidityData = "DELETE FROM humidity_data WHERE device_id IN (SELECT id FROM devices WHERE user_id=?)";
    $stmt = $mysqli->prepare($deleteHumidityData);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
	
    // Delete related records from devices table
    $deleteDevices = "DELETE FROM devices WHERE user_id=?";
    $stmtDevices = $mysqli->prepare($deleteDevices);
    $stmtDevices->bind_param('i', $id);
    $stmtDevices->execute();
    $stmtDevices->close();

    // Delete the user from users table
    $deleteUser = "DELETE FROM users WHERE id=?";
    $stmtUser = $mysqli->prepare($deleteUser);
    $stmtUser->bind_param('i', $id);
    $stmtUser->execute();
    $stmtUser->close();

    if ($stmtUser) {
        $success = "User Records Deleted";
    } else {
        $err = "Try Again Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<?php include('assets/inc/head.php');?>

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
                <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
                <?php include("assets/inc/sidebar.php");?>
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
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                                            <li class="breadcrumb-item active">Manage User</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Manage User Details</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-12">
                                <div class="card-box">
                                    <div class="table-responsive">
                                        <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th data-toggle="true">User_ID</th>
                                                <th data-toggle="true">Name</th>
                                                <th data-hide="phone">Email</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                            </thead>
                                            <?php
                                            $ret = "SELECT * FROM users ORDER BY RAND()";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <?php if (!$row->is_admin) { ?>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row->id; ?></td>
                                                        <td><?php echo $row->f_name; ?> <?php echo $row->l_name; ?></td>
                                                        <td><?php echo $row->email; ?></td>
                                                        <td>
                                                            <a href="manage_user.php?delete=<?php echo $row->id; ?>" class="badge badge-danger"><i class="mdi mdi-trash-can-outline"></i> Delete</a>
                                                            <a href="single_user.php?id=<?php echo $row->id; ?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>
                                                            <a href="update_single_user.php?id=<?php echo $row->id; ?>" class="badge badge-primary"><i class="mdi mdi-check-box-outline"></i> Update</a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            </tbody>
                                            <?php
                                                $cnt++;
                                            }
                                            ?>
                                            <tfoot>
                                                <tr class="active">
                                                    <td colspan="8">
                                                        <div class="text-right">
                                                            <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> <!-- end .table-responsive-->
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

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


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Footable js -->
        <script src="assets/libs/footable/footable.all.min.js"></script>

        <!-- Init js -->
        <script src="assets/js/pages/foo-tables.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>
