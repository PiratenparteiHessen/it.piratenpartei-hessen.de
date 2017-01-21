<?php

	// url + debug
		switch ($_SERVER["SERVER_NAME"]) {
			case "dev.gi.net":
				define("URL", "http://dev.gi.net/it.piratenpartei-hessen.de/");

				define("DEBUG", false);		// debug output
				define("TESTING", true);	// test email
				define("LIVE", false);		// live email
				break;

			default:
				define("URL", "https://it.piratenpartei-hessen.de/");

				define("DEBUG", false);		// debug output
				define("TESTING", false);	// test email
				define("LIVE", true);		// live email
				break;
		}

	// functions
		function init_mail() {
			require_once('PHPMailer/PHPMailerAutoload.php');
			$mail = new PHPMailer;
			$mail->isSMTP();				// Set mailer to use SMTP
			$mail->Host = 'localhost'; 		// Specify main and backup SMTP servers

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

			return $mail;
		}

		function process_step_one() {
			ob_start();
			if (DEBUG) echo $label."\n";
			if (DEBUG) print_r($_POST);
			if (DEBUG) print_r($_FILES);
			if (DEBUG) print_r($_SESSION);
		}

		function process_step_two(&$return, $mail) {
			$body = $_SESSION["mail_store"]["body"];
			$label = $_SESSION["mail_store"]["label"];

			$mail->Sender = 'noreply@piratenpartei-hessen.de';
			$mail->SetFrom($_SESSION["submit"]["post"][$label."-email"], $_SESSION["submit"]["post"][$label."-vorname"]." ".$_SESSION["submit"]["post"][$label."-nachname"], FALSE);
			$mail->AddReplyTo($_SESSION["submit"]["post"][$label."-email"], $_SESSION["submit"]["post"][$label."-vorname"]." ".$_SESSION["submit"]["post"][$label."-nachname"]);

			if (TESTING && !LIVE) $mail->addAddress('nowrap@gmx.net');
			if (LIVE && $label != "antrag-email") $mail->addAddress('it@piratenpartei-hessen.de');
			if (LIVE && $label == "antrag-email") $mail->addAddress('mitglieder-ticket@piratenpartei-hessen.de');
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

			if (isset($_SESSION["submit"])) {
				if (isset($_SESSION["submit"]["files"][$label."-pgp"]) && $_SESSION["submit"]["files"][$label."-pgp"]["error"] == 0) {
					$mail->addAttachment($_SESSION["submit"]["files"][$label."-pgp"]["tmp_name"], $_SESSION["submit"]["files"][$label."-pgp"]["name"]);
				}

				@unlink($_SESSION["submit"]["files"][$label."-pgp"]["tmp_name"]);
			}

			if(!$mail->send()) {
				$return["message"] = $mail->ErrorInfo;
			} else {
				$return["message"] = ob_get_clean();
				$return["success"] = true;
			}

			unset($_SESSION["submit"]);
			unset($_SESSION["cc"]);
			unset($_SESSION["captcha"]);
			unset($_SESSION["mc"]);
		}