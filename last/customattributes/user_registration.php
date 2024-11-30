<?php
include('../../../wp-config.php');
echo "<pre>";
print_r($_POST);
$name=$_POST['name'];
$email=sanitize_email($_POST['email']);
$username=$_POST['username'];
$password=$_POST['password'];
//$subcription=$_POST['subcription'];
if(isset($name) && !empty($name) && isset($email) && !empty($email) && isset($username) && !empty($username) && isset($password) && !empty($password)){


}else{
	$msg['status']=false;
	$msg['msg']="Name,Email,Username and password are required";
	echo json_encode($msg);
}