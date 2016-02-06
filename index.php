<?php

echo '<h1>AirScan</h1>';

echo '<form method="POST">
	Email: <input name="email" /><br />
	<input type="submit" value="Scan" />
</form>';

$timestamp = date('Y-m-d_H-i-s', time());
$filename = "AirScan_" . $timestamp;
$scanner = "hpaio:/usb/Officejet_J4500_series?serial=CN9C7D10MW052T";

if(isset($_POST)) {
	$email = $_POST["email"]; // TODO: remember to escape this before using it
	if(strlen($email) > 0) {
		shell_exec("scanimage -d $scanner --format tiff > $filename.tiff");
		shell_exec("convert $filename.tiff $filename.pdf");
		unlink("$filename.tiff");
	} else {
		echo "Enter an email address";
	}
}

?>