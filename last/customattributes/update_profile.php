 <?php require_once("../../../wp-config.php");
 global $wpdb;
 if(!is_user_logged_in()) {
 	 wp_redirect(site_url('/user-login/'));
 	 exit();
 }
 $firstname=base64_decode($_GET['firstname']);
 $lastname=base64_decode($_GET['lastname']);
 $username=base64_decode($_GET['uname']);
 $email=base64_decode($_GET['email']);
 $userid=base64_decode($_GET['userid']);
 $actionuserid=base64_decode($_GET['actionuserid']);
 $npass=base64_decode($_GET['npass']);
 $wpuser=base64_decode($_GET['wpuser']);
 
  $datauser=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."users WHERE `user_login`='".$username."' or `user_email`='".$email."'");
   wp_update_user( array(
        'ID' => $datauser->ID,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name' => $lastname,
        'user_nicename' => trim($firstname."".$lastname),
        'display_name' =>trim($firstname."".$lastname)
   ) );

 if(!empty($npass)){
  wp_set_password($npass,$datauser->ID);
 }
if(!empty($wpuser)){
  $wpdb->update($wpdb->users, array('user_login' => $username), array('ID' =>$wpuser));
}


if($userid==$actionuserid){
 		$current_user = wp_get_current_user();
        wp_set_auth_cookie($datauser->ID);
        wp_set_current_user($datauser->ID);
        do_action('wp_login', $current_user->user_login,$current_user);
        $redirecturl=MoodleURL."/user/profile.php?id=".$userid;
 		$msg=base64_encode("Changes saved");
 	}else{

     $redirecturl=MoodleURL."/admin/user.php";
     $msg=base64_encode("Changes saved");
 	}
 wp_redirect(MoodleURL."/local/oawa_auth/redirect_page.php?redirecturl=".base64_encode($redirecturl)."&message=".$msg);
