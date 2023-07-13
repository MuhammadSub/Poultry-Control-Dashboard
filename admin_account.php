<?php
	session_start();
	include('configure/config.php');
		if(isset($_POST['update_profile']))
		{
			$f_name=$_POST['f_name'];
			$l_name=$_POST['l_name'];
			$user_id=$_SESSION['user_id'];
			$id=$_SESSION['user_id'];
            $email=$_POST['email'];
            $pic=$_FILES["pic"]["name"];
		    move_uploaded_file($_FILES["pic"]["tmp_name"],"assets/images/users/".$_FILES["pic"]["name"]);

            //sql to insert captured values
			$query="UPDATE admin SET f_name=?, l_name=?,  email=?, pic=? WHERE user_id = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssi', $f_name, $l_name, $email, $pic, $user_id);
			$stmt->execute();
			$query1="UPDATE users SET f_name=?, l_name=?,  email=?, pic=? WHERE id = ?";
			$stmt1 = $mysqli->prepare($query1);
			$rc1=$stmt1->bind_param('ssssi', $f_name, $l_name, $email, $pic, $user_id);
			$stmt1->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Profile Updated";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			$query1="UPDATE users SET email=? WHERE id = ?";
			$stmt1 = $mysqli->prepare($query1);
			$rc1=$stmt1->bind_param('si', $email, $user_id);
			$stmt1->execute();
			
			
        }
        //Change Password
        if(isset($_POST['update_password']))
		{
            $user_id=$_SESSION['user_id'];
            $password=$_POST['password'];//double encrypt 
            
            //sql to insert captured values
			$query="UPDATE users SET password =? WHERE id = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('si', $password, $user_id);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Password Updated";
			}
			else {
				$err = "Please Try Again Or Try Later";
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
                <?php include('assets/inc/sidebar.php');?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
                $aid=$_SESSION['user_id'];
                $ret="select * from admin where user_id=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('i',$aid);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
            ?>
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
                                                <li class="breadcrumb-item active">Profile</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title"><?php echo $row->f_name;?> <?php echo $row->l_name;?>'s Profile</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- end page title -->

                            <div class="row">
                                <div class="col-lg-4 col-xl-4">
                                    <div class="card-box text-center">
                                        <img src="assets/images/users/<?php echo $row->pic;?>" class="rounded-circle avatar-lg img-thumbnail"
                                            alt="profile-image">

                                        <h4 class="mb-0"><?php echo $row->f_name;?> <?php echo $row->l_name;?></h4>
                                        <p class="text-muted">@Control Poultry Admin</p>
                                        <div class="text-left mt-3">
                                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2"><?php echo $row->f_name;?> <?php echo $row->l_name;?></span></p>
                                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 "><?php echo $row->email;?></span></p>
                                        </div>

                                    </div> <!-- end card-box -->
                                   
                                </div> <!-- end col-->

                                <div class="col-lg-8 col-xl-8">
                                    <div class="card-box">
                                        <ul class="nav nav-pills navtab-bg nav-justified">
                                            <li class="nav-item">
                                                <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    Update Profile
                                                </a>
                                            </li>
                                            
                                            <li class="nav-item">
                                                <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                    Change Password
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="aboutme">

                                            <form method="post" enctype="multipart/form-data">
                                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name</label>
                                                                <input type="text" name="f_name"  class="form-control" id="firstname" placeholder="<?php echo $row->f_name;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name</label>
                                                                <input type="text" name="l_name" class="form-control" id="lastname" placeholder="<?php echo $row->l_name;?>">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="useremail">Email Address</label>
                                                                <input type="email" name="email" class="form-control" id="useremail" placeholder="<?php echo $row->email;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="useremail">Profile Picture</label>
                                                                <input type="file" name="pic" class="form-control btn btn-success" id="useremail" placeholder="<?php echo $row->email;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" name="update_profile" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                    </div>
                                                </form>


                                            </div> <!-- end tab-pane -->
                                            <!-- end about me section content -->

                                           

                                            <div class="tab-pane" id="settings">
                                                <form method="post">
                                                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname">Old Password</label>
                                                                <input type="password" class="form-control" id="firstname" placeholder="Enter Old Password">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="lastname">New Password</label>
                                                                <input type="password" class="form-control" name="password" id="lastname" placeholder="Enter New Password">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="form-group">
                                                        <label for="useremail">Confirm Password</label>
                                                        <input type="password" class="form-control" id="useremail" placeholder="Confirm New Password">
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" name="update_password" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Update Password</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- end settings content-->

                                        </div> <!-- end tab-content -->
                                    </div> <!-- end card-box-->

                                </div> <!-- end col -->
                            </div>
                            <!-- end row-->

                        </div> <!-- container -->

                    </div> <!-- content -->

                    <!-- Footer Start -->
                    <?php include("assets/inc/footer.php");?>
                    <!-- end Footer -->

                </div>
            <?php }?>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

    </body>


</html>