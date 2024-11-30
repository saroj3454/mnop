<?php
include_once('../../../wp-config.php');
if(!is_user_logged_in()){
	 wp_redirect(site_url('user-login/?authlogin=login')); 
	exit();
}
$userdata=wp_get_current_user();
$first_name=get_user_meta($userdata->data->ID,'first_name',true);
$last_name=get_user_meta($userdata->data->ID,'last_name',true);
$post =json_encode([
    'username' =>$userdata->data->user_login,
    'password' => $userdata->data->user_pass,
    'email'   =>$userdata->data->user_email,
    'first_name'=>$first_name,
    'last_name'=>$last_name
]);
print_r($post);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => MoodleURL.'/local/oawa_auth/user_auth/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$post,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/plain'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$dd=json_decode($response);

 // die();
 if($dd->status){
  // echo $dd->launch_url;
  // die();
  wp_redirect($dd->launch_url);
  exit();
 }
 