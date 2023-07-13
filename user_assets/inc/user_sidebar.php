<?php
session_start();
include('configure/config.php');
include('configure/checklogin.php');
check_login();
$uid = $_SESSION['user_id'];

// Retrieve the user ID from the URL parameter
$userID = $_GET['user_id'];

// Fetch the user's device information from the database based on the ID
$selectDevice = "SELECT * FROM devices WHERE user_id = ?";
$stmt = $mysqli->prepare($selectDevice);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
$devices = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$humidityDeviceId = null; // Initialize $humidityDeviceId outside the loop
$temperatureDeviceId = null; // Initialize $temperatureId outside the loop

foreach ($devices as $device) {
    // Retrieve the last temperature value for the device
    $selectTemperature = "SELECT temperature, device_id FROM temperature_data WHERE device_id = ? ORDER BY time_tempget DESC LIMIT 1";
    $stmtTemperature = $mysqli->prepare($selectTemperature);
    $stmtTemperature->bind_param('i', $device['id']);
    $stmtTemperature->execute();
    $resultTemperature = $stmtTemperature->get_result();
    $temperatureData = $resultTemperature->fetch_assoc();
    if ($temperatureData !== null) {
        $temperature = $temperatureData['temperature'];
        $temperatureDeviceId = $temperatureData['device_id'];
    }
    $stmtTemperature->close();

    // Retrieve the last humidity value for the device
    $selectHumidity = "SELECT humidity, device_id FROM humidity_data WHERE device_id = ? ORDER BY time_humpget DESC LIMIT 1";
    $stmtHumidity = $mysqli->prepare($selectHumidity);
    $stmtHumidity->bind_param('i', $device['id']);
    $stmtHumidity->execute();
    $resultHumidity = $stmtHumidity->get_result();
    $humidityData = $resultHumidity->fetch_assoc();
    if ($humidityData !== null) {
        $humidity = $humidityData['humidity'];
        $humidityDeviceId = $humidityData['device_id'];
    }
    $stmtHumidity->close();
}
?>

<!-- Rest of the HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head Code -->
    <?php include("assets/inc/head.php"); ?>

	 <!-- Custom CSS -->
	<style>
		.device-card {
			background-color: #f9f9f9;
			border-radius: 10px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.device-info {
			margin-bottom: 15px;
		}

		.device-id .badge,
		.user-id .badge {
			font-size: 14px;
			padding: 5px 10px;
		}

		.device-id .badge {
			background-color: #007bff;
		}

		.user-id .badge {
			background-color: #6c757d;
		}

		.card-icon {
			font-size: 35px;
			margin-bottom: 20px;
		}

		.progress-bar {
			height: 10px;
			border-radius: 5px;
		}

		.card-body {
			padding-bottom: 15px;
		}

		.card-body .mt-3 {
			margin-top: 15px;
		}

		.meter {
			background-color: #e9ecef;
			height: 20px;
			border-radius: 10px;
			overflow: hidden;
			position: relative;
		}

		.meter-bar {
			height: 100%;
			border-radius: 10px;
			transition: width 0.3s ease-in-out;
			width: 0;
			position: absolute;
			top: 0;
			left: 0;
		}

		.meter-value {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			font-size: 14px;
			font-weight: 500;
			color: #6c757d;
		}

		.temperature-meter .meter-bar {
			background-color: #ff8f5a; /* Change temperature meter color */
		}

		.humidity-meter .meter-bar {
			background-color: #67c7ff; /* Change humidity meter color */
		}

		.card-footer {
			background-color: #f9f9f9;
			border-top: none;
			text-align: center;
			padding: 15px 0;
		}

		.card-footer p {
			margin-bottom: 5px;
			font-size: 14px;
			color: #6c757d;
		}
	</style>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom script for updating temperature and humidity -->
    <script>
        $(document).ready(function() {
            function updateTemperatureAndHumidity() {
                <?php foreach ($devices as $device) { ?>
                    $.ajax({
                        url: 'get_temperature.php',
                        type: 'GET',
                        data: {
                            device_id: <?php echo $device['id']; ?>
                        },
                        success: function(response) {
                            var temperatureWidth = parseInt(response);
                            $('#temperature_meter_<?php echo $device['id']; ?> .meter-bar').css('width', temperatureWidth + '%');
                            $('#temperature_value_<?php echo $device['id']; ?>').text(response + ' °C');
                        }
                    });

                    $.ajax({
                        url: 'get_humidity.php',
                        type: 'GET',
                        data: {
                            device_id: <?php echo $device['id']; ?>
                        },
                        success: function(response) {
                            var humidityWidth = parseInt(response);
                            $('#humidity_meter_<?php echo $device['id']; ?> .meter-bar').css('width', humidityWidth + '%');
                            $('#humidity_value_<?php echo $device['id']; ?>').text(response + ' %');
                        }
                    });
                <?php } ?>
            }

            // Call the updateTemperatureAndHumidity function initially
            updateTemperatureAndHumidity();

            // Call the updateTemperatureAndHumidity function every 5 seconds
            setInterval(updateTemperatureAndHumidity, 5000);
        });
    </script>
</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('assets/inc/user_sidebar.php'); ?>
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
                                <h4 class="page-title">Control Poultry System</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End page title -->

                    <div class="container">
					<div class="row">
						<?php foreach ($devices as $device) { ?>
						<div class="col-md-4">
							<div class="card device-card">
								<div class="card-body text-center">
									<div class="device-info">
										<p class="device-id">Device ID: <span class="badge badge-primary"><?php echo $device['id']; ?></span></p>
										<p class="user-id">User ID: <span class="badge badge-secondary"><?php echo $device['user_id']; ?></span></p>
									</div>
									<div class="card-icon">
										<?php if ($device['id'] == $temperatureDeviceId) { ?>
											<i class="fas fa-thermometer-half"></i>
											<p>Temperature (°C)</p>
											<div class="meter temperature-meter" id="temperature_meter_<?php echo $device['id']; ?>">
												<div class="meter-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
												<div class="meter-value" id="temperature_value_<?php echo $device['id']; ?>"></div>
											</div>
											<p id="temperature_<?php echo $device['id']; ?>"></p>
										<?php } ?>
										<?php if ($device['id'] == $humidityDeviceId) { ?>
											<i class="fas fa-tint"></i>
											<p>Humidity (%)</p>
											<div class="meter humidity-meter" id="humidity_meter_<?php echo $device['id']; ?>">
												<div class="meter-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
												<div class="meter-value" id="humidity_value_<?php echo $device['id']; ?>"></div>
											</div>
											<p id="humidity_<?php echo $device['id']; ?>"></p>
										<?php } ?>
									</div>
									<div class="mt-3">
										<a href="device_detail.php?device_id=<?php echo $device['id']; ?>" class="btn btn-primary">View Detail</a>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php'); ?>
                <!-- end Footer -->

            </div> <!-- end content -->
        </div>
        <!-- END wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <!-- ... Rest of the code remains the same ... -->
        </div>
        <!-- /Right-bar -->

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
