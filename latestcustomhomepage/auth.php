<?php
require_once('../../config.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("HTTP/1.0 200 Successfull operation");
$getpatameter=$_POST;
$functionname = null;
$args = null;
$functionname = $getpatameter['action'];
class APIManager {
    public $status = 0; 
    public $message = "Error";
    public $data = null;
    public $code = 404;
    public $error = array(
        "code"=> 404,
        "title"=> "Server Error.",
        "message"=> "Server under maintenance"
    );
    function __construct() {
        $this->code = 404;
        $this->error = array(
            "code"=> 404,
            "title"=> "Server Error..",
            "message"=> "Missing functionality"
        );
    }
    public function registerauth($postdata){
    	$data=new stdclass();
       $rdata=$this->fieldfullname($postdata['fullname']);
        if(!empty($postdata['username'])){
         $data->errorusername=$this->usernamecheck($postdata['username']);
              if($data->errorusername=="User name already registered"){
                        $data->fname=$rdata['firstname'];
                        $data->username=$postdata['username'];
                        $data->lname=$rdata['lastname'];
                        $data->email=$postdata['email'];
                         $data->password=$postdata['password'];
                        $data->cpassword=$postdata['confirm_password'];

                    if($this->passwordmatch($postdata['password'],$postdata['confirm_password'])=="pass"){

                    }else{
                        $data->passworderror="Password Not Match";
                    }
                    if ($rdata['error']=='empty lastname') {
                    	$data->fullnameerror="Please Enter full name";
                    }else{

                    }
                    if($this->emailMatch($postdata['email'])=="notavl"){
                    	$data->emailerror="Email address already registered";
                    }

              }elseif($this->passwordmatch($postdata['password'],$postdata['confirm_password'])!="pass"){
                       $data->fname=$rdata['firstname'];
                        $data->username=$postdata['username'];
                        $data->lname=$rdata['lastname'];
                        $data->email=$postdata['email'];
                         $data->password=$postdata['password'];
                        $data->cpassword=$postdata['confirm_password'];

                    if($this->passwordmatch($postdata['password'],$postdata['confirm_password'])=="pass"){

                    }else{
                        $data->passworderror="Password Not Match";
                    }
                    if ($rdata['error']=='empty lastname') {
                    	$data->fullnameerror="Please Enter full name";
                    }else{
                    	
                    }

                    if($this->emailMatch($postdata['email'])=="notavl"){
                    	$data->emailerror="Email address already registered";
                    }

              }elseif($this->emailMatch($postdata['email'])=="notavl"){
                        $data->fname=$rdata['firstname'];
                        $data->username=$postdata['username'];
                        $data->lname=$rdata['lastname'];
                        $data->email=$postdata['email'];
                        $data->password=$postdata['password'];
                        $data->cpassword=$postdata['confirm_password'];
                         $data->emailerror="Email address already registered";
                    if($this->passwordmatch($postdata['password'],$postdata['cpassword'])=="pass"){

                    }else{
                        $data->passworderror="Password Not Match";
                    }
                    if ($rdata['error']=='empty lastname') {
                    	$data->fullnameerror="Please Enter full name";
                    }else{
                    	
                    }

                    if($this->emailMatch($postdata['email'])=="notavl"){
                    	$data->emailerror="Email address already registered";
                    }

              }
              elseif ($rdata['error']=='empty lastname') {
              	      $data->fname=$rdata['firstname'];
                        $data->username=$postdata['username'];
                        $data->lname=$rdata['lastname'];
                        $data->email=$postdata['email'];
                        $data->password=$postdata['password'];
                        $data->cpassword=$postdata['confirm_password'];
                         $data->fullnameerror="Please Enter fullname";
                         
                    if($this->passwordmatch($postdata['password'],$postdata['cpassword'])=="pass"){

                    }else{
                        $data->passworderror="Password Not Match";
                    }
                    if ($rdata['error']=='empty lastname') {
                    	$data->fullnameerror="Please Enter full name";
                    }else{
                    	
                    }

                    if($this->emailMatch($postdata['email'])=="notavl"){
                    	$data->emailerror="Email address already registered";
                    }
               
              }

              if(empty($data->passworderror) && empty($data->emailerror) && empty($data->errorusername) && empty($rdata['error'])){
                  $data->firstname=$rdata['firstname'];
                        $data->username=strtolower($postdata['username']);
                        $data->lastname=$rdata['lastname'];
                        $data->email=strtolower($postdata['email']);
                        $data->password=md5($postdata['password']);
                        $data->cpassword=$postdata['confirm_password'];
                        $data->emailerror="";
                        
               $r=$this->signuppost($data);
                if($r){
                global $CFG,$DB,$SESSION;
                $userdata = $DB->get_record("user", array("id"=>$r));
                complete_user_login($userdata);
                \core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());
                if(!empty($SESSION->wantsurl)){
                $redirecturl=$SESSION->wantsurl;
                }

                
                if(empty($redirecturl)){
                $redirecturl=$CFG->wwwroot;
                }

                redirect($redirecturl);
                exit();
                }
              }else{
              	$_SESSION['messagedata']=$data;
              	$redirecturl=$CFG->wwwroot.'/local/seodashboard/login.php';
              	redirect($redirecturl);
                exit();
                 //return $data;
              }
                             
        }



    }
    public static function fieldfullname($name){
        $data=self::split_name($name);
        $return=array();
        $return['firstname']=$data['0'];
        if(empty($data['1'])){
        $return['error']='empty lastname';
        }else{
        $return['lastname']=$data['1'];
        $return['pass']='pass';
        }
        return $return;
    }
    public static function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
    }


public function emailMatch($email){
     global $DB, $OUTPUT, $PAGE, $USER,$CFG; 
   $userrecord=$DB->get_record_sql("SELECT * FROM {user} where `username`='".$email."' or `email`='".$email."'");
            if(!empty($userrecord)){
            $avl="notavl";
            }else{
            $avl= "avl";
            }
    return $avl;
}

protected function usernamevalidate($username){
     global $DB, $OUTPUT, $PAGE, $USER,$CFG; 
$userrecord=$DB->get_record_sql("SELECT * FROM {user} where `username`='".$username."' or `email`='".$username."'");
            if(!empty($userrecord)){
            $avl="notavl";
            }else{
            $avl= "avl";
            }
    return $avl;
}
protected function usernamecheck($username){
    if($this->usernamevalidate($username)=="notavl"){
      return "User name already registered";  
    }
}
protected function passwordmatch($password,$cpassword){
    if($password==$cpassword){
      return "pass";  
    }
}
protected function signuppost($getdata){
global $DB, $OUTPUT, $PAGE, $USER,$CFG;
    $getdata->mnethostid= 1;
    //$newuserdata= signup_setup_new_user($getdata);
    //$getdata->confirmed=$newuserdata->secret; 
     $getdata->auth="manual";
     $getdata->confirmed= 1;
     $userrecord=$DB->get_record_sql("SELECT * FROM {user} where `username`='".$getdata->username."' or `email`='".$getdata->username."' or `username`='".$getdata->email."' or `email`='".$getdata->email."'");
            if(empty($userrecord)){
    return $postrecord=$DB->insert_record('user',$getdata);
}
       
}


protected function formdatas(){
    $data=new stdclass();
    if(!empty($_POST['signup'])){
        




    }


}



    private function sendResponse($data) {
        $this->status = 1;
        $this->message = "Success";
        $this->data = $data;
        $this->code = 200;
        $this->error = null;
    }
    private function sendError($title, $message, $code=400) {
        $this->status = 0;
        $this->message = "Error";
        $this->data = null;
        $this->code = $code;
        $this->error = array(
            "code"=> $code,
            "title"=> $title,
            "message"=> $message
        );
    }
    public function validatetoken($args){
        global $CFG;
        $this->token = sesskey();
        if($args->token == sesskey()){
            return true;
        } else {
            $this->sendError("error","request not authenticated");
            return false;
        }
    }
    public function emailorusername($data){

    }    
    public static function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
     }
}
$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
    $args = new stdClass();
     $args->token =$getpatameter['token'] ;
        if($baseobject->validatetoken($args)){
            $baseobject->$functionname($getpatameter);
        }
}
