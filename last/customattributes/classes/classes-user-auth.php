<?php
class UserAuth{
	public $fileurl;
	function __construct(){
		$this->fileurl=FILE_URL;	
		add_action('user_auth',[ $this,'user_auth_login_page']);
		add_action('wp_enqueue_scripts',[ $this,'jsLibrary']);
		
	}
	public function user_auth_login_page(){
		
			if(empty($_GET['authlogin'])){
					 if(!empty(wc_get_raw_referer())){
				      $_SESSION['url'] =wc_get_raw_referer();
				     
				   }
			     wp_redirect(MoodleURL.'/local/oawa_auth/loginauth.php');  
			}

		if(isset($_POST['loginsubmit'])){
			$resultdata=self::loginpostdata($_POST);
			if($resultdata['status']){
				 wp_redirect($resultdata['redirect']);
        		exit();
			}else{


			get_template_part('template-parts/oawa-login', null,array('login_error'=>$resultdata['error'],'username'=>$resultdata['username'],'password'=>$resultdata['password'],'register_error'=>''));
			}
		}
		if(isset($_POST['registersubmit'])){
			$registerdata=self::registerPostData($_POST);

			if($registerdata['status']){
				 wp_redirect($registerdata['redirect']);
				 exit();
        		
			}else{
			get_template_part('template-parts/oawa-login', null,array('login_error'=>'','username'=>$registerdata['username'],'password'=>$registerdata['password'],'register_error'=>$registerdata['error'],'user_data'=>$registerdata['userdata']));
			
			}
		}
		if(!isset($_POST['loginsubmit']) && !isset($_POST['registersubmit'])){
			get_template_part('template-parts/oawa-login',null,array('login_error'=>'','username'=>'','password'=>'','register_error'=>''));
		}
		
		
	}
	public function jsLibrary(){
		if(is_page_template("login.php")){
			wp_enqueue_script('jquery-validator', $this->fileurl.'js/jquery.validate.min.js',false, '1.0.0', 'all');
			wp_enqueue_script('additional-jquery-validator', $this->fileurl.'js/additional-methods.min.js',false, '1.0.0', 'all');
			wp_enqueue_script('loginjs-lib', $this->fileurl.'js/customlogin.js',false, '1.0.0', 'all');
		}
	}
	private function loginpostdata($userdata){
		//$loginerror=array();
		$logindata=array();
		$logindata['user_login'] = $userdata['username'];  
		$logindata['user_password'] = $userdata['password'];  
		$logindata['remember'] = 'forever';
		$user_verify = wp_signon($logindata,false);
   
		if (is_wp_error($user_verify) )   
    		{  
     		//$return['input_staus']=""; 
     		$msg['status']=false;
     		$msg['msg']="not loggedin";
     		$msg['error']="The username or password you entered is incorrect.";
     		$msg['username']=$userdata['username'];  
     		$msg['password']=$userdata['password'];  
     		return $msg;
    	} else{ 
      		$user = get_user_by('login', $user_verify->user_login);
      		wp_clear_auth_cookie();
      		wp_set_current_user($user->ID);                         
      		wp_set_auth_cookie($user->ID);                           
      		$current_user=wp_get_current_user();
      		$user_id=$user->ID;
			$useriddata = get_user_by( 'id',$user_id );
			$msg['status']=true;
			$msg['msg']='Logged in successfully';
			$msg['redirect']=$userdata['redirect'];
			return $msg;

      	}

	}
	private function registerPostData($userdata){
		$registerdata=array();
		$registerdata['user_pass']=$userdata['password'];
		$registerdata['user_login']=strtolower($userdata['username']);
		$registerdata['user_email']=$userdata['email'];
		$registerdata['display_name']=$userdata['username'];
		$registerdata['first_name']=$userdata['fname'];
		$registerdata['last_name']=$userdata['lname'];
		if(!empty($registerdata['user_pass']) && !empty($registerdata['user_login']) && !empty($registerdata['user_email']) && !empty($registerdata['first_name']) && !empty($registerdata['last_name'])){
			$userregiserid=wp_insert_user($registerdata);
			if (is_wp_error($user_verify) ){

				$msg['status']=false;
				$msg['msg']="something wrong";
				$msg['error']="Something wrong,please try again!";
				$msg['userdata']=$userdata;
				return $msg;

			}else{
				$marketing_mail=isset($userdata['subcription'])?1:0;
				update_user_meta($userregiserid,'marketing_mail',$marketing_mail);
				$logindata=array();
				$logindata['user_login'] = $userdata['username'];  
				$logindata['user_password'] = $userdata['password'];  
				$logindata['remember'] = 'forever';
				$user_verify = wp_signon($logindata,false);
				$msg['status']=true;
				$msg['msg']="data aaded successfully";
				$msg['redirect']=$userdata['redirect'];
				$userdata['wordpressuserid']=$userregiserid;
				$curl = curl_init();
					curl_setopt_array($curl, array(
  					CURLOPT_URL => MoodleURL.'/local/oawa_auth/user-enrollments/',
  					CURLOPT_RETURNTRANSFER => true,
  					CURLOPT_ENCODING => '',
  					CURLOPT_MAXREDIRS => 10,
  					CURLOPT_TIMEOUT => 0,
  					CURLOPT_FOLLOWLOCATION => true,
  					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  					CURLOPT_CUSTOMREQUEST => 'POST',
 				 	CURLOPT_POSTFIELDS => $userdata,
				));

				$response = curl_exec($curl);
				curl_close($curl);
				return $msg;
			} 

		}else{
			$msg['status']=false;
			$msg['msg']="all fields are required";
			$msg['error']="all fields are required";
			$msg['userdata']=$userdata;
			return $msg;
		}


	}


}
$webhook=new UserAuth();