<?php

	// check id param
	$redir = true;
	if (isset($_GET["id"])) {
		if (strlen($_GET["id"]) == 38) {
			$cc = substr($_GET["id"], 0, 6);
			#echo $cc."<br />\n";

			$sid = substr($_GET["id"], 6, 26);
			#echo $sid."<br />\n";

			$mc = substr($_GET["id"], 32, 6);
			#echo $mc."<br />\n";

			session_id($sid);
			session_start();

			/*/echo "<pre>";
			print_r($_SESSION);/**/

			if ($_SESSION["mc"] == $mc && $_SESSION["cc"] == $cc) {
				#$redir = false;

				require_once 'lib/api_lib.php';

				$return = array(
					"success" => false,
					"message" => ""
				);

				$mail = init_mail();
				process_step_one();
				process_step_two($return, $mail);

				/*/echo "<pre>";
				 print_r($return);/**/

				if ($return["success"]) {
					if ($_SESSION["mail_store"]["label"] == "antrag-email") {
						$return["message"] = '<p>Du erhältst nun eine Bestätigungs-E-Mail vom Ticketsystem der hessischen Mitgliederverwaltung.</p>';
					} else {
						$return["message"] = '<p>Du erhältst nun eine Bestätigungs-E-Mail vom Ticketsystem der Hessen-IT.</p>';
					}
				}

				$_SESSION["mail_message"] = $return;
				unset($_SESSION["mail_store"]);
			}
		}
	}

	#echo json_encode($redir);
	if ($redir) {
		header("location: ./");
		exit();
	}

?>