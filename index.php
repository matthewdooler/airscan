<?php

echo '<h1>AirScan</h1>';

echo '<form method="POST">
	Email: <input name="email" /><br />
	<input type="submit" value="Scan" />
</form>';

require("config.php");
require_once("Mail.php");

$timestamp = date('Y-m-d_H-i-s', time());
$filename = "AirScan_" . $timestamp;
$scanner = "hpaio:/usb/Officejet_J4500_series?serial=CN9C7D10MW052T";


if(isset($_POST)) {
	$email = $_POST["email"]; // TODO: remember to escape this before using it
	if(strlen($email) > 0) {
		$scan_result = shell_exec("scanimage -d $scanner --format tiff > $filename.tiff");
		echo "<pre>$scan_result</pre>";
		$convert_result = shell_exec("convert $filename.tiff $filename.pdf");
		echo "<pre>$convert_result</pre>";
		//send_email($email, $filename);
		unlink("$filename.tiff");
		unlink("$filename.pdf");
	} else {
		echo "Enter an email address";
	}
}

?>