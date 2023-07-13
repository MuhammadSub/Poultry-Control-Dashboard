<?php
session_start();
include('configure/config.php');
include('configure/checklogin.php');
check_login();
$uid = $_SESSION['user_id'];

// Retrieve the user ID from the URL parameter
$deviceID = $_GET['device_id'];

// Fetch temperature data for the device
$selectData = "SELECT td.*, d.device_name 
               FROM temperature_data td 
               INNER JOIN devices d ON td.device_id = d.id 
               WHERE td.device_id = ?";
$stmt = $mysqli->prepare($selectData);
$stmt->bind_param('i', $deviceID);
$stmt->execute();
$result = $stmt->get_result();
$temperatureData = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch humidity data for the device
$selectData = "SELECT hd.*, d.device_name 
               FROM humidity_data hd 
               INNER JOIN devices d ON hd.device_id = d.id 
               WHERE hd.device_id = ?";
$stmt = $mysqli->prepare($selectData);
$stmt->bind_param('i', $deviceID);
$stmt->execute();
$result = $stmt->get_result();
$humidityData = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!-- Rest of the HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head Code -->
    <?php include("user_assets/inc/head.php"); ?>
    <style>
        .chart-container {
            position: relative;
            height: 480px;
            width: 800px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include('user_assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('user_assets/inc/sidebar.php'); ?>
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
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (!empty($temperatureData) && $deviceID == $temperatureData[0]['device_id']) { ?>
                                            <h5 class="card-title">Temperature Data - <?php echo $temperatureData[0]['device_name']; ?></h5>
                                            <div class="chart-container">
                                                <canvas id="TemperatureChart"></canvas>
                                            </div>
                                        <?php } elseif (!empty($humidityData) && $deviceID == $humidityData[0]['device_id']) { ?>
                                            <h5 class="card-title">Humidity Data - <?php echo $humidityData[0]['device_name']; ?></h5>
                                            <div class="chart-container">
                                                <canvas id="HumidityChart"></canvas>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container -->

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
		
		    <script>
            // Extract humidity values and timestamps from PHP variables
            var humidityData = <?php echo json_encode($humidityData); ?>;
            var humidityValues = humidityData.map(item => item.humidity);
            var humidityTimestamps = humidityData.map(item => new Date(item.time_humpget).toLocaleString());

            // Create humidity chart
            var humidityCtx = document.getElementById('HumidityChart').getContext('2d');
            var humidityChart = new Chart(humidityCtx, {

                type: 'line',
                data: {
                    labels: humidityTimestamps,
                    datasets: [{
                        label: 'Humidity',
                        data: humidityValues,
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderColor: 'rgba(255, 193, 7, 0.7)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 10
                            },
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5
                            },
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            // Extract temperature values and timestamps from PHP variables
            var temperatureData = <?php echo json_encode($temperatureData); ?>;
            var temperatureValues = temperatureData.map(item => item.temperature);
            var temperatureTimestamps = temperatureData.map(item => new Date(item.time_tempget).toLocaleString());

            // Create temperature chart
            var temperatureCtx = document.getElementById('TemperatureChart').getContext('2d');
            var temperatureChart = new Chart(temperatureCtx, {

                type: 'line',
                data: {
                    labels: temperatureTimestamps,
                    datasets: [{
                        label: 'Temperature',
                        data: temperatureValues,
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderColor: 'rgba(0, 123, 255, 0.7)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 10
                            },
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        y: {
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5
                            },
                            grid: {
                                display: true,
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        </script>
    </body>
</html>
