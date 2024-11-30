<?php
require_once('../../config.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("HTTP/1.0 200 Successfull operation");
$getpatameter=$_POST;
$functionname = $_POST['action'];
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
    public static function signupusernamevalidate($data){
    	     global $DB, $OUTPUT, $PAGE, $USER,$CFG; 
    	     $rdata=array();
$userrecord=$DB->get_record_sql("SELECT * FROM {user} where `username`='".$data['username']."' or `email`='".$data['username']."'");
            if(!empty($userrecord)){
            $rdata['avl']="notavl";
            }else{
           $rdata['avl']= "avl";
            }
    echo json_encode($rdata);
   }


}
$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
     $baseobject->$functionname($getpatameter);
}