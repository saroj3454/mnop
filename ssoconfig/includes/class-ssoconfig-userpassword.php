<?php class UserpasswordUpdate{
	function __construct(){
		add_shortcode('ss_user_password',[ $this,'userpassword_update']);
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
		}
		$result = wp_check_password($data['currentpassword'], $current_user->user_pass, $current_user->ID);
		if(empty($result)){
			$message['current_pass_error']="Password Not Match";
		}
		if($data['newpassword'] != $data['confirmpassword']){
			$message['password']="New Password and Confirm Password Not Match";
		}

    if(empty($message)){
        wp_set_password($data['newpassword'], $current_user->ID);
        wp_set_auth_cookie($current_user->ID);
        wp_set_current_user($current_user->ID);
        do_action('wp_login', $current_user->user_login,$current_user);
        $message['sucess']="Password changed successfully!";
    }
		
	}
return $message;
}

}

 $webhook=new UserpasswordUpdate();