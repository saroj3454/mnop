<?php class UserpasswordUpdate{
	function __construct(){
		
    add_shortcode('ss_user_password',[ $this,'userpassword_update']);
    add_filter('wp_authenticate_user', [ $this,'my_auth_login'],10,2);
    add_action('init', [ $this,'checkpasswordMoodle'],10,2);
	}

	function userpassword_update(){
		if(!is_user_logged_in()){
			wp_redirect(site_url('login'));
            exit();  
		}
  $current_user = wp_get_current_user();
   $emailid="";
  if(!empty($current_user->user_email)){
     $emailid=$current_user->user_email;
  }

	wp_enqueue_script('custom-js', site_url().'/wp-content/plugins/ssoconfig/js/password.js',false, '1.0.0', 'all');
	 wp_enqueue_style('password-css-rgister', site_url().'/wp-content/plugins/ssoconfig/css/passwordstyle.css',false, '1.0.0', 'all');	
    $postdata=$this->post_update($_POST);
    $mailerror="";
    if(!empty($postdata['email'])){
    	$mailerror=$postdata['email'];
    }
	$current_pass_error="";
	if(!empty($postdata['current_pass_error'])){
    	$current_pass_error=$postdata['current_pass_error'];
    }
    $matchpassword="";
	if(!empty($postdata['password'])){
    	$matchpassword=$postdata['password'];
    }

    $sucessdata="";
    if(!empty($postdata['sucess'])){
    	$sucessdata="<div class='center-success'>
		 <div class='alert alert-success alert-dismissible fade show'>".$postdata['sucess']."<button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    </div>";
    }else{
    	$sucessdata="";
    }

		echo $sucessdata."
    <div class='wrap-header'>

    <div class='forgot-header'>
<form method='post' autocomplete='off'>
<div class='form-wrap'>
  <label for='email' class='forgot-label'><p>Email</p></label>
  <input type='email' class='forgot-input' id='email' placeholder='Enter email' name='email' value='".$emailid."' autocomplete='off' required='true'>
  <div class='error_data'>".$mailerror."</div>
</div>
<div class='form-wrap'>
  <label for='pwd' class='forgot-label'><p>Current Password</p></label>
  <input type='password' class='forgot-input' id='pwd' placeholder='Enter password' name='currentpassword' autocomplete='new-password' required='true' >
  <div class='error_data'>".$current_pass_error."</div>
</div>
<div class=''>
  <label for='pwd' class='forgot-label'><p>New Password</p></label>
  <input type='password' class='forgot-input' id='password' placeholder='Enter password' name='newpassword' autocomplete='new-password' required='true'>
<div class='error_data' id='epassword'>".$matchpassword."</div>
</div>
<div class=''>
  <label for='pwd' class='forgot-label'><p>Confirm Password</p></label>
  <input type='password' class='forgot-input' id='cpassword' placeholder='Confirm Password' name='confirmpassword' autocomplete='new-password' required='true'>
  <div class='error_data' id='econfirmpassword'>".$matchpassword."</div>
</div>
<div class='submit-btn'>
<button type='submit' value='Submit' class='btn btn-default'>Submit</button>
</div>
</form>
</div>
    
    </div>

		";
	}
 function post_update($data){
	$current_user = wp_get_current_user();
	$emailid=$current_user->user_email;
	$message=array();
	if(!empty($data)){
		if($data['email']!=$emailid){
			$message['email']="Email-id Not Match";
      return $message;
		}
		$result = wp_check_password($data['currentpassword'], $current_user->user_pass, $current_user->ID);
		if(empty($result)){
			$message['current_pass_error']="Password Not Match";
      return $message;
		}
		if($data['newpassword'] != $data['confirmpassword']){
			$message['password']="New Password and Confirm Password Not Match";
      return $message;
		}

    if(empty($message)){
        wp_set_password($data['newpassword'], $current_user->ID);
        wp_set_auth_cookie($current_user->ID);
        wp_set_current_user($current_user->ID);
        do_action('wp_login', $current_user->user_login,$current_user);
        $syncstatus=self::passwordSync($current_user->ID,$data['newpassword']);
          if(is_numeric($syncstatus)){
           $message['sucess']="Password changed successfully!";
           return $message;
          }
          
        
    }
		
	}

}

public static function passwordSync($userid,$password){
  global $wpdb;
      $current_user=wp_get_current_user();
       $image_url="";
      $attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$current_user->ID."' and `meta_key`='profile_image'");
      if(!empty($attechment->meta_value)){
      $image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
      if(!empty($image[0])){
         $image_url = $image[0]; 
      }
    }

      $userdata = array(
      'user_login'    =>   $current_user->user_login,
      'user_email'    =>   $current_user->user_email,
      'user_pass'     =>   $password,
      'first_name'    =>   $current_user->first_name,
      'last_name'     =>   $current_user->last_name,
      'user_image'     =>  $image_url); 
       $returnstatus=Syncssodata::moodle_api_register($userdata);
       if(!empty($returnstatus['userid'])){
        $avlcheckp=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."password_sync WHERE `userid`='".$current_user->ID."' and `status`='1'");
          if(empty($avlcheckp)){
          $wpdb->insert($wpdb->prefix.'password_sync',array('userid'=>$current_user->ID,'status'=>'1','createdtime'=>time()));
          }else{
           $wpdb->update($wpdb->prefix.'password_sync',array('id'=>$avlcheckp->id,'userid'=>$current_user->ID,'status'=>'1','updatedtime'=>time()),array('id'=>$avlcheckp->id));
          }
          return $returnstatus['userid']; 
       }
      
}




function my_auth_login($user, $password) {
     global $wpdb;

         $avlssodata=Syncssodata::avl_syncconfigdata();
    if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
      $moodledata=get_user_meta( $user->ID, 'moodle_user_id', true );
      if(!empty($moodledata)){
      delete_user_meta($user->ID, 'moodle_user_id' );
      }
      $image_url="";
      $attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$user->ID."' and `meta_key`='profile_image'");
      if(!empty($attechment->meta_value)){
          $image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
          if(!empty($image[0])){
          $image_url = $image[0]; 
          }
      }
      $useriddata = get_user_by( 'id',$user->ID);
      $userdata = array('user_login'=>$useriddata->user_login,
      'user_email'    =>   $useriddata->user_email,
      'first_name'    =>   get_user_meta( $user->ID, 'first_name', true ),
      'last_name'     =>   get_user_meta( $user->ID, 'last_name', true ),
      'user_image'     =>  $image_url
      );
      $returnstatus=Syncssodata::moodle_api_register($userdata);
      if(!empty($returnstatus['userid'])){
      return $user;
      }          
    }else{
      return $user;
    }

  }

public static function checkpasswordMoodle(){
  global $wpdb; 
    if(is_user_logged_in()){
      $current_url = explode("?",self::current_location());
        if($current_url[0]==site_url('user-account/')){
        }elseif($current_url[0]==site_url('wp-login.php')){

      }else{
        $user_ID = get_current_user_id(); 
        $avlcheckp=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."password_sync WHERE `userid`='".$user_ID."' and `status`='1'");
          if(empty($avlcheckp)){
              wp_redirect(site_url('user-account/?eb-active-link=eb-my-profile&eb_user_account_nav_nonce=cea58f7f40'));
            exit();
          }
        }

    }
  }


 public static function current_location()
{
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

}

 $webhook=new UserpasswordUpdate();