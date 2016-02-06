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
		$scan_result = shell_exec("scanimage -d $scanner --format tiff > $filename.tiff");
		echo "<div>Scan result: $scan_result</div>";
		$convert_result = shell_exec("convert $filename.tiff $filename.pdf");
		echo "<div>Convert result: $convert_result</div>";
		// TODO: send pdf by email
		unlink("$filename.tiff");
		// TODO: delete pdf once sent
	} else {
		echo "Enter an email address";
	}
}

?>