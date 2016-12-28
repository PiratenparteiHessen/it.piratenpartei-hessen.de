<?php

	// include
	require_once('api_lib.php');

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
	$mail = init_mail();

	// to session
	$_SESSION["mail_store"] = array(
			"label"	=>	$label,
			"body"	=>	$body,
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

		$start = rand(0, 32-6);
		$mc = substr(md5(uniqid(rand(), true)), $start, 6);
		$_SESSION["mc"] = $mc;
		$id =  $_SESSION["cc"].session_id().$mc;

		$body = str_replace("%%link%%", URL."confirmation.php?id=".$id, $body);
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
		#if (TESTING) $return["server"] = $_SERVER;

		echo json_encode($return);
		/*/print_r($_SESSION);
		 echo session_id();/**/
		exit();
	}

	// process
	process_step_one();

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
	process_step_two($return, $mail);

	// return
	$return["token"] = NoCSRF::generate('csrf_token');
	$return["recipient"] = "it";
	if ($label == "antrag-email")
		$return["recipient"] = "mv";
	echo json_encode($return);
	exit();

?>