<?php
include('../../config.php');
include('lib.php');

$courses=getCourse();
if(!empty($courses)){
	$msg['status']=true;
	$msg['data']=$courses;
	$msg['msg']='';
	echo json_encode($msg);
}else{
	$msg['status']=false;
	$msg['msg']='no records found';
	echo json_encode($msg);
}
