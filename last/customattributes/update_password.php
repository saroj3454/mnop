<?php require_once("../../../wp-config.php");
 global $wpdb;
 if(empty($_GET['forget'])){
	 if(!is_user_logged_in()) {
	 	 wp_redirect(site_url('/user-login/'));
	 	 exit();
	 }
 }else{
 	session_destroy();
    wp_clear_auth_cookie();
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_logout(); 
 }

 $npass=base64_decode($_GET['npass']);
 $wpuser=base64_decode($_GET['wpuser']);

$datauser=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users WHERE `ID`='".$wpuser."'");

       wp_set_password($npass,$datauser->ID);
   		
        wp_set_auth_cookie($datauser->ID);
        wp_set_current_user($datauser->ID);
        $current_user = wp_get_current_user();
        do_action('wp_login', $current_user->user_login,$current_user);

if(!empty($_GET['forget'])){
$redirecturl=MoodleURL."/my/";
$msg="";
}else{
	$redirecturl=MoodleURL."/user/preferences.php";
	$msg=base64_encode("Password Updated");
}
        
 wp_redirect(MoodleURL."/local/oawa_auth/redirect_page.php?redirecturl=".base64_encode($redirecturl)."&message=".$msg);
