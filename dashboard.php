<?php
// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "co2_monitoring";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get latest 20 records
$result = $conn->query("SELECT * FROM sensor_data ORDER BY created_at DESC LIMIT 20");
$latest = $conn->query("SELECT * FROM sensor_data ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>IoT CO2 Monitoring - Industry</title>
<style>
body{font-family:Arial; background:#f4f4f4; padding:20px;}
.card{background:white; padding:20px; border-radius:10px; margin:10px; display:inline-block; width:200px; text-align:center;}
.danger{background:#ff4d4d; color:white;}
table{width:100%; background:white; border-collapse:collapse; margin-top:20px;}
th,td{padding:10px; border:1px solid #ddd; text-align:center;}
</style>
</head>
<body>
<h1>IoT Smart Monitoring CO2 in Industry</h1>
<p>Real-time monitoring using ESP32 + MQ-135 + DHT11</p>

<div class="card <?php if($latest['co2']>1000) echo 'danger'; ?>">
<h3>CO2</h3>
<h1><?php echo $latest['co2']; ?> ppm</h1>
<p><?php echo $latest['co2']>1000 ? '⚠️ DANGER - Ventilate!' : '✅ Safe'; ?></p>
</div>

<div class="card"><h3>Temperature</h3><h1><?php echo $latest['temp']; ?> °C</h1></div>
<div class="card"><h3>Humidity</h3><h1><?php echo $latest['hum']; ?> %</h1></div>

<table>
<tr><th>CO2 (ppm)</th><th>Temp (°C)</th><th>Humidity (%)</th><th>Time</th></tr>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['co2']; ?></td>
<td><?php echo $row['temp']; ?></td>
<td><?php echo $row['hum']; ?></td>
<td><?php echo $row['created_at']; ?></td>
</tr>
<?php } ?>
</table>
<p>Author: MUTUYIMANA Vainqueur</p>
</body>
</html>
