<?php

require("config.php");
require_once "Mail.php";
require_once "Mail/mime.php";
require_once "Mail/mail.php";

echo '<html>
<head>
	<title>AirScan</title>
	<link rel="shortcut icon" type="image/png" href="favicon.ico"/>
	<link rel="apple-touch-icon" href="favicon.png"/>
	<link rel="apple-touch-icon" sizes="72x72" href="favicon.png"/>
	<link rel="apple-touch-icon" sizes="114x114" href="favicon.png"/>
</head><body>';

echo '<h1>AirScan</h1>';

if(isset($_POST["email"])) {
	$email = $_POST["email"];
} else {
	$email = "";
}

echo '<form method="POST">
	Email: <input name="email" value="'.$email.'" /><br />
	<input type="submit" value="Scan" />
</form>';

$timestamp = date('Y-m-d_H-i-s', time());
$filename = "AirScan_" . $timestamp;
//$scanner = "hpaio:/usb/Officejet_J4500_series?serial=CN9C7D10MW052T";
$scanner = "hpaio:/usb/OfficeJet_3830_series?serial=CN64C2J4C30664";

function send_email($email, $filename) {
	global $smtp_server, $smtp_port, $smtp_email, $smtp_password;
	$from = '<'.$smtp_email.'>';
	$to = '<'.$email.'>';
	$subject = 'AirScan';
	
	$html = "<html><body>Hi, your scan is now ready for you. Please find the PDF attached.</body></html>";
	$text = "Hi, your scan is now ready for you. Please find the PDF attached.";

	$headers = array(
		'MIME-Version' => '1.0',
	    'From' => $from,
	    'To' => $to,
	    'Subject' => $subject
	);

	$crlf = "\n";
	$mime = new Mail_mime(array('eol' => $crlf));

	$mime->setTXTBody($text);
	$mime->setHTMLBody($html); 
	$mime->addAttachment($filename, 'application/pdf');

	$body = $mime->get();
	$headers = $mime->headers($headers);

	$smtp = Mail::factory('smtp', array(
	        'host' => 'ssl://'.$smtp_server,
	        'port' => $smtp_port,
	        'auth' => true,
	        'username' => $smtp_email,
	        'password' => $smtp_password
	    ));
	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
	    echo('<div>' . $mail->getMessage() . '</div>');
	} else {
	    echo('<h2>Scan complete. Check your email for the PDF.</h2>');
	}
}

if(isset($_POST["email"])) {
	if(strlen($email) > 0) {
		$scan_result = shell_exec("scanimage -d $scanner --format tiff > $filename.tiff");
		echo "<pre>$scan_result</pre>";
		$convert_result = shell_exec("convert $filename.tiff $filename.pdf");
		echo "<pre>$convert_result</pre>";
		send_email($email, $filename.".pdf");
		unlink("$filename.tiff");
		unlink("$filename.pdf");
	} else {
		echo "Enter an email address";
	}
}

echo '</body></html>';

?>