<?php
session_start();
include('configure/config.php');
include('configure/checklogin.php');
check_login();

$aid = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete humidity data associated with the device
    $deleteHumidityData = "DELETE FROM humidity_data WHERE device_id=?";
    $stmt = $mysqli->prepare($deleteHumidityData);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // Delete temperature data associated with the device
    $deleteTemperatureData = "DELETE FROM temperature_data WHERE device_id=?";
    $stmt = $mysqli->prepare($deleteTemperatureData);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    // Delete the device
    $deleteDevice = "DELETE FROM devices WHERE id=?";
    $stmt = $mysqli->prepare($deleteDevice);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Device Records Deleted";
    } else {
        $err = "Try Again Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Devices</a></li>
                                        <li class="breadcrumb-item active">Manage Dvices</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Devices Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <div class="table-responsive">
                                    <table id="demo-foo-filtering"
                                        class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th data-toggle="true">User_ID</th>
                                                <th data-toggle="true">Name</th>
                                                <th data-toggle="true">Device Name</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $ret = "SELECT u.id, u.email, u.is_admin, d.id as device_id, d.device_name, CONCAT(u.f_name, ' ', u.l_name) as full_name 
                                            FROM users u 
                                            INNER JOIN devices d ON u.id = d.user_id
                                            ORDER BY d.id ASC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_assoc()) {
                                        ?>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['device_name']; ?></td>
                                                <td>
                                                    <a href="manage_device.php?delete=<?php echo $row['device_id']; ?>"
                                                        class="badge badge-danger"><i
                                                            class=" mdi mdi-trash-can-outline "></i> Delete</a>
                                                    <a href="single_user.php?id=<?php echo $row['id']; ?>"
                                                        class="badge badge-success"><i class="mdi mdi-eye"></i> View</a>
                                                </td>
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
                                                        <ul
                                                            class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0">
                                                        </ul>
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
            <?php include('assets/inc/footer.php'); ?>
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
