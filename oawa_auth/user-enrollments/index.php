<?php
include('../../../config.php');
include('../lib.php'); 
if(!empty($_POST)){
	echo json_encode(user_registration($_POST));
}else{
	$msg['status']=false;
	$msg['msg']="Request invalid";
	echo json_encode($msg);
}
