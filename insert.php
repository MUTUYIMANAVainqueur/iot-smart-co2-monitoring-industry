<?php
$conn = new mysqli("localhost", "root", "", "co2_monitoring");
$co2 = $_POST['co2'];
$temp = $_POST['temp'];
$hum = $_POST['hum'];
$conn->query("INSERT INTO sensor_data (co2, temp, hum) VALUES ('$co2','$temp','$hum')");
echo "Data Saved";
?>
