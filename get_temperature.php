<?php
// Retrieve the device ID from the AJAX request
$deviceID = $_GET['device_id'];

// Implement your logic to fetch the temperature value from the database based on the device ID
// Example: Assuming you have a table named "temperature_data" with a column named "temperature"
// where you store the temperature values, you can fetch the latest temperature value as follows:

include('configure/config.php');

$selectTemperature = "SELECT temperature FROM temperature_data WHERE device_id = ? ORDER BY time_tempget DESC LIMIT 1";
$stmtTemperature = $mysqli->prepare($selectTemperature);
$stmtTemperature->bind_param('i', $deviceID);
$stmtTemperature->execute();
$resultTemperature = $stmtTemperature->get_result();
$temperatureData = $resultTemperature->fetch_assoc();
$stmtTemperature->close();

if ($temperatureData !== null) {
    $temperature = $temperatureData['temperature'];
    echo $temperature;
} else {
    echo "N/A"; // Display "N/A" if no temperature value is found
}
?>
