<?php require_once("../../config.php");
require_once("lib.php");
   global $PAGE, $CFG,$DB;
	$userdata = json_decode(file_get_contents('php://input'), true); 
	$username=$userdata['user_login'];
	$user_email=$userdata['user_email'];
	$user_pass=$userdata['user_pass'];
	$first_name=$userdata['first_name'];
	$last_name=$userdata['last_name'];
	$userinfos=$DB->get_record_sql("SELECT id FROM {user} where username ='$username' or email='$username'");
	if(!empty($userinfos->id)){
			echo json_encode(authtokengenerate($userinfos->id));
	}else{
		$return=wordpressUsers($userdata);
	echo json_encode(authtokengenerate($return['userid']));  
	}


