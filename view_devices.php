<?php
  session_start();
  include('configure/config.php');
  include('configure/checklogin.php');
  check_login();
  $aid=$_SESSION['user_id'];
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Devices</a></li>
                                            <li class="breadcrumb-item active">View Dvices</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Devices Details</h4>
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
                                                <th data-toggle="true">Device Name</th>
												<th data-hide="phone">Action</th>
                                            </tr>
                                            </thead>
                                            <?php
                                            /*
                                                *get details of all devices
                                                *
                                            */
                                                $ret="SELECT * FROM  devices ORDER BY RAND() "; 
                                                //sql code to get to ten docs  randomly
                                                $stmt= $mysqli->prepare($ret) ;
                                                $stmt->execute() ;//ok
                                                $res=$stmt->get_result();
                                                $cnt=1;
                                                while($row=$res->fetch_object())
                                                {
                                            ?>

                                                <tbody>
													<?php
														$ret = "SELECT u.id,u.f_name,u.l_name, GROUP_CONCAT(d.device_name SEPARATOR ' ') AS device_names
																FROM users AS u
																INNER JOIN devices AS d ON u.id = d.user_id
																GROUP BY u.id";
														$stmt = $mysqli->prepare($ret);
														$stmt->execute();
														$res = $stmt->get_result();
														$cnt = 1;
														while ($row = $res->fetch_assoc()) {
															?>
															<tr>
																<td><?php echo $cnt; ?></td>
																<td><?php echo $row['id']; ?></td>
																<td><?php echo $row['f_name']; ?> <?php echo $row['l_name']; ?></td>
																<td><?php echo $row['device_names'] ?></td>
																
																<td>
																	<a href="devices_show.php?user_id=<?php echo $row['id']; ?>" class="badge badge-success"><i class="mdi mdi-eye"></i> View</a></td>
															</tr>
															<?php
															$cnt++;
														}

													?>
												</tbody>


                                            <?php  $cnt = $cnt +1 ; }?>
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