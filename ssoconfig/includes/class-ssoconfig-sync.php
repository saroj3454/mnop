<?php

/**
 * Fired during plugin deactivation
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Sso_Sync
 * @subpackage Sso_Sync/includes
 */

class Syncssodata {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
    public static function ssosyncconfigmenu(){ 
     $position  = 5;   
      add_menu_page('Sync Config', 'Sync Config', 'manage_options', 'syncconfig', 'add_syncconfig','dashicons-admin-tools', $position );
    }




    public static function sso_syncauthretrive(){
        global $wpdb;
        $avlssodata=self::avl_syncconfigdata();
        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
              $data=self::sso_syncauth();  
              if($data=="secure"){
                 return '<div class="success"><i class="fa fa-check-circle"></i>Connected Successfully in moodle & moodle Login Url Change in wordpress url</div>';
                }else{
                    return '<div class="danger"><i class="fa fa-times-circle"></i>Not Connected </div>';
                }
            }
           
    }

    public static function redirect_after_logout(){
            $avlssodata=self::avl_syncconfigdata();
             $pagesdata=self::plugin_pages();
              if(!empty($avlssodata['apiurl'])){
                $redirecturl=$avlssodata['apiurl']."local/ssosync/moodlelogout.php";
                }

            if(empty($pagesdata['login']->guid)){
                 wp_redirect(site_url()); 
            }else{ 
           // $redirecturl=$pagesdata['login']->guid;

            wp_redirect($redirecturl); 
            }  
            exit();

    }

    public static function using_avl_syncconfigdata(){
        return $avlssodata=self::avl_syncconfigdata();
    }

    public static function using_sso_syncauth(){
        return $data=self::sso_syncauth();
    }

    


    private static function sso_syncauth(){
        $avlssodata=self::avl_syncconfigdata();
        if(!empty($avlssodata['apiurl'])){
            $apiurl=$avlssodata['apiurl'];
        }
        if(!empty($avlssodata['clientid'])){
            $clientid=$avlssodata['clientid'];
        }
        if(!empty($avlssodata['secrtekey'])){
            $secrtekey=$avlssodata['secrtekey'];
        }
        $curl = curl_init();
        $senddata=json_encode(array('clientid'=>$clientid,'secretkey'=>$secrtekey,'authstatus'=>'secure'));
        curl_setopt_array($curl, array(
        CURLOPT_URL => $apiurl.'local/ssosync/sync.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$senddata,
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        $result=json_decode($response);
        if(!empty($result)){
             return $result=$result->secure;
        }    
    }
  public static function activator_loginregisterpageurlsync(){
     global $wpdb;
    self::loginregisterpageurlsync();
  }


  private static function loginregisterpageurlsync(){
     global $wpdb;
        $avlssodata=self::avl_syncconfigdata();
        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
              $data=self::sso_syncauth(); 
              if($data=="secure"){     
                    if(!empty($avlssodata['apiurl'])){
                    $apiurl=$avlssodata['apiurl'];
                    }
                    if(!empty($avlssodata['clientid'])){
                    $clientid=$avlssodata['clientid'];
                    }
                    if(!empty($avlssodata['secrtekey'])){
                    $secrtekey=$avlssodata['secrtekey'];
                    }
                        $woocomment=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_key`='_wp_ssoconfig'");
                         $sync=array();
                          foreach ($woocomment as $value) {
                             $datakey=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."posts WHERE `ID`='".$value->post_id."'");
                                if(!empty($datakey)){
                                    $wordpress=array();
                                    $wordpress['postid']=$datakey->ID;
                                    $wordpress['post_title']=$datakey->post_title;
                                    $wordpress['post_url']=$datakey->guid;
                                    array_push($sync, $wordpress);
                                 }
                             }
                            $curl = curl_init();
                            $apisyncurl=$apiurl.'local/ssosync/sync.php';
                            $senddata=json_encode(array('clientid'=>$clientid,'secretkey'=>$secrtekey,'action'=>'wordpressurl','pagedata'=>$sync));
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => $apisyncurl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS =>$senddata,
                            CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                            ),
                            ));
                            curl_exec($curl);

              } 

          }
  }

    private static function insert_sync($data){
        global $wpdb;
        if(!empty($data['submit'])){
            $apiurl=$data['apiurl'];
            $clientid=$data['clientid'];
            $secrtekey=$data['secrtekey'];
            if(!empty($apiurl)){
                if(substr($apiurl, -1) == '/') {
               $fapiurl=$apiurl;
                }else{
                $fapiurl=$apiurl."/";
                } 
            }
            $avldata=$wpdb->get_row("SELECT count(id) as avl FROM ".$wpdb->prefix."sso_syncconfig");
            if(empty($avldata->avl)){
                $wpdb->insert($wpdb->prefix.'sso_syncconfig',array('apiurl'=>$fapiurl,'clientid'=>$clientid,'secrtekey'=>$secrtekey,'created'=>time()));
               $return="Insert Sucessfully";
            }else{
                $sdata=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."sso_syncconfig limit 0,1");
                if(!empty($sdata)){
                  $wpdb->update($wpdb->prefix.'sso_syncconfig',array('id'=>$sdata->id,'apiurl'=>$fapiurl,'clientid'=>$clientid,'secrtekey'=>$secrtekey,'updated'=>time()),array('id'=>$sdata->id));
                $return="Updated Sucessfully";
                 }
            }

            if(!empty($return)){
                self::loginregisterpageurlsync();
            self::syncdatainsertreturn($return);
            }else{
                self::syncdatainsertreturn("Please contact your it administrator");
            }         
        }     
    } 
    public static function roytuts_on_activation(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sso_syncconfig=$wpdb->prefix . 'sso_syncconfig';
        $sso_syncconfig_sql="CREATE TABLE IF NOT EXISTS $sso_syncconfig (
        id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        apiurl longtext NULL,
        clientid longtext NULL,
        secrtekey longtext NULL,
        created bigint(20)  NULL,
        updated bigint(20)  NULL) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sso_syncconfig_sql);
    }
    public static function syncdatainsertreturn($data){
        if(!empty($data)){
            if($data=='Please contact your it administrator'){
                echo'<div class="alert alert-danger" role="alert">'.$data.'</div>';
            }else{
                echo'<div class="alert alert-success" role="alert">'.$data.'</div>'; 
            }
           
        }
    }

    public static function plugin_pages() {
        global $wpdb;
          $loginpage=$wpdb->get_row("SELECT p.ID,p.post_title,p.guid FROM ".$wpdb->prefix."postmeta as pm inner join ".$wpdb->prefix."posts as p on pm.post_id=p.ID WHERE pm.meta_key='_wp_ssoconfig' and pm.meta_value='login.php'");
          $registerpage=$wpdb->get_row("SELECT p.ID,p.post_title,p.guid FROM ".$wpdb->prefix."postmeta as pm inner join ".$wpdb->prefix."posts as p on pm.post_id=p.ID WHERE pm.meta_key='_wp_ssoconfig' and pm.meta_value='register.php'");
             $logintomoodle=$wpdb->get_row("SELECT p.ID,p.post_title,p.guid FROM ".$wpdb->prefix."postmeta as pm inner join ".$wpdb->prefix."posts as p on pm.post_id=p.ID WHERE pm.meta_key='_wp_ssoconfig' and pm.meta_value='logintomoodle.php'");
          $data=array();
          $data['login']=$loginpage;
          $data['register']=$registerpage;
          $data['logintomoodle']=$logintomoodle;
          return $data;
    }


    public static function add_syncconfig(){
        global $wpdb;
        echo '<div class="moodle_database container">
        <div class="row">
        <div class="custom-card">';
        self::get_syncconfig();
        $avlssodata=self::avl_syncconfigdata();
        if(!empty($avlssodata['apiurl'])){
            $apiurl=$avlssodata['apiurl'];
        }
        if(!empty($avlssodata['clientid'])){
            $clientid=$avlssodata['clientid'];
        }
        if(!empty($avlssodata['secrtekey'])){
            $secrtekey=$avlssodata['secrtekey'];
        }
        echo '<div class="row">
        <div class="heading col-3">
            <h2>Sync Config</h2>
        </div>
        <div class="col-9">'.self::sso_syncauthretrive().'</div>
        </div><form class="form-group" method="post" enctype="multipart/form-data">
        <div class="mb-bottom row">
        <div class="col-3">
            <label class="fs-18">Api Url</label>
        </div>
        <div class="col-9">
            <input type="text" value="'.$apiurl.'" name="apiurl" placeholder="Api Url Ex:-https://moodle-test.digiface.org/" />
        </div>
        </div>


        <div class="mb-bottom row">
        <div class="col-3">
            <label class="fs-18">Client Id</label>
        </div>
        <div class="col-9">
            <input type="text" value="'.$clientid.'" name="clientid" placeholder="Client Id" />
        </div>
        </div>
        <div class="mb-bottom row">
        <div class="col-3">
            <label class="fs-18">Secrte Key</label>
            </div>
            <div class="col-9">
            <input type="text" value="'.$secrtekey.'" name="secrtekey" placeholder="Secrte Key" />
            </div>
        </div>
        <div>
            <input type="submit" value="submit" name="submit" class="custom-btn"/>
        </div>
        </form>
        </div>
        </div>
        </div>
    ';
    self::userdatasync();
    } 
    private static function avl_syncconfigdata(){
         global $wpdb;
      $sdata=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."sso_syncconfig limit 0,1");
       if(!empty($sdata)){
        $data=array();
        $data['apiurl']=$sdata->apiurl;
        $data['clientid']=$sdata->clientid;
        $data['secrtekey']=$sdata->secrtekey;
        return $data;
       }
    } 
   public static function userdatasync(){
       return wp_enqueue_style('custom-css', site_url().'/wp-content/plugins/ssoconfig/css/admin.css',false, '1.0.0', 'all');
    }
    public static function get_syncconfig(){
        echo self::insert_sync($_POST);
    } 
    public static function frontweblogin(){
       self::ssoconfig_login_shordcode();
    }
    private static function syncuser_sessionstart($userdata){
       return self::moodle_api_register($userdata); 
    }    


    private static function userloginauth(){
         global $wpdb;
      $username = $wpdb->escape($_POST['lusername']);  
      $password = $wpdb->escape($_POST['lpassword']);  
      $remember = $wpdb->escape($_POST['remember']);  
        if($remember){
           $remember = "true";  
       } 
      else{
        $remember = "false";
        } 

            $return=array(); 
          if(empty($username) && empty($password)){
            $return['input_staus']="Username and Password cannot be blank";
          }else{
                    $logindata=array();
                    $logindata['user_login'] = $username;  
                    $logindata['user_password'] = $password;  
                    $logindata['remember'] = $remember;
                     $user_verify = wp_signon($logindata,false);   
                      if (is_wp_error($user_verify) )   
                      {  
                           $return['input_staus']="The username or password you entered is incorrect."; 
                      } else
                      {   
                        

                             $user = get_user_by('login', $user_verify->user_login);
                            wp_clear_auth_cookie();
                            wp_set_current_user($user->ID);                         
                             wp_set_auth_cookie($user->ID);                           
                            $current_user=wp_get_current_user();
							                 $user_id=$user->ID;
                            $useriddata = get_user_by( 'id',$user_id );
							// $image_url="";
							// $attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$user_id."' and `meta_key`='profile_image'");
							// 	if(!empty($attechment->meta_value)){
							// 	$image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
							// 	if(!empty($image[0])){
							// 	   $image_url = $image[0]; 
							// 	}

							// 	}
                            // $current_userdata = array(
                            // 'user_login'    =>   $current_user->user_login,
                            // 'user_email'    =>   $current_user->user_email,
                            // 'user_pass'     =>   $current_user->user_pass,
                            // 'first_name'    =>   $current_user->first_name,
                            // 'last_name'     =>   $current_user->last_name,
                            // 'user_image'     =>  $image_url,
                            // );
                            // $returnstatus=self::syncuser_sessionstart($current_userdata);
                            // if(!empty($returnstatus['userid'])){
                                wp_redirect(site_url());
                                exit();  
                            // } 
                      }   
                 return $return;      
               }
    }
    private static function ssoconfig_login_shordcode(){
                 global $wpdb;
          if(is_user_logged_in()){
            wp_redirect(site_url());
          }
       $pagesdata=self::plugin_pages();
       $userlogin=self::userloginauth();
       if(!empty($userlogin['input_staus'])){
        $error='<p style="color:#FF0000; text-aling:left;text-indent: 25px;"><strong>ERROR</strong>: '.$userlogin['input_staus']. '<br /></p>';
       }
        echo '<div class="breadcrumbs ">
<div class="wrapper wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
    <span><span><a href="'.site_url().'" data-wpel-link="internal">Home</a>  <span class="breadcrumb_last" aria-current="page">'.$pagesdata['login']->post_title.'</span></span></span>    </div>
</div>
<div class="custom-container-fluid">
<div class="custom-container"> 
    <div class="cutom-col-md-9">'.$error.'
        <div>
            <form method="post" autocomplete="off">            
                <div class="p-25">
                    <label >Email*</label>
                    <input type="text" name="lusername" id="email" required="true" class="mt-10"/ >
                </div>              
                 <div class="p-25">
                    <label >Password*</label>
                    <div class="input-wrap mt-10">
                    <input type="password" name="lpassword"  id="lpassword" required="true" class="input-border-none" autocomplete="new-password"  / >
                   
                        <i class="fa fa-eye-slash  sshow" aria-hidden="true"></i>
                        </div>
                </div>          
                <div class="inline-input p-25">
                    <div class="custom-w-49">                     
                    </div>
                    <div class="custom-w-49 text-right">
                      <a href="'.site_url('password-reset').'">Forgot password?</a>
                    </div>
                 </div>
            <div class="p-25">
                   <input type="checkbox" name="remember" value="true"/>
                   Remember me
                </div>
                <div class="p-25">
                    <button type="submit" name="userlogin" value="userregister" class="reg-btn">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="custome-col-md-3">
        <div class="custom-text-center">
            <p>
               Don\'t have an Account? Register one!
            </p>
        <a href="'.$pagesdata['register']->guid.'">    <button  type="button" class="log-btn"> 
                Get Registered
            </button></a>
        </div>
    </div>
</div>
</div>';
    }
 public static function validatelogintomoodle_tokenauth(){
    self::logintomoodle_tokenauth();
 }


    private static function logintomoodle_tokenauth(){
             global $wpdb; 
         if(is_user_logged_in()){
               $pagesdata=self::plugin_pages();
                if(empty($pagesdata['login']->guid)){
                    echo "<p style='color:red;'>Please check Sso configuration pages(not uploaded)</p>";
                }else{

                        $avlssodata=self::avl_syncconfigdata();    
                        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
                          $data=self::sso_syncauth(); 
                              if($data=="secure"){     
                                if(!empty($avlssodata['apiurl'])){
                                $apiurl=$avlssodata['apiurl'];
                                }
                                if(!empty($avlssodata['clientid'])){
                                $clientid=$avlssodata['clientid'];
                                }
                                if(!empty($avlssodata['secrtekey'])){
                                $secrtekey=$avlssodata['secrtekey'];
                                }
                                   $current_user=wp_get_current_user();
                                    $current_userdata = array(
                                    'user_login'    =>   $current_user->user_login,
                                    'user_email'    =>   $current_user->user_email,
                                    'user_pass'     =>   $current_user->user_pass,
                                    'first_name'    =>   $current_user->first_name,
                                    'last_name'     =>   $current_user->last_name); 
                                    $curl = curl_init();
                                     $senddata=json_encode($current_userdata);
                                    curl_setopt_array($curl, array(
                                    CURLOPT_URL => $apiurl.'local/ssosync/authtoken.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS =>$senddata,
                                    CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                    ),
                                    ));
                                    $response = curl_exec($curl);
                                    $token=json_decode($response);
                                    wp_redirect( $apiurl.'local/ssosync/usersession.php?token='.$token);
                                    exit;

                                }else{
                               echo"<p style='color:red;'>Sso configuration not connected</p>"; 
                                }

                         }else{
                            echo"<p style='color:red;'>Sso configuration not connected</p>";
                        }
                    }
        }else{
                       $pagesdata=self::plugin_pages();
                        if(empty($pagesdata['login']->guid)){
                            echo "<p style='color:red;'>Please check Sso configuration pages(not uploaded)</p>";
                        }else{ 
                        $redirecturl=$pagesdata['login']->guid;
                        wp_redirect($redirecturl); 
                        }  
            } 
    }

    public static function moodleredirectionurl(){
        $avlssodata=self::avl_syncconfigdata();    
                        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
                          $data=self::sso_syncauth(); 
                              if($data=="secure"){    
                                if(!empty($avlssodata['apiurl'])){
                                $apiurl=$avlssodata['apiurl'];
                                }
                                if(!empty($avlssodata['clientid'])){
                                $clientid=$avlssodata['clientid'];
                                }
                                if(!empty($avlssodata['secrtekey'])){
                                $secrtekey=$avlssodata['secrtekey'];
                                }
                                    $curl = curl_init();
                                    $senddata=json_encode('secure');
                                    curl_setopt_array($curl, array(
                                    CURLOPT_URL => $apiurl.'local/ssosync/courseredirection.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS =>$senddata,
                                    CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json'
                                    ),
                                    ));
                                    $response = curl_exec($curl);
                                   return $redirecturl=json_decode($response);


                            }
                        }
    }


    public static function webpage_register_post(){
        global $wpdb; 
        if(!empty($_POST['userregister'])){
                global $reg_errors, $user_ID;
                $return=array();
                    $reg_errors = new WP_Error;
                    $username=strtolower(trim($_POST['email']," "));
                  $useremail=strtolower(trim($_POST['email']," "));
                  $password=$_POST['password'];
                  $pass_confirm=$_POST['cpassword'];
                  $first_name = $_POST['fname'];
                  $last_name= $_POST['lname'];
                  $centre= $_POST['centre'];
                  $institution= $_POST['institution'];
                  $userstatus= $_POST['user-status'];
                  if(!empty($_POST['programme'])){
                    $programme=$_POST['programme'];
                  }
                  $origin= $_POST['origin'];
                  $currentcountry= $_POST['current-country'];
                  $protection= $_POST['protection'];
                  $terms= $_POST['terms'];
 
            if(empty($user_ID)){
                if(empty( $username ) || empty( $useremail ) || empty($password) || empty($password) || empty($pass_confirm) || empty($first_name) || empty($last_name) || empty($centre) || empty($userstatus) || empty($origin) || empty($currentcountry) || empty($protection) || empty($terms))
                {
                  $reg_errors->add('field', 'Required form field is missing');
                }   

                if ( 6 > strlen($username))
                {
                  $reg_errors->add('username_length', 'Username too short. At least 6 characters is required' );
                }
                if ( username_exists( $username ) )
                {
                  $reg_errors->add('user_name', 'The username you entered already exists!');
                }
                if ( ! validate_username( $username ) )
                {
                  $reg_errors->add( 'username_invalid', 'The username you entered is not valid!' );
                }
                if ( !is_email( $useremail ) )
                {
                  $reg_errors->add( 'email_invalid', 'Email id is not valid!' );
                }
                
                if ( email_exists( $useremail ) )
                {
                  $reg_errors->add( 'email', 'This email already exists. Please use a different email to register.' );
                }
                   
                   if ( 6 > strlen( $password ) ) {
                    $reg_errors->add( 'password', 'Your password must contain at least 6 characters.' );
                  } 
                  
                  if($password != $pass_confirm) {
                  // passwords do not match
                   $reg_errors->add( 'password_mismatch', 'Your passwords do not match. Please enter your passwords again.' );
                 }
                    if (is_wp_error( $reg_errors ))
                    { 
                        foreach ( $reg_errors->get_error_messages() as $error )
                        {
                          
                        $signUpError='<p style="color:#FF0000; text-aling:left;text-indent: 25px;"><strong>ERROR</strong>: '.$error . '<br /></p>';
                        $return['error']=$signUpError;

                        } 
                    }

                        global $username, $useremail;
                        $username   =   sanitize_user(strtolower($_POST['email']));
                        $useremail  =   sanitize_email(strtolower($_POST['email']));
                        $password   =   esc_attr( $_POST['password'] );
                        $userdata = array(
                        'user_login'    =>   $username,
                        'user_email'    =>   $useremail,
                        'user_pass'     =>   $password,
                        'first_name'    =>   $first_name,
                        'last_name'     =>   $last_name,
                        );
                    $userid = wp_insert_user( $userdata );
                    if ( !is_wp_error( $userid ) ) {
                          update_user_meta($userid,'institution',$institution);
                          update_user_meta($userid,'user_centre',$centre);
                        update_user_meta($userid,'location',$origin);
                        update_user_meta($userid,'user_nationality',$currentcountry);
                        update_user_meta($userid,'user_status_daad',$userstatus);
                        update_user_meta($userid,'programme_name',$programme);

                        // add_user_meta( $userid, 'user_centre',$center );
                        // add_user_meta( $userid, 'user_country_of_birth',$currentcountry);
                        // add_user_meta( $userid, 'user_current_country',$currentcountry);
                        // add_user_meta( $userid, 'user_status_daad',$userstatus);
                        $user = get_user_by('login', $username );
                        wp_clear_auth_cookie();
                        wp_set_current_user($user->ID); 
                        wp_set_auth_cookie($user->ID); 

                      
                        $image_url="";
                        $attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$user->ID."' and `meta_key`='profile_image'");
                        if(!empty($attechment->meta_value)){
                        $image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
                        if(!empty($image[0])){
                           $image_url = $image[0]; 
                        }

                        }
                         $alluserdata = array(
                        'user_login'    =>   $username,
                        'user_email'    =>   $useremail,
                        'user_pass'     =>   $password,
                        'first_name'    =>   $first_name,
                        'last_name'     =>   $last_name,
                        'user_image'     =>  $image_url
                        );
                        $returnstatus=self::moodle_api_register($alluserdata);
                        if(!empty($returnstatus['userid'])){
                            wp_redirect(site_url('wp-admin/profile.php'));
                        }                      
                    }   
            }      
 return $return;
        }
    }
   
   public static function moodle_api_register($userdata){
      global $wpdb;
      $return=array();
        $avlssodata=self::avl_syncconfigdata();
         
        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
              $data=self::sso_syncauth(); 
            if($data=="secure"){     
                if(!empty($avlssodata['apiurl'])){
                $apiurl=$avlssodata['apiurl'];
                }
                if(!empty($avlssodata['clientid'])){
                $clientid=$avlssodata['clientid'];
                }
                if(!empty($avlssodata['secrtekey'])){
                $secrtekey=$avlssodata['secrtekey'];
                }
            $curl = curl_init();
           $apisyncurl=$apiurl.'local/ssosync/sync.php';
            $senddata=json_encode(array('clientid'=>$clientid,'secretkey'=>$secrtekey,'action'=>'moodleregister','userdata'=>$userdata));
            curl_setopt_array($curl, array(
            CURLOPT_URL => $apisyncurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$senddata,
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
            ),
            ));
            $response = curl_exec($curl);
            $result=json_decode($response);


            $return['synconnect_status']=$result->status;
            $return['userid']=$result->userid;
               }else{
                $return['synconnect_status']="fail";
               }
               return $return;
            }
   }

  public static function wordpresslogout(){
     $pagesdata=self::plugin_pages();                   
    wp_clear_auth_cookie();
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_logout(); 
                        if(empty($pagesdata['login']->guid)){
                           wp_redirect(site_url());
                        }else{ 
                        $redirecturl=$pagesdata['login']->guid;
                        wp_redirect($redirecturl); 
                        } 
    
  }

  public static function listinstitute(){
    $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => site_url().'/wp-json/institutions/v1/list-institutions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Cookie: PHPSESSID=knplb5anm8h5h28p2opi70jhv7'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return json_decode($response);
  }

    public static function webpage_register(){
     

        if(is_user_logged_in()){
            wp_redirect(site_url());
        }
        $pagesdata=self::plugin_pages();
         $datareturn=self::webpage_register_post();
         $error="";
         if(!empty($datareturn['error'])){
            $error=$datareturn['error'];
         }
         $email="";
         if(!empty($_POST['email'])){
            $email=$_POST['email'];
         }
         $password="";
        if(!empty($_POST['password'])){
            $password=$_POST['password'];
         }
         $cpassword="";
        if(!empty($_POST['cpassword'])){
            $cpassword=$_POST['cpassword'];
         }
         $fname="";
        if(!empty($_POST['fname'])){
            $fname=$_POST['fname'];
         }
         $lname="";
        if(!empty($_POST['lname'])){
            $lname=$_POST['lname'];
         }
         $centre="";
        if(!empty($_POST['centre'])){
          $centre=$_POST['centre'];
         }
            $userstatus="";
          if(!empty($_POST['user-status'])){
            $userstatus=$_POST['user-status'];
         }
         $origin = "";
        if(!empty($_POST['origin'])){
              $origin=$_POST['origin'];
         }
         $currentcountry="";
        if(!empty($_POST['current-country'])){
            $currentcountry=$_POST['current-country'];
         }
         $institution="";
         if(!empty($_POST['institution'])){
            $institution=$_POST['institution'];
         }
         $listinstitutedata=array();
         foreach (self::listinstitute() as $value) {
          array_push($listinstitutedata,'<option value="'.$value->name.'" '.(($institution==$value->name)?" selected ":"").'>'.$value->name.'</option>');
         }



        // if(!empty($_POST['protection'])){

        //  }
        // if(!empty($_POST['terms'])){

        //  }
       

        echo '<div class="breadcrumbs ">
<div class="wrapper wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
    <span><span><a href="'.site_url().'" data-wpel-link="internal">Home</a>  <span class="breadcrumb_last" aria-current="page">'.$pagesdata['register']->post_title.'</span></span></span>    </div>
</div>

<div class="custom-container-fluid">
<div class="custom-container"> 


    <div class="cutom-col-md-10">'.$error.'
        <div>
            <form method="post" autocomplete="off" id="user-registration-form">
                <div class="inline-input p-25">
                    <div class="custom-w-49">
                        <div>
                            <label >First name</label>
                            <input type="text" name="fname" value="'.$fname.'" id="fname"   class="mt-10"/>
                        </div>
                        <div id="fnameerror" style="position:absolute;"></div>   
                </div>
                <div class="custom-w-49">
                    <div>
                        <label >Last name</label>
                        <input type="text" name="lname" value="'.$lname.'" id="lname"  class="mt-10"/>
                    </div>
                    <div id="lnameerror" style="position:absolute;"></div> 
                </div>
                </div>
                <div class="p-25">
                    <label >Email*</label>
                    <input type="email" name="email" value="'.$email.'"  id="email" class="mt-10"/ >
                </div>
                <div class="inline-input p-25">
                    <div class="custom-w-49">
                    <label >Password*</label>
                    <input type="password" name="password"  value="'.$password.'" id="password"  class="mt-10" autocomplete="new-password"/>
                </div>
                <div class="custom-w-49">
                    <label >Confirm Password*</label>
                    <input type="password" name="cpassword" value="'.$cpassword.'" id="cpassword" class="mt-10" />
                </div>
                <div id="perror"></div>   
                </div>

                  <div class="p-25 institutionSelect">
                    <label for="institution" >Institution*</label>
                    <div >
                    <select id="institution" name="institution" class="selectpicker" data-show-subtext="true" data-live-search="true" >
                        <option value="">Select institution</option>
                       '.implode(" ",$listinstitutedata).'
                    </select>
                    </div>
                    <span class="institutionerror"></span>
                </div>

                <div class="p-25">
                    <label for="centre" >Centre*</label>
                    <select id="centre" name="centre" class="mt-10" >

                       
                    </select>
                </div>

                <div class="p-25">
                    <label for="user-status" >
                    Status*
                    <button type="button" class="info-popup">ⓘ</button>
                </label>
                <select id="user-status" name="user-status" class="mt-10">
                    <option value="">Select Status</option>
                    <option value="Student of a Centre of Excellence" '.(($userstatus == "Student of a Centre of Excellence")?" selected ":"").'>Student of a Centre of Excellence</option>
                    <option value="Funded Student with DAAD Scholarship of a Centre of Excellence" '.(($userstatus == "Funded Student with DAAD Scholarship of a Centre of Excellence")?" selected ":"").'>Funded Student with DAAD Scholarship of a Centre of Excellence</option>

                    <option value="Alumni of a Centre of Excellence" '.(($userstatus == "Alumni of a Centre of Excellence")?" selected ":"").'>Alumni of a Centre of Excellence</option>
                    <option value="Funded Alumni with DAAD Scholarship of a Centre of Excellence" '.(($userstatus == "Funded Alumni with DAAD Scholarship of a Centre of Excellence")?" selected ":"").'>Funded Alumni with DAAD Scholarship of a Centre of Excellence</option>
                    <option value="Lecturer of a Centre of Excellence" '.(($userstatus == "Lecturer of a Centre of Excellence")?" selected ":"").'>Lecturer of a Centre of Excellence</option>
                    <option value="Staff of a Centre of Excellence" '.(($userstatus == "Staff of a Centre of Excellence")?" selected ":"").'>Staff of a Centre of Excellence</option>
                    <option value="Staff of DIGI-FACE" '.(($userstatus == "Staff of DIGI-FACE")?" selected ":"").'>Staff of DIGI-FACE</option>
                    <option value="Staff of DAAD" '.(($userstatus == "Staff of DAAD")?" selected ":"").'>Staff of DAAD</option>
                    <option value="External Student" '.(($userstatus == "External Student")?" selected ":"").'>External Student</option>
                    <option value="External Lecturer" '.(($userstatus == "External Lecturer")?" selected ":"").'>External Lecturer</option>
                    <option value="External staff" '.(($userstatus == "External staff")?" selected ":"").'>External staff</option>
                    <option value="External user/other" '.(($userstatus == "External user/other")?" selected ":"").'>External user/other</option>
                    <option value="Funded student with other DAAD scholarship" '.(($userstatus == "Funded student with other DAAD scholarship")?" selected ":"").'>Funded student with other DAAD scholarship</option>

                    <option value="Staff/Lecturers of other DAAD funded programme" '.(($userstatus == "Staff/Lecturers of other DAAD funded programme")?" selected ":"").'>Staff/Lecturers of other DAAD funded programme</option>

                                        </select>
                </div>

                <div class="p-25" style="display:none" id="programmefield">
                    <label >Programme Name</label>
                    <input type="text" name="programme" id="programme" value="'.$programme.'"  class="mt-10" />
                </div>

                <div class="p-25">
                    <label for="origin">
                    Country of birth*
                    </label>
                    <select id="origin" name="origin"  class="mt-10">
                        <option value="" >Select Country</option>
                        <option value="afghanistan" '.(($origin == "afghanistan")?" selected ":"").'>Afghanistan</option>
                        <option value="albania" '.(($origin == "albania")?" selected ":"").'>Albania</option>
                        <option value="algeria" '.(($origin == "algeria")?" selected ":"").'>Algeria</option>
                        <option value="american_samoa" '.(($origin == "american_samoa")?" selected ":"").'>American Samoa</option>
                        <option value="andorra" '.(($origin == "andorra")?" selected ":"").'>Andorra</option>
                        <option value="angola" '.(($origin == "angola")?" selected ":"").'>Angola</option>
                        <option value="anguilla" '.(($origin == "anguilla")?" selected ":"").'>Anguilla</option>
                        <option value="antigua_and_barbuda" '.(($origin == "antigua_and_barbuda")?" selected ":"").'>Antigua and Barbuda</option>
                        <option value="argentina" '.(($origin == "argentina")?" selected ":"").'>Argentina</option>
                        <option value="armenia" '.(($origin == "armenia")?" selected ":"").'>Armenia</option>
                        <option value="aruba" '.(($origin == "aruba")?" selected ":"").'>Aruba</option>
                        <option value="australia" '.(($origin == "australia")?" selected ":"").'>Australia</option>
                        <option value="austria" '.(($origin == "austria")?" selected ":"").'>Austria</option>
                        <option value="azerbaijan" '.(($origin == "azerbaijan")?" selected ":"").'>Azerbaijan</option>
                        <option value="bahamas" '.(($origin == "bahamas")?" selected ":"").'>Bahamas</option>
                        <option value="bahrain" '.(($origin == "bahrain")?" selected ":"").'>Bahrain</option>
                        <option value="bangladesh" '.(($origin == "bangladesh")?" selected ":"").'>Bangladesh</option>
                        <option value="barbados" '.(($origin == "barbados")?" selected ":"").'>Barbados</option>
                        <option value="belarus" '.(($origin == "belarus")?" selected ":"").'>Belarus</option>
                        <option value="belgium" '.(($origin == "belgium")?" selected ":"").'>Belgium</option>
                        <option value="belize" '.(($origin == "belize")?" selected ":"").'>Belize</option>
                        <option value="benin" '.(($origin == "benin")?" selected ":"").'>Benin</option>
                        <option value="bermuda" '.(($origin == "bermuda")?" selected ":"").'>Bermuda</option>
                        <option value="bhutan" '.(($origin == "bhutan")?" selected ":"").'>Bhutan</option>
                        <option value="bolivia"  '.(($origin == "bolivia")?" selected ":"").'>Bolivia</option>
                        <option value="bosnia-herzegovina" '.(($origin == "bosnia-herzegovina")?" selected ":"").'>Bosnia-Herzegovina</option>
                        <option value="botswana" '.(($origin == "botswana")?" selected ":"").'>Botswana</option>
                        <option value="bouvet_island"  '.(($origin == "bouvet_island")?" selected ":"").'>Bouvet Island</option>
                        <option value="brazil"  '.(($origin == "brazil")?" selected ":"").'>Brazil</option>
                        <option value="brunei"  '.(($origin == "brunei")?" selected ":"").'>Brunei</option>
                        <option value="bulgaria"  '.(($origin == "bulgaria")?" selected ":"").'>Bulgaria</option>
                        <option value="burkina_faso" '.(($origin == "burkina_faso")?" selected ":"").'>Burkina Faso</option>
                        <option value="burundi" '.(($origin == "burundi")?" selected ":"").'>Burundi</option>
                        <option value="cambodia" '.(($origin == "cambodia")?" selected ":"").'>Cambodia</option>
                        <option value="cameroon" '.(($origin == "cameroon")?" selected ":"").'>Cameroon</option>
                        <option value="canada" '.(($origin == "canada")?" selected ":"").'>Canada</option>
                        <option value="cape_verde" '.(($origin == "cape_verde")?" selected ":"").'>Cape Verde</option>
                        <option value="cayman_islands" '.(($origin == "cayman_islands")?" selected ":"").'>Cayman Islands</option>
                        <option value="central_african_republic" '.(($origin == "central_african_republic")?" selected ":"").'>Central African Republic</option>
                        <option value="chad"  '.(($origin == "chad")?" selected ":"").'>Chad</option>
                        <option value="chile"  '.(($origin == "chile")?" selected ":"").'>Chile</option>
                        <option value="china"  '.(($origin == "china")?" selected ":"").'>China</option>
                        <option value="christmas_island" '.(($origin == "christmas_island")?" selected ":"").'>Christmas Island</option>
                        <option value="cocos_(keeling)_islands" '.(($origin == "cocos_(keeling)_islands")?" selected ":"").'>C  </option>
                        <option value="colombia" '.(($origin == "colombia")?" selected ":"").'>Colombia</option>
                        <option value="comoros"  '.(($origin == "comoros")?" selected ":"").'>Comoros</option>
                        <option value="congo" '.(($origin == "congo")?" selected ":"").'>Congo</option>
                        <option value="cook_islands" '.(($origin == "cook_islands")?" selected ":"").'>Cook Islands</option>
                        <option value="costa_rica" '.(($origin == "costa_rica")?" selected ":"").'>Costa Rica</option>
                        <option value="croatia" '.(($origin == "croatia")?" selected ":"").'>Croatia</option>
                        <option value="cuba" '.(($origin == "cuba")?" selected ":"").'>Cuba</option>
                        <option value="cyprus" '.(($origin == "cyprus")?" selected ":"").'>Cyprus</option>
                        <option value="czech_republic" '.(($origin == "czech_republic")?" selected ":"").'>Czech Republic</option>
                        <option value="democratic_republic_of_congo"  '.(($origin == "democratic_republic_of_congo")?" selected ":"").'>Democratic Republic of Congo</option>
                        <option value="denmark" '.(($origin == "denmark")?" selected ":"").'>Denmark</option>
                        <option value="djibouti" '.(($origin == "djibouti")?" selected ":"").'>Djibouti</option>
                        <option value="dominica" '.(($origin == "dominica")?" selected ":"").'>Dominica</option>
                        <option value="dominican_republic" '.(($origin == "dominican_republic")?" selected ":"").'>Dominican Republic</option>
                        <option value="ecuador" '.(($origin == "ecuador")?" selected ":"").'>Ecuador</option>
                        <option value="egypt" '.(($origin == "egypt")?" selected ":"").'>Egypt</option>
                        <option value="el_salvador" '.(($origin == "el_salvador")?" selected ":"").'>El Salvador</option>
                        <option value="equatorial_guinea" '.(($origin == "equatorial_guinea")?" selected ":"").'>Equatorial Guinea</option>
                        <option value="eritrea" '.(($origin == "eritrea")?" selected ":"").'>Eritrea</option>
                        <option value="estonia" '.(($origin == "estonia")?" selected ":"").'>Estonia</option>
                        <option value="ethiopia" '.(($origin == "ethiopia")?" selected ":"").'>Ethiopia</option>
                        <option value="falkland_islands" '.(($origin == "falkland_islands")?" selected ":"").'>Falkland Islands</option>
                        <option value="faroe_islands" '.(($origin == "faroe_islands")?" selected ":"").'>Faroe Islands</option>
                        <option value="fiji" '.(($origin == "fiji")?" selected ":"").'>Fiji</option>
                        <option value="finland" '.(($origin == "finland")?" selected ":"").'>Finland</option>
                        <option value="france" '.(($origin == "france")?" selected ":"").'>France</option>
                        <option value="french_guiana" '.(($origin == "french_guiana")?" selected ":"").'>French Guiana</option>
                        <option value="gabon" '.(($origin == "gabon")?" selected ":"").'>Gabon</option>
                        <option value="gambia" '.(($origin == "gambia")?" selected ":"").'>Gambia</option>
                        <option value="georgia" '.(($origin == "georgia")?" selected ":"").'>Georgia</option>
                        <option value="germany" '.(($origin == "germany")?" selected ":"").'>Germany</option>
                        <option value="ghana" '.(($origin == "ghana")?" selected ":"").'>Ghana</option>
                        <option value="gibraltar" '.(($origin == "gibraltar")?" selected ":"").'>Gibraltar</option>
                        <option value="greece" '.(($origin == "greece")?" selected ":"").'>Greece</option>
                        <option value="greenland" '.(($origin == "greenland")?" selected ":"").'>Greenland</option>
                        <option value="grenada" '.(($origin == "grenada")?" selected ":"").'>Grenada</option>
                        <option value="guadeloupe_(french)" '.(($origin == "guadeloupe_(french)")?" selected ":"").'>Guadeloupe (French)</option>
                        <option value="guam_(usa)" '.(($origin == "guam_(usa)")?" selected ":"").'>Guam (USA)</option>
                        <option value="guatemala" '.(($origin == "guatemala")?" selected ":"").'>Guatemala</option>
                        <option value="guinea"  '.(($origin == "guinea")?" selected ":"").'>Guinea</option>
                        <option value="guinea_bissau"  '.(($origin == "guinea_bissau")?" selected ":"").'>Guinea Bissau</option>
                        <option value="guyana"  '.(($origin == "guyana")?" selected ":"").'>Guyana</option>
                        <option value="haiti"  '.(($origin == "haiti")?" selected ":"").'>Haiti</option>
                        <option value="honduras"  '.(($origin == "honduras")?" selected ":"").'>Honduras</option>
                        <option value="hong_kong"  '.(($origin == "hong_kong")?" selected ":"").'>Hong Kong</option>
                        <option value="hungary"  '.(($origin == "hungary")?" selected ":"").'>Hungary</option>
                        <option value="iceland" '.(($origin == "iceland")?" selected ":"").'>Iceland</option>
                        <option value="india" '.(($origin == "india")?" selected ":"").'>India</option>
                        <option value="indonesia" '.(($origin == "indonesia")?" selected ":"").'>Indonesia</option>
                        <option value="iran" '.(($origin == "iran")?" selected ":"").'>Iran</option>
                        <option value="iraq" '.(($origin == "iraq")?" selected ":"").'>Iraq</option>
                        <option value="ireland" '.(($origin == "ireland")?" selected ":"").'>Ireland</option>
                        <option value="israel" '.(($origin == "israel")?" selected ":"").'>Israel</option>
                        <option value="italy" '.(($origin == "italy")?" selected ":"").'>Italy</option>
                        <option value="ivory_coast_(cote_divoire)" '.(($origin == "ivory_coast_(cote_divoire)")?" selected ":"").'>Ivory Coast (Cote D`Ivoire)</option>
                        <option value="jamaica" '.(($origin == "jamaica")?" selected ":"").'>Jamaica</option>
                        <option value="japan" '.(($origin == "japan")?" selected ":"").'>Japan</option>
                        <option value="jordan" '.(($origin == "jordan")?" selected ":"").'>Jordan</option>
                        <option value="kazakhstan" '.(($origin == "kazakhstan")?" selected ":"").'>Kazakhstan</option>
                        <option value="kenya" '.(($origin == "kenya")?" selected ":"").'>Kenya</option>
                        <option value="kiribati" '.(($origin == "kiribati")?" selected ":"").'>Kiribati</option>
                        <option value="kuwait" '.(($origin == "kuwait")?" selected ":"").'>Kuwait</option>
                        <option value="kyrgyzstan" '.(($origin == "kyrgyzstan")?" selected ":"").'>Kyrgyzstan</option>
                        <option value="laos" '.(($origin == "laos")?" selected ":"").'>Laos</option>
                        <option value="latvia" '.(($origin == "latvia")?" selected ":"").'>Latvia</option>
                        <option value="lebanon" '.(($origin == "lebanon")?" selected ":"").'>Lebanon</option>
                        <option value="lesotho" '.(($origin == "lesotho")?" selected ":"").'>Lesotho</option>
                        <option value="liberia" '.(($origin == "liberia")?" selected ":"").'>Liberia</option>
                        <option value="libya" '.(($origin == "libya")?" selected ":"").'>Libya</option>
                        <option value="liechtenstein" '.(($origin == "liechtenstein")?" selected ":"").'>Liechtenstein</option>
                        <option value="lithuania"  '.(($origin == "lithuania")?" selected ":"").'>Lithuania</option>
                        <option value="luxembourg" '.(($origin == "luxembourg")?" selected ":"").'>Luxembourg</option>
                        <option value="macau" '.(($origin == "macau")?" selected ":"").'>Macau</option>
                        <option value="macedonia" '.(($origin == "macedonia")?" selected ":"").'>Macedonia</option>
                        <option value="madagascar" '.(($origin == "madagascar")?" selected ":"").'>Madagascar</option>
                        <option value="malawi" '.(($origin == "malawi")?" selected ":"").'>Malawi</option>
                        <option value="malaysia" '.(($origin == "malaysia")?" selected ":"").'>Malaysia</option>
                        <option value="maldives" '.(($origin == "maldives")?" selected ":"").'>Maldives</option>
                        <option value="mali" '.(($origin == "mali")?" selected ":"").'>Mali</option>
                        <option value="malta" '.(($origin == "malta")?" selected ":"").'>Malta</option>
                        <option value="marshall_islands" '.(($origin == "marshall_islands")?" selected ":"").'>Marshall Islands</option>
                        <option value="martinique_(french)" '.(($origin == "martinique_(french)")?" selected ":"").'>Martinique (French)</option>
                        <option value="mauritania" '.(($origin == "mauritania")?" selected ":"").'>Mauritania</option>
                        <option value="mauritius" '.(($origin == "mauritius")?" selected ":"").'>Mauritius</option>
                        <option value="mayotte" '.(($origin == "mayotte")?" selected ":"").'>Mayotte</option>
                        <option value="mexico" '.(($origin == "mexico")?" selected ":"").'>Mexico</option>
                        <option value="micronesia" '.(($origin == "micronesia")?" selected ":"").'>Micronesia</option>
                        <option value="moldova" '.(($origin == "moldova")?" selected ":"").'>Moldova</option>
                        <option value="monaco" '.(($origin == "monaco")?" selected ":"").'>Monaco</option>
                        <option value="mongolia" '.(($origin == "mongolia")?" selected ":"").'>Mongolia</option>
                        <option value="montenegro" '.(($origin == "montenegro")?" selected ":"").'>Montenegro</option>
                        <option value="montserrat" '.(($origin == "montserrat")?" selected ":"").'>Montserrat</option>
                        <option value="morocco" '.(($origin == "morocco")?" selected ":"").'>Morocco</option>
                        <option value="mozambique" '.(($origin == "mozambique")?" selected ":"").'>Mozambique</option>
                        <option value="myanmar" '.(($origin == "myanmar")?" selected ":"").'>Myanmar</option>
                        <option value="namibia" '.(($origin == "namibia")?" selected ":"").'>Namibia</option>
                        <option value="nauru" '.(($origin == "nauru")?" selected ":"").'>Nauru</option>
                        <option value="nepal" '.(($origin == "nepal")?" selected ":"").'>Nepal</option>
                        <option value="netherlands" '.(($origin == "netherlands")?" selected ":"").'>Netherlands</option>
                        <option value="netherlands_antilles" '.(($origin == "netherlands_antilles")?" selected ":"").'>Netherlands Antilles</option>
                        <option value="new_caledonia_(french)" '.(($origin == "new_caledonia_(french)")?" selected ":"").'>New Caledonia (French)</option>
                        <option value="new_zealand" '.(($origin == "new_zealand")?" selected ":"").'>New Zealand</option>
                        <option value="nicaragua" '.(($origin == "nicaragua")?" selected ":"").'>Nicaragua</option>
                        <option value="niger" '.(($origin == "niger")?" selected ":"").'>Niger</option>
                        <option value="nigeria" '.(($origin == "nigeria")?" selected ":"").'>Nigeria</option>
                        <option value="niue" '.(($origin == "niue")?" selected ":"").'>Niue</option>
                        <option value="norfolk_island" '.(($origin == "norfolk_island")?" selected ":"").'>Norfolk Island</option>
                        <option value="northern_mariana_islands"  '.(($origin == "northern_mariana_islands")?" selected ":"").'>Northern Mariana Islands</option>
                        <option value="norway" '.(($origin == "norway")?" selected ":"").'>Norway</option>
                        <option value="oman" '.(($origin == "oman")?" selected ":"").'>Oman</option>
                        <option value="pakistan" '.(($origin == "pakistan")?" selected ":"").'>Pakistan</option>
                        <option value="palau" '.(($origin == "palau")?" selected ":"").'>Palau</option>
                        <option value="panama" '.(($origin == "panama")?" selected ":"").'>Panama</option>
                        <option value="papua_new_guinea" '.(($origin == "papua_new_guinea")?" selected ":"").'>Papua New Guinea</option>
                        <option value="paraguay" '.(($origin == "paraguay")?" selected ":"").'>Paraguay</option>
                        <option value="peru" '.(($origin == "peru")?" selected ":"").'>Peru</option>
                        <option value="philippines" '.(($origin == "philippines")?" selected ":"").'>Philippines</option>
                        <option value="pitcairn_island" '.(($origin == "pitcairn_island")?" selected ":"").'>Pitcairn Island</option>
                        <option value="poland" '.(($origin == "poland")?" selected ":"").'>Poland</option>
                        <option value="polynesia_(french)" '.(($origin == "polynesia_(french)")?" selected ":"").'>Polynesia (French)</option>
                        <option value="portugal"  '.(($origin == "portugal")?" selected ":"").'>Portugal</option>
                        <option value="puerto_rico"  '.(($origin == "puerto_rico")?" selected ":"").'>Puerto Rico</option>
                        <option value="qatar"  '.(($origin == "qatar")?" selected ":"").'>Qatar</option>
                        <option value="reunion"  '.(($origin == "reunion")?" selected ":"").'>Reunion</option>
                        <option value="romania"  '.(($origin == "romania")?" selected ":"").'>Romania</option>
                        <option value="russia"  '.(($origin == "russia")?" selected ":"").'>Russia</option>
                        <option value="rwanda" '.(($origin == "rwanda")?" selected ":"").'>Rwanda</option>
                        <option value="saint_helena" '.(($origin == "saint_helena")?" selected ":"").'>Saint Helena</option>
                        <option value="saint_kitts_and_nevis" '.(($origin == "saint_kitts_and_nevis")?" selected ":"").'>Saint Kitts and Nevis</option>
                        <option value="saint_lucia" '.(($origin == "saint_lucia")?" selected ":"").'>Saint Lucia</option>
                        <option value="saint_pierre_and_miquelon" '.(($origin == "saint_pierre_and_miquelon")?" selected ":"").'>Saint Pierre and Miquelon</option>
                        <option value="saint_vincent_and_grenadines" '.(($origin == "saint_vincent_and_grenadines")?" selected ":"").'>Saint Vincent and Grenadines</option>
                        <option value="samoa" '.(($origin == "samoa")?" selected ":"").'>Samoa</option>
                        <option value="san_marino" '.(($origin == "san_marino")?" selected ":"").'>San Marino</option>
                        <option value="sao_tome_and_principe" '.(($origin == "sao_tome_and_principe")?" selected ":"").'>Sao Tome and Principe</option>
                        <option value="saudi_arabia" '.(($origin == "saudi_arabia")?" selected ":"").'>Saudi Arabia</option>
                        <option value="senegal" '.(($origin == "senegal")?" selected ":"").'>Senegal</option>
                        <option value="serbia" '.(($origin == "serbia")?" selected ":"").'>Serbia</option>
                        <option value="seychelles" '.(($origin == "seychelles")?" selected ":"").'>Seychelles</option>
                        <option value="sierra_leone" '.(($origin == "sierra_leone")?" selected ":"").'>Sierra Leone</option>
                        <option value="singapore" '.(($origin == "singapore")?" selected ":"").'>Singapore</option>
                        <option value="slovakia" '.(($origin == "slovakia")?" selected ":"").'>Slovakia</option>
                        <option value="slovenia" '.(($origin == "slovenia")?" selected ":"").'>Slovenia</option>
                        <option value="solomon_islands" '.(($origin == "solomon_islands")?" selected ":"").'>Solomon Islands</option>
                        <option value="somalia" '.(($origin == "somalia")?" selected ":"").'>Somalia</option>
                        <option value="south_africa" '.(($origin == "south_africa")?" selected ":"").'>South Africa</option>
                        <option value="south_sudan" '.(($origin == "south_sudan")?" selected ":"").'>South Sudan</option>
                        <option value="spain" '.(($origin == "spain")?" selected ":"").'>Spain</option>
                        <option value="sri_lanka" '.(($origin == "sri_lanka")?" selected ":"").'>Sri Lanka</option>
                        <option value="sudan" '.(($origin == "sudan")?" selected ":"").'>Sudan</option>
                        <option value="suriname" '.(($origin == "suriname")?" selected ":"").'>Suriname</option>
                        <option value="swaziland" '.(($origin == "swaziland")?" selected ":"").'>Swaziland</option>
                        <option value="sweden" '.(($origin == "sweden")?" selected ":"").'>Sweden</option>
                        <option value="switzerland" '.(($origin == "switzerland")?" selected ":"").'>Switzerland</option>
                        <option value="syria" '.(($origin == "syria")?" selected ":"").'>Syria</option>
                        <option value="taiwan" '.(($origin == "taiwan")?" selected ":"").'>Taiwan</option>
                        <option value="tajikistan" '.(($origin == "tajikistan")?" selected ":"").'>Tajikistan</option>
                        <option value="tanzania" '.(($origin == "tanzania")?" selected ":"").'>Tanzania</option>
                        <option value="thailand" '.(($origin == "thailand")?" selected ":"").'>Thailand</option>
                        <option value="togo" '.(($origin == "togo")?" selected ":"").'>Togo</option>
                        <option value="tokelau" '.(($origin == "tokelau")?" selected ":"").'>Tokelau</option>
                        <option value="tonga" '.(($origin == "tonga")?" selected ":"").'>Tonga</option>
                        <option value="trinidad_and_tobago"  '.(($origin == "trinidad_and_tobago")?" selected ":"").'>Trinidad and Tobago</option>
                        <option value="tunisia" '.(($origin == "tunisia")?" selected ":"").'>Tunisia</option>
                        <option value="turkey" '.(($origin == "turkey")?" selected ":"").'>Turkey</option>
                        <option value="turkmenistan" '.(($origin == "turkmenistan")?" selected ":"").'>Turkmenistan</option>
                        <option value="turks_and_caicos_islands" '.(($origin == "turks_and_caicos_islands")?" selected ":"").'>Turks and Caicos Islands</option>
                        <option value="tuvalu" '.(($origin == "tuvalu")?" selected ":"").'>Tuvalu</option>
                        <option value="uganda" '.(($origin == "uganda")?" selected ":"").'>Uganda</option>
                        <option value="ukraine" '.(($origin == "ukraine")?" selected ":"").'>Ukraine</option>
                        <option value="united_arab_emirates" '.(($origin == "united_arab_emirates")?" selected ":"").'>United Arab Emirates</option>
                        <option value="united_kingdom" '.(($origin == "united_kingdom")?" selected ":"").'>United Kingdom</option>
                        <option value="united_states" '.(($origin == "united_states")?" selected ":"").'>United States</option>
                        <option value="uruguay" '.(($origin == "uruguay")?" selected ":"").'>Uruguay</option>
                        <option value="uzbekistan" '.(($origin == "uzbekistan")?" selected ":"").'>Uzbekistan</option>
                        <option value="vanuatu" '.(($origin == "vanuatu")?" selected ":"").'>Vanuatu</option>
                        <option value="venezuela" '.(($origin == "venezuela")?" selected ":"").'>Venezuela</option>
                        <option value="vietnam" '.(($origin == "vietnam")?" selected ":"").'>Vietnam</option>
                        <option value="virgin_islands" '.(($origin == "virgin_islands")?" selected ":"").'>Virgin Islands</option>
                        <option value="wallis_and_futuna_islands" '.(($origin == "wallis_and_futuna_islands")?" selected ":"").'>Wallis and Futuna Islands</option>
                        <option value="yemen" '.(($origin == "yemen")?" selected ":"").'>Yemen</option>
                        <option value="zambia" '.(($origin == "zambia")?" selected ":"").'>Zambia</option>
                        <option value="zimbabwe" '.(($origin == "zimbabwe")?" selected ":"").'>Zimbabwe</option>
                    </select>
                </div>

                <div class="p-25">
                    <label for="current-country">
                    Current country*
                    </label>
                    <select id="current-country" name="current-country"  class="mt-10">
                    <option value="">Select Country</option>
                    <option value="afghanistan" '.(($currentcountry == "afghanistan")?" selected ":"").'>Afghanistan</option>
                        <option value="albania" '.(($currentcountry == "albania")?" selected ":"").'>Albania</option>
                        <option value="algeria" '.(($currentcountry == "algeria")?" selected ":"").'>Algeria</option>
                        <option value="american_samoa" '.(($currentcountry == "american_samoa")?" selected ":"").'>American Samoa</option>
                        <option value="andorra" '.(($currentcountry == "andorra")?" selected ":"").'>Andorra</option>
                        <option value="angola" '.(($currentcountry == "angola")?" selected ":"").'>Angola</option>
                        <option value="anguilla" '.(($currentcountry == "anguilla")?" selected ":"").'>Anguilla</option>
                        <option value="antigua_and_barbuda" '.(($currentcountry == "antigua_and_barbuda")?" selected ":"").'>Antigua and Barbuda</option>
                        <option value="argentina" '.(($currentcountry == "argentina")?" selected ":"").'>Argentina</option>
                        <option value="armenia" '.(($currentcountry == "armenia")?" selected ":"").'>Armenia</option>
                        <option value="aruba" '.(($currentcountry == "aruba")?" selected ":"").'>Aruba</option>
                        <option value="australia" '.(($currentcountry == "australia")?" selected ":"").'>Australia</option>
                        <option value="austria" '.(($currentcountry == "austria")?" selected ":"").'>Austria</option>
                        <option value="azerbaijan" '.(($currentcountry == "azerbaijan")?" selected ":"").'>Azerbaijan</option>
                        <option value="bahamas" '.(($currentcountry == "bahamas")?" selected ":"").'>Bahamas</option>
                        <option value="bahrain" '.(($currentcountry == "bahrain")?" selected ":"").'>Bahrain</option>
                        <option value="bangladesh" '.(($currentcountry == "bangladesh")?" selected ":"").'>Bangladesh</option>
                        <option value="barbados" '.(($currentcountry == "barbados")?" selected ":"").'>Barbados</option>
                        <option value="belarus" '.(($currentcountry == "belarus")?" selected ":"").'>Belarus</option>
                        <option value="belgium" '.(($currentcountry == "belgium")?" selected ":"").'>Belgium</option>
                        <option value="belize" '.(($currentcountry == "belize")?" selected ":"").'>Belize</option>
                        <option value="benin" '.(($currentcountry == "benin")?" selected ":"").'>Benin</option>
                        <option value="bermuda" '.(($currentcountry == "bermuda")?" selected ":"").'>Bermuda</option>
                        <option value="bhutan" '.(($currentcountry == "bhutan")?" selected ":"").'>Bhutan</option>
                        <option value="bolivia"  '.(($currentcountry == "bolivia")?" selected ":"").'>Bolivia</option>
                        <option value="bosnia-herzegovina" '.(($currentcountry == "bosnia-herzegovina")?" selected ":"").'>Bosnia-Herzegovina</option>
                        <option value="botswana" '.(($currentcountry == "botswana")?" selected ":"").'>Botswana</option>
                        <option value="bouvet_island"  '.(($currentcountry == "bouvet_island")?" selected ":"").'>Bouvet Island</option>
                        <option value="brazil"  '.(($currentcountry == "brazil")?" selected ":"").'>Brazil</option>
                        <option value="brunei"  '.(($currentcountry == "brunei")?" selected ":"").'>Brunei</option>
                        <option value="bulgaria"  '.(($currentcountry == "bulgaria")?" selected ":"").'>Bulgaria</option>
                        <option value="burkina_faso" '.(($currentcountry == "burkina_faso")?" selected ":"").'>Burkina Faso</option>
                        <option value="burundi" '.(($currentcountry == "burundi")?" selected ":"").'>Burundi</option>
                        <option value="cambodia" '.(($currentcountry == "cambodia")?" selected ":"").'>Cambodia</option>
                        <option value="cameroon" '.(($currentcountry == "cameroon")?" selected ":"").'>Cameroon</option>
                        <option value="canada" '.(($currentcountry == "canada")?" selected ":"").'>Canada</option>
                        <option value="cape_verde" '.(($currentcountry == "cape_verde")?" selected ":"").'>Cape Verde</option>
                        <option value="cayman_islands" '.(($currentcountry == "cayman_islands")?" selected ":"").'>Cayman Islands</option>
                        <option value="central_african_republic" '.(($currentcountry == "central_african_republic")?" selected ":"").'>Central African Republic</option>
                        <option value="chad"  '.(($currentcountry == "chad")?" selected ":"").'>Chad</option>
                        <option value="chile"  '.(($currentcountry == "chile")?" selected ":"").'>Chile</option>
                        <option value="china"  '.(($currentcountry == "china")?" selected ":"").'>China</option>
                        <option value="christmas_island" '.(($currentcountry == "christmas_island")?" selected ":"").'>Christmas Island</option>
                        <option value="cocos_(keeling)_islands" '.(($currentcountry == "cocos_(keeling)_islands")?" selected ":"").'>C  </option>
                        <option value="colombia" '.(($currentcountry == "colombia")?" selected ":"").'>Colombia</option>
                        <option value="comoros"  '.(($currentcountry == "comoros")?" selected ":"").'>Comoros</option>
                        <option value="congo" '.(($currentcountry == "congo")?" selected ":"").'>Congo</option>
                        <option value="cook_islands" '.(($currentcountry == "cook_islands")?" selected ":"").'>Cook Islands</option>
                        <option value="costa_rica" '.(($currentcountry == "costa_rica")?" selected ":"").'>Costa Rica</option>
                        <option value="croatia" '.(($currentcountry == "croatia")?" selected ":"").'>Croatia</option>
                        <option value="cuba" '.(($currentcountry == "cuba")?" selected ":"").'>Cuba</option>
                        <option value="cyprus" '.(($currentcountry == "cyprus")?" selected ":"").'>Cyprus</option>
                        <option value="czech_republic" '.(($currentcountry == "czech_republic")?" selected ":"").'>Czech Republic</option>
                        <option value="democratic_republic_of_congo"  '.(($currentcountry == "democratic_republic_of_congo")?" selected ":"").'>Democratic Republic of Congo</option>
                        <option value="denmark" '.(($currentcountry == "denmark")?" selected ":"").'>Denmark</option>
                        <option value="djibouti" '.(($currentcountry == "djibouti")?" selected ":"").'>Djibouti</option>
                        <option value="dominica" '.(($currentcountry == "dominica")?" selected ":"").'>Dominica</option>
                        <option value="dominican_republic" '.(($currentcountry == "dominican_republic")?" selected ":"").'>Dominican Republic</option>
                        <option value="ecuador" '.(($currentcountry == "ecuador")?" selected ":"").'>Ecuador</option>
                        <option value="egypt" '.(($currentcountry == "egypt")?" selected ":"").'>Egypt</option>
                        <option value="el_salvador" '.(($currentcountry == "el_salvador")?" selected ":"").'>El Salvador</option>
                        <option value="equatorial_guinea" '.(($currentcountry == "equatorial_guinea")?" selected ":"").'>Equatorial Guinea</option>
                        <option value="eritrea" '.(($currentcountry == "eritrea")?" selected ":"").'>Eritrea</option>
                        <option value="estonia" '.(($currentcountry == "estonia")?" selected ":"").'>Estonia</option>
                        <option value="ethiopia" '.(($currentcountry == "ethiopia")?" selected ":"").'>Ethiopia</option>
                        <option value="falkland_islands" '.(($currentcountry == "falkland_islands")?" selected ":"").'>Falkland Islands</option>
                        <option value="faroe_islands" '.(($currentcountry == "faroe_islands")?" selected ":"").'>Faroe Islands</option>
                        <option value="fiji" '.(($currentcountry == "fiji")?" selected ":"").'>Fiji</option>
                        <option value="finland" '.(($currentcountry == "finland")?" selected ":"").'>Finland</option>
                        <option value="france" '.(($currentcountry == "france")?" selected ":"").'>France</option>
                        <option value="french_guiana" '.(($currentcountry == "french_guiana")?" selected ":"").'>French Guiana</option>
                        <option value="gabon" '.(($currentcountry == "gabon")?" selected ":"").'>Gabon</option>
                        <option value="gambia" '.(($currentcountry == "gambia")?" selected ":"").'>Gambia</option>
                        <option value="georgia" '.(($currentcountry == "georgia")?" selected ":"").'>Georgia</option>
                        <option value="germany" '.(($currentcountry == "germany")?" selected ":"").'>Germany</option>
                        <option value="ghana" '.(($currentcountry == "ghana")?" selected ":"").'>Ghana</option>
                        <option value="gibraltar" '.(($currentcountry == "gibraltar")?" selected ":"").'>Gibraltar</option>
                        <option value="greece" '.(($currentcountry == "greece")?" selected ":"").'>Greece</option>
                        <option value="greenland" '.(($currentcountry == "greenland")?" selected ":"").'>Greenland</option>
                        <option value="grenada" '.(($currentcountry == "grenada")?" selected ":"").'>Grenada</option>
                        <option value="guadeloupe_(french)" '.(($currentcountry == "guadeloupe_(french)")?" selected ":"").'>Guadeloupe (French)</option>
                        <option value="guam_(usa)" '.(($currentcountry == "guam_(usa)")?" selected ":"").'>Guam (USA)</option>
                        <option value="guatemala" '.(($currentcountry == "guatemala")?" selected ":"").'>Guatemala</option>
                        <option value="guinea"  '.(($currentcountry == "guinea")?" selected ":"").'>Guinea</option>
                        <option value="guinea_bissau"  '.(($currentcountry == "guinea_bissau")?" selected ":"").'>Guinea Bissau</option>
                        <option value="guyana"  '.(($currentcountry == "guyana")?" selected ":"").'>Guyana</option>
                        <option value="haiti"  '.(($currentcountry == "haiti")?" selected ":"").'>Haiti</option>
                        <option value="honduras"  '.(($currentcountry == "honduras")?" selected ":"").'>Honduras</option>
                        <option value="hong_kong"  '.(($currentcountry == "hong_kong")?" selected ":"").'>Hong Kong</option>
                        <option value="hungary"  '.(($currentcountry == "hungary")?" selected ":"").'>Hungary</option>
                        <option value="iceland" '.(($currentcountry == "iceland")?" selected ":"").'>Iceland</option>
                        <option value="india" '.(($currentcountry == "india")?" selected ":"").'>India</option>
                        <option value="indonesia" '.(($currentcountry == "indonesia")?" selected ":"").'>Indonesia</option>
                        <option value="iran" '.(($currentcountry == "iran")?" selected ":"").'>Iran</option>
                        <option value="iraq" '.(($currentcountry == "iraq")?" selected ":"").'>Iraq</option>
                        <option value="ireland" '.(($currentcountry == "ireland")?" selected ":"").'>Ireland</option>
                        <option value="israel" '.(($currentcountry == "israel")?" selected ":"").'>Israel</option>
                        <option value="italy" '.(($currentcountry == "italy")?" selected ":"").'>Italy</option>
                        <option value="ivory_coast_(cote_d`ivoire)" '.(($currentcountry == "ivory_coast_(cote_d`ivoire)")?" selected ":"").'>Ivory Coast (Cote D`Ivoire)</option>
                        <option value="jamaica" '.(($currentcountry == "jamaica")?" selected ":"").'>Jamaica</option>
                        <option value="japan" '.(($currentcountry == "japan")?" selected ":"").'>Japan</option>
                        <option value="jordan" '.(($currentcountry == "jordan")?" selected ":"").'>Jordan</option>
                        <option value="kazakhstan" '.(($currentcountry == "kazakhstan")?" selected ":"").'>Kazakhstan</option>
                        <option value="kenya" '.(($currentcountry == "kenya")?" selected ":"").'>Kenya</option>
                        <option value="kiribati" '.(($currentcountry == "kiribati")?" selected ":"").'>Kiribati</option>
                        <option value="kuwait" '.(($currentcountry == "kuwait")?" selected ":"").'>Kuwait</option>
                        <option value="kyrgyzstan" '.(($currentcountry == "kyrgyzstan")?" selected ":"").'>Kyrgyzstan</option>
                        <option value="laos" '.(($currentcountry == "laos")?" selected ":"").'>Laos</option>
                        <option value="latvia" '.(($currentcountry == "latvia")?" selected ":"").'>Latvia</option>
                        <option value="lebanon" '.(($currentcountry == "lebanon")?" selected ":"").'>Lebanon</option>
                        <option value="lesotho" '.(($currentcountry == "lesotho")?" selected ":"").'>Lesotho</option>
                        <option value="liberia" '.(($currentcountry == "liberia")?" selected ":"").'>Liberia</option>
                        <option value="libya" '.(($currentcountry == "libya")?" selected ":"").'>Libya</option>
                        <option value="liechtenstein" '.(($currentcountry == "liechtenstein")?" selected ":"").'>Liechtenstein</option>
                        <option value="lithuania"  '.(($currentcountry == "lithuania")?" selected ":"").'>Lithuania</option>
                        <option value="luxembourg" '.(($currentcountry == "luxembourg")?" selected ":"").'>Luxembourg</option>
                        <option value="macau" '.(($currentcountry == "macau")?" selected ":"").'>Macau</option>
                        <option value="macedonia" '.(($currentcountry == "macedonia")?" selected ":"").'>Macedonia</option>
                        <option value="madagascar" '.(($currentcountry == "madagascar")?" selected ":"").'>Madagascar</option>
                        <option value="malawi" '.(($currentcountry == "malawi")?" selected ":"").'>Malawi</option>
                        <option value="malaysia" '.(($currentcountry == "malaysia")?" selected ":"").'>Malaysia</option>
                        <option value="maldives" '.(($currentcountry == "maldives")?" selected ":"").'>Maldives</option>
                        <option value="mali" '.(($currentcountry == "mali")?" selected ":"").'>Mali</option>
                        <option value="malta" '.(($currentcountry == "malta")?" selected ":"").'>Malta</option>
                        <option value="marshall_islands" '.(($currentcountry == "marshall_islands")?" selected ":"").'>Marshall Islands</option>
                        <option value="martinique_(french)" '.(($currentcountry == "martinique_(french)")?" selected ":"").'>Martinique (French)</option>
                        <option value="mauritania" '.(($currentcountry == "mauritania")?" selected ":"").'>Mauritania</option>
                        <option value="mauritius" '.(($currentcountry == "mauritius")?" selected ":"").'>Mauritius</option>
                        <option value="mayotte" '.(($currentcountry == "mayotte")?" selected ":"").'>Mayotte</option>
                        <option value="mexico" '.(($currentcountry == "mexico")?" selected ":"").'>Mexico</option>
                        <option value="micronesia" '.(($currentcountry == "micronesia")?" selected ":"").'>Micronesia</option>
                        <option value="moldova" '.(($currentcountry == "moldova")?" selected ":"").'>Moldova</option>
                        <option value="monaco" '.(($currentcountry == "monaco")?" selected ":"").'>Monaco</option>
                        <option value="mongolia" '.(($currentcountry == "mongolia")?" selected ":"").'>Mongolia</option>
                        <option value="montenegro" '.(($currentcountry == "montenegro")?" selected ":"").'>Montenegro</option>
                        <option value="montserrat" '.(($currentcountry == "montserrat")?" selected ":"").'>Montserrat</option>
                        <option value="morocco" '.(($currentcountry == "morocco")?" selected ":"").'>Morocco</option>
                        <option value="mozambique" '.(($currentcountry == "mozambique")?" selected ":"").'>Mozambique</option>
                        <option value="myanmar" '.(($currentcountry == "myanmar")?" selected ":"").'>Myanmar</option>
                        <option value="namibia" '.(($currentcountry == "namibia")?" selected ":"").'>Namibia</option>
                        <option value="nauru" '.(($currentcountry == "nauru")?" selected ":"").'>Nauru</option>
                        <option value="nepal" '.(($currentcountry == "nepal")?" selected ":"").'>Nepal</option>
                        <option value="netherlands" '.(($currentcountry == "netherlands")?" selected ":"").'>Netherlands</option>
                        <option value="netherlands_antilles" '.(($currentcountry == "netherlands_antilles")?" selected ":"").'>Netherlands Antilles</option>
                        <option value="new_caledonia_(french)" '.(($currentcountry == "new_caledonia_(french)")?" selected ":"").'>New Caledonia (French)</option>
                        <option value="new_zealand" '.(($currentcountry == "new_zealand")?" selected ":"").'>New Zealand</option>
                        <option value="nicaragua" '.(($currentcountry == "nicaragua")?" selected ":"").'>Nicaragua</option>
                        <option value="niger" '.(($currentcountry == "niger")?" selected ":"").'>Niger</option>
                        <option value="nigeria" '.(($currentcountry == "nigeria")?" selected ":"").'>Nigeria</option>
                        <option value="niue" '.(($currentcountry == "niue")?" selected ":"").'>Niue</option>
                        <option value="norfolk_island" '.(($currentcountry == "norfolk_island")?" selected ":"").'>Norfolk Island</option>
                        <option value="northern_mariana_islands"  '.(($currentcountry == "northern_mariana_islands")?" selected ":"").'>Northern Mariana Islands</option>
                        <option value="norway" '.(($currentcountry == "norway")?" selected ":"").'>Norway</option>
                        <option value="oman" '.(($currentcountry == "oman")?" selected ":"").'>Oman</option>
                        <option value="pakistan" '.(($currentcountry == "pakistan")?" selected ":"").'>Pakistan</option>
                        <option value="palau" '.(($currentcountry == "palau")?" selected ":"").'>Palau</option>
                        <option value="panama" '.(($currentcountry == "panama")?" selected ":"").'>Panama</option>
                        <option value="papua_new_guinea" '.(($currentcountry == "papua_new_guinea")?" selected ":"").'>Papua New Guinea</option>
                        <option value="paraguay" '.(($currentcountry == "paraguay")?" selected ":"").'>Paraguay</option>
                        <option value="peru" '.(($currentcountry == "peru")?" selected ":"").'>Peru</option>
                        <option value="philippines" '.(($currentcountry == "philippines")?" selected ":"").'>Philippines</option>
                        <option value="pitcairn_island" '.(($currentcountry == "pitcairn_island")?" selected ":"").'>Pitcairn Island</option>
                        <option value="poland" '.(($currentcountry == "poland")?" selected ":"").'>Poland</option>
                        <option value="polynesia_(french)" '.(($currentcountry == "polynesia_(french)")?" selected ":"").'>Polynesia (French)</option>
                        <option value="portugal"  '.(($currentcountry == "portugal")?" selected ":"").'>Portugal</option>
                        <option value="puerto_rico"  '.(($currentcountry == "puerto_rico")?" selected ":"").'>Puerto Rico</option>
                        <option value="qatar"  '.(($currentcountry == "qatar")?" selected ":"").'>Qatar</option>
                        <option value="reunion"  '.(($currentcountry == "reunion")?" selected ":"").'>Reunion</option>
                        <option value="romania"  '.(($currentcountry == "romania")?" selected ":"").'>Romania</option>
                        <option value="russia"  '.(($currentcountry == "russia")?" selected ":"").'>Russia</option>
                        <option value="rwanda" '.(($currentcountry == "rwanda")?" selected ":"").'>Rwanda</option>
                        <option value="saint_helena" '.(($currentcountry == "saint_helena")?" selected ":"").'>Saint Helena</option>
                        <option value="saint_kitts_and_nevis" '.(($currentcountry == "saint_kitts_and_nevis")?" selected ":"").'>Saint Kitts and Nevis</option>
                        <option value="saint_lucia" '.(($currentcountry == "saint_lucia")?" selected ":"").'>Saint Lucia</option>
                        <option value="saint_pierre_and_miquelon" '.(($currentcountry == "saint_pierre_and_miquelon")?" selected ":"").'>Saint Pierre and Miquelon</option>
                        <option value="saint_vincent_and_grenadines" '.(($currentcountry == "saint_vincent_and_grenadines")?" selected ":"").'>Saint Vincent and Grenadines</option>
                        <option value="samoa" '.(($currentcountry == "samoa")?" selected ":"").'>Samoa</option>
                        <option value="san_marino" '.(($currentcountry == "san_marino")?" selected ":"").'>San Marino</option>
                        <option value="sao_tome_and_principe" '.(($currentcountry == "sao_tome_and_principe")?" selected ":"").'>Sao Tome and Principe</option>
                        <option value="saudi_arabia" '.(($currentcountry == "saudi_arabia")?" selected ":"").'>Saudi Arabia</option>
                        <option value="senegal" '.(($currentcountry == "senegal")?" selected ":"").'>Senegal</option>
                        <option value="serbia" '.(($currentcountry == "serbia")?" selected ":"").'>Serbia</option>
                        <option value="seychelles" '.(($currentcountry == "seychelles")?" selected ":"").'>Seychelles</option>
                        <option value="sierra_leone" '.(($currentcountry == "sierra_leone")?" selected ":"").'>Sierra Leone</option>
                        <option value="singapore" '.(($currentcountry == "singapore")?" selected ":"").'>Singapore</option>
                        <option value="slovakia" '.(($currentcountry == "slovakia")?" selected ":"").'>Slovakia</option>
                        <option value="slovenia" '.(($currentcountry == "slovenia")?" selected ":"").'>Slovenia</option>
                        <option value="solomon_islands" '.(($currentcountry == "solomon_islands")?" selected ":"").'>Solomon Islands</option>
                        <option value="somalia" '.(($currentcountry == "somalia")?" selected ":"").'>Somalia</option>
                        <option value="south_africa" '.(($currentcountry == "south_africa")?" selected ":"").'>South Africa</option>
                        <option value="south_sudan" '.(($currentcountry == "south_sudan")?" selected ":"").'>South Sudan</option>
                        <option value="spain" '.(($currentcountry == "spain")?" selected ":"").'>Spain</option>
                        <option value="sri_lanka" '.(($currentcountry == "sri_lanka")?" selected ":"").'>Sri Lanka</option>
                        <option value="sudan" '.(($currentcountry == "sudan")?" selected ":"").'>Sudan</option>
                        <option value="suriname" '.(($currentcountry == "suriname")?" selected ":"").'>Suriname</option>
                        <option value="swaziland" '.(($currentcountry == "swaziland")?" selected ":"").'>Swaziland</option>
                        <option value="sweden" '.(($currentcountry == "sweden")?" selected ":"").'>Sweden</option>
                        <option value="switzerland" '.(($currentcountry == "switzerland")?" selected ":"").'>Switzerland</option>
                        <option value="syria" '.(($currentcountry == "syria")?" selected ":"").'>Syria</option>
                        <option value="taiwan" '.(($currentcountry == "taiwan")?" selected ":"").'>Taiwan</option>
                        <option value="tajikistan" '.(($currentcountry == "tajikistan")?" selected ":"").'>Tajikistan</option>
                        <option value="tanzania" '.(($currentcountry == "tanzania")?" selected ":"").'>Tanzania</option>
                        <option value="thailand" '.(($currentcountry == "thailand")?" selected ":"").'>Thailand</option>
                        <option value="togo" '.(($currentcountry == "togo")?" selected ":"").'>Togo</option>
                        <option value="tokelau" '.(($currentcountry == "tokelau")?" selected ":"").'>Tokelau</option>
                        <option value="tonga" '.(($currentcountry == "tonga")?" selected ":"").'>Tonga</option>
                        <option value="trinidad_and_tobago"  '.(($currentcountry == "trinidad_and_tobago")?" selected ":"").'>Trinidad and Tobago</option>
                        <option value="tunisia" '.(($currentcountry == "tunisia")?" selected ":"").'>Tunisia</option>
                        <option value="turkey" '.(($currentcountry == "turkey")?" selected ":"").'>Turkey</option>
                        <option value="turkmenistan" '.(($currentcountry == "turkmenistan")?" selected ":"").'>Turkmenistan</option>
                        <option value="turks_and_caicos_islands" '.(($currentcountry == "turks_and_caicos_islands")?" selected ":"").'>Turks and Caicos Islands</option>
                        <option value="tuvalu" '.(($currentcountry == "tuvalu")?" selected ":"").'>Tuvalu</option>
                        <option value="uganda" '.(($currentcountry == "uganda")?" selected ":"").'>Uganda</option>
                        <option value="ukraine" '.(($currentcountry == "ukraine")?" selected ":"").'>Ukraine</option>
                        <option value="united_arab_emirates" '.(($currentcountry == "united_arab_emirates")?" selected ":"").'>United Arab Emirates</option>
                        <option value="united_kingdom" '.(($currentcountry == "united_kingdom")?" selected ":"").'>United Kingdom</option>
                        <option value="united_states" '.(($currentcountry == "united_states")?" selected ":"").'>United States</option>
                        <option value="uruguay" '.(($currentcountry == "uruguay")?" selected ":"").'>Uruguay</option>
                        <option value="uzbekistan" '.(($currentcountry == "uzbekistan")?" selected ":"").'>Uzbekistan</option>
                        <option value="vanuatu" '.(($currentcountry == "vanuatu")?" selected ":"").'>Vanuatu</option>
                        <option value="venezuela" '.(($currentcountry == "venezuela")?" selected ":"").'>Venezuela</option>
                        <option value="vietnam" '.(($currentcountry == "vietnam")?" selected ":"").'>Vietnam</option>
                        <option value="virgin_islands" '.(($currentcountry == "virgin_islands")?" selected ":"").'>Virgin Islands</option>
                        <option value="wallis_and_futuna_islands" '.(($currentcountry == "wallis_and_futuna_islands")?" selected ":"").'>Wallis and Futuna Islands</option>
                        <option value="yemen" '.(($currentcountry == "yemen")?" selected ":"").'>Yemen</option>
                        <option value="zambia" '.(($currentcountry == "zambia")?" selected ":"").'>Zambia</option>
                        <option value="zimbabwe" '.(($currentcountry == "zimbabwe")?" selected ":"").'>Zimbabwe</option>
                                        </select>
                </div>
                <div class="p-25">
                    <input type="checkbox" name="protection"   value="1" id="protection"  /> I confirm to have read and understood<a href="'.site_url('data-protection').'" target="_blank"> data protection statement</a>
                    <div class="pt_error"></div>
                </div>
                <div class="p-25">
                   <input type="checkbox" name="terms"  value="1" id="terms"  />
                    By registering I agree to the <a href="'.site_url('terms-of-use').'" target="_blank">Terms of Use</a> as amended
                     <div class="tm_error"></div>
                </div>
                <div class="p-25">
                    <input type="submit" name="userregister" value="Register" class="reg-btn" >
                        
                </div>
            </form>
        </div>
    </div>
    <div class="custom-col-md-3">
        <div class="custom-text-center">
            <p>
                Already have an account?
            </p>
            <a href="'.$pagesdata['login']->guid.'"><button href="#" type="button" class="log-btn"> 
                Login
            </button></a>
        </div>
    </div>
</div>
</div>';
echo '<style>
.usererror {
  color: red !important;
    
}
</style>';
    }


}