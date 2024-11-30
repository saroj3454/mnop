<?php 
require_once('../../config.php');
global $DB, $OUTPUT, $PAGE, $USER,$CFG;
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');

// error_reporting(0);
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: access");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Credentials: true");
// header('Content-Type: application/json');
// header("HTTP/1.0 200 Successfull operation");


// $data=json_decode(file_get_contents('php://input',True),true);


$data = json_decode(file_get_contents('php://input'),true); 

 if(!empty($data['admin'])){
    $coursedata=$data['admin'];
    $rdata=woocourseenroledata($coursedata);
   echo json_encode($rdata);
 }