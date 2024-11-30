<?php 
require_once("../../../wp-config.php");
  global $wpdb;
$username=$_POST['username'];
$user_email=$_POST['email'];
if(!empty($username) && !empty($user_email)){
$userdata=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users where `user_login`='$username' or `user_email`='$username' and `user_email`='$user_email' or `user_login`='$user_email'");
$data=array();
if(!empty($userdata)){
	 $avlcheckp=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."password_sync WHERE `userid`='".$userdata->ID."' and `status`='1'");
          if(empty($avlcheckp)){
          	$data['result']='notsync';
          }else{
          	$data['result']='allreadysync';
          }
}else{
	$data['result']='user data empty';
}
echo json_encode($data);
}

