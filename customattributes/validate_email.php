<?php
include('../../../wp-config.php');
$email=$_GET['email'];
global $wpdb;
$query="SELECT * FROM ".$wpdb->prefix."users WHERE user_email='".$email."'";
$userdata=$wpdb->get_results($query);
if(!empty($userdata)){
	echo "false";
	exit();
}else{
	echo "true";
	exit();
}