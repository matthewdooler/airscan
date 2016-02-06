<?php

echo '<h1>AirScan</h1>';

echo '<form method="POST">
	Email: <input name="email" /><br />
	<input type="submit" value="Scan" />
</form>';

$timestamp = date('Y-m-d_H-i-s', time());
$filename = "AirScan_" . $timestamp;
$scanner = "hpaio:/usb/Officejet_J4500_series?serial=CN9C7D10MW052T";

echo $filename;

?>