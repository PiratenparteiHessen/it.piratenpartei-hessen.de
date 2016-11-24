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
		$return["token"] = NoCSRF::generate('csrf_token');
		echo json_encode($return);
		/*/print_r($_POST);
		print_r($_SESSION);
		echo session_id();/**/
		unset($_SESSION["submit"]);
		exit();
	}

	// check captcha
	$captcha = $_POST[$label."-captcha"];
	if (!$_POST["confirmation"] && $captcha != $_SESSION["captcha"]["math_1"] + $_SESSION["captcha"]["math_2"]) {
		// wrong
		$return["message"] = "Das mathematische Rätsel wurde nicht korrekt gelöst.";
		$return["token"] = NoCSRF::generate('csrf_token');
		echo json_encode($return);
		/*/print_r($_POST);
		print_r($_SESSION);
		echo session_id();/**/
		exit();
	}

	// mail
	require_once('PHPMailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();									// Set mailer to use SMTP
	$mail->Host = 'localhost'; 							// Specify main and backup SMTP servers

	$mail->isHTML(false);
	$mail->CharSet = 'utf-8';
	$mail->SetLanguage("de");

	$mail->SMTPOptions = array(
			'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
			)
	);

	// process confirmation
	if (isset($_SESSION["cc"]) && !isset($_SESSION["submit"])) {
		$_SESSION["submit"] = array(
			"post"   => $_POST,
			"files"  => $_FILES,
		);

		// mail
		$mail->Sender = 'noreply@piratenpartei-hessen.de';
		$mail->SetFrom('noreply@piratenpartei-hessen.de');
		$mail->addAddress($_POST[$label."-email"]);
		$mail->Subject = "Bestätigung von: ".$_POST["title"];

		$file = dirname(__FILE__)."/api.txt";
		if (DEBUG) print_r($file);
		$body = file_get_contents($file);
		$time = time();

		$body = str_replace("%%vorname%%", $_POST[$label."-vorname"], $body);
		$body = str_replace("%%nachname%%", $_POST[$label."-nachname"], $body);
		$body = str_replace("%%title%%", $_POST["title"], $body);

		$body = str_replace("%%datum%%", date("d.m.Y", $time), $body);
		$body = str_replace("%%uhrzeit%%", date("H:i:s", $time), $body);

		$body = str_replace("%%link%%", "confirmation.php?id=".session_id(), $body);
		$body = str_replace("%%code%%", $_SESSION["cc"], $body);
		if (DEBUG) print_r($body);

		$mail->Body = nl2br($body);
		$mail->AltBody = $body;
		if (DEBUG) print_r($mail);

		if(!$mail->send()) {
			$return["message"] = $mail->ErrorInfo;
		} else {
			$return["success"] = true;
			$return["next"] = "code";
		}
		$return["token"] = NoCSRF::generate('csrf_token');

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

	// check confirmation
	if ($_POST["confirmation"] != $_SESSION["cc"]) {
		// wrong
		$return["message"] = "Der Bestätigungs-Code ist falsch.";
		$return["token"] = NoCSRF::generate('csrf_token');
		echo json_encode($return);
		/*/print_r($_POST);
		print_r($_SESSION);
		echo session_id();/**/

		unset($_SESSION["submit"]);

		$start = rand(0, 32-6);
		$cc = substr(md5(uniqid(rand(), true)), $start, 6);
		$_SESSION["cc"] = $cc;

		exit();
	}

	// mail
	$mail->Sender = 'noreply@piratenpartei-hessen.de';
	$mail->SetFrom($_SESSION["submit"]["post"][$label."-email"], $_SESSION["submit"]["post"][$label."-vorname"]." ".$_SESSION["submit"]["post"][$label."-nachname"], FALSE);
	$mail->AddReplyTo($_SESSION["submit"]["post"][$label."-email"], $_SESSION["submit"]["post"][$label."-vorname"]." ".$_SESSION["submit"]["post"][$label."-nachname"]);

	if (TESTING && !LIVE) $mail->addAddress('nowrap@gmx.net');
	if (LIVE) $mail->addAddress('it@piratenpartei-hessen.de');
	if (TESTING && LIVE) $mail->addAddress('vorstand@piratenpartei-hessen.de');
	if (TESTING && LIVE) $mail->addAddress('datenschutzbeauftragter@piratenpartei-hessen.de');

	$mail->Subject = $_SESSION["submit"]["post"]["title"];
	foreach($_SESSION["submit"]["post"] as $key => $value) {
		$body = str_replace("%%".$key."%%", $value, $body);
	}
	$time = time();
	$body = str_replace("%%datum%%", date("d.m.Y", $time), $body);
	$body = str_replace("%%uhrzeit%%", date("H:i:s", $time), $body);
	$mail->Body = nl2br($body);
	$mail->AltBody = $body;

	if (isset($_SESSION["submit"]["files"][$label."-pgp"]) && $_SESSION["submit"]["files"][$label."-pgp"]["error"] == 0) {
		$mail->addAttachment($_SESSION["submit"]["files"][$label."-pgp"]["tmp_name"], $_SESSION["submit"]["files"][$label."-pgp"]["name"]);
	}

	@unlink($_SESSION["submit"]["files"][$label."-pgp"]["tmp_name"]);

	if(!$mail->send()) {
		$return["message"] = $mail->ErrorInfo;
	} else {
		$return["message"] = ob_get_clean();
		$return["success"] = true;
	}

	unset($_SESSION["submit"]);
	unset($_SESSION["cc"]);
	unset($_SESSION["captcha"]);

	// return
	$return["token"] = NoCSRF::generate('csrf_token');
	$return["recipient"] = "it";
	if ($label == "antrag-email")
		$return["recipient"] = "mv";
	echo json_encode($return);
	exit();

?>