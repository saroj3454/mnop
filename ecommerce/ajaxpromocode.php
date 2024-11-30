<?php
require_once('../../config.php');
//require_once($CFG->dirroot."/local/ecommerce/classes/models/promocodeModel.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("HTTP/1.0 200 Successfull operation");
$getpatameter=json_decode(file_get_contents('php://input',True),true);
$functionname = null;
$args = null;
if(is_array($getpatameter)){
    $functionname = $getpatameter['wsfunction'];
    $args = $getpatameter['wsargs'];
}
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

    public function openAddPromoCode($args){
        global $CFG, $PAGE, $OUTPUT , $DB;
    
      $newpdata = array();
        $data = $OUTPUT->render_from_template('local_ecommerce/promocode/add_promocode', $newpdata); 
        $this->sendResponse($data);
    }



  // public function saveNewPromocode($args){
  //       global $CFG, $PAGE, $OUTPUT;
  //       $PCM = new promocodeModel();
  //       if($PCM->savePromocode($args)){
  //           $this->sendResponse("Updated successfully");
  //       } else {
  //           $this->sendError("Operation Failed", "Failed to save product");
  //       }
  //   }
}

$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
        if(is_array($args)){$args = (object)$args;}
        $baseobject->$functionname($args);
}
echo json_encode($baseobject);