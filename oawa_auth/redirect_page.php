<?php require_once("../../config.php");
	global $DB, $USER,$CFG;
	$redirecturl=base64_decode($_GET['redirecturl']);
	$message="";
	if(!empty($_GET['message'])){
		$message=base64_decode($_GET['message']);
	}
	

redirect($redirecturl, $message, null, \core\output\notification::NOTIFY_SUCCESS);