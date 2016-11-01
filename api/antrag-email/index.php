<?php

	// session
	session_start();

	// sleep
	#sleep(10);

	// vars
	$label = basename(dirname(__FILE__));
	$body = file_get_contents("mail.txt");

	// run api
	include("../../lib/api.php");

?>