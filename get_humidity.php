<?php
// Retrieve the device ID from the AJAX request
$deviceID = $_GET['device_id'];

// Implement your logic to fetch the humidity value from the database based on the device ID
// Example: Assuming you have a table named "humidity_data" with a column named "humidity"
// where you store the humidity values, you can fetch the latest humidity value as follows:

include('configure/config.php');

$selectHumidity = "SELECT humidity FROM humidity_data WHERE device_id = ? ORDER BY time_humpget DESC LIMIT 1";
$stmtHumidity = $mysqli->prepare($selectHumidity);
$stmtHumidity->bind_param('i', $deviceID);
$stmtHumidity->execute();
$resultHumidity = $stmtHumidity->get_result();
$humidityData = $resultHumidity->fetch_assoc();
$stmtHumidity->close();

if ($humidityData !== null) {
    $humidity = $humidityData['humidity'];
    echo $humidity;
} else {
    echo "N/A"; // Display "N/A" if no humidity value is found
}
?>
