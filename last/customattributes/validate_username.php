<?php
include('../../../wp-config.php');
include('../../../wp-config.php');
$username=$_GET['username'];
global $wpdb;
$query="SELECT * FROM ".$wpdb->prefix."users WHERE user_login='".$username."'";
$userdata=$wpdb->get_results($query);
if(!empty($userdata)){
	echo "false";
	exit();
}else{
	echo "true";
	exit();
}