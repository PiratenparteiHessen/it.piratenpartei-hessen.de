<?php

	// debug
	define("DEBUG", false);		// debug output
	define("TESTING", true);	// test email
	define("LIVE", true);		// live email

	// check ajax
	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	if (!IS_AJAX) {
		throw new Exception('no ajax call detected');
		exit;
	}

	// return
	header('Content-Type: application/json');
	$return = array(
		"success" => false,
		"message" => ""
	);

	// check csrf
	require_once('nocsrf.php');
	try {
		// Run CSRF check, on POST data, in exception mode, with a validity of 10 minutes, in one-time mode.
		NoCSRF::check('csrf_token', $_POST, true, 60*10, false);
	} catch (Exception $e) {
		// CSRF attack detected
		$return["message"] = $e->getMessage();
		echo json_encode($return);
		/*/print_r($_SESSION);
		echo session_id();/**/
		exit();
	}

	// process
	ob_start();
	if (DEBUG) echo $label."\n";
	if (DEBUG) print_r($_POST);
	if (DEBUG) print_r($_FILES);
	if (DEBUG) print_r($_SESSION);

	// mail
	require_once('PHPMailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();									// Set mailer to use SMTP
	$mail->Host = 'localhost'; 							// Specify main and backup SMTP servers

	$mail->CharSet = 'utf-8';
	$mail->SetLanguage("de");

	$mail->Sender = 'noreply@piratenpartei-hessen.de';
	$mail->SetFrom($_POST[$label."-email"], $_POST[$label."-vorname"]." ".$_POST[$label."-nachname"], FALSE);
	$mail->AddReplyTo($_POST[$label."-email"], $_POST[$label."-vorname"]." ".$_POST[$label."-nachname"]);

	if (TESTING && !LIVE) $mail->addAddress('nowrap@gmx.net');  
	if (LIVE) $mail->addAddress('it@piratenpartei-hessen.de');  
	if (TESTING && LIVE) $mail->addAddress('vorstand@piratenpartei-hessen.de');
	if (TESTING && LIVE) $mail->addAddress('datenschutzbeauftragter@piratenpartei-hessen.de');

	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);

	$mail->isHTML(false);
	$mail->Subject = $_POST["title"];
	foreach($_POST as $key => $value) {
		$body = str_replace("%%".$key."%%", $value, $body);
	}
	$time = time();
	$body = str_replace("%%datum%%", date("d.m.Y", $time), $body);
	$body = str_replace("%%uhrzeit%%", date("H:i:s", $time), $body);
	$mail->Body = $body;
	$mail->AltBody = $body;

	if (isset($_FILES[$label."-pgp"]) && $_FILES[$label."-pgp"]["error"] == 0) {
		$mail->addAttachment($_FILES[$label."-pgp"]["tmp_name"], $_FILES[$label."-pgp"]["name"]);
	}

	if(!$mail->send()) {
		$return["message"] = $mail->ErrorInfo;
	} else {
		$return["message"] = ob_get_clean();
		$return["success"] = true;
	}
	@unlink($_FILES[$label."-pgp"]["tmp_name"]);

	// return
	echo json_encode($return);
	exit();

?>