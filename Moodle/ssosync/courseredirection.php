<?php
require_once("../../config.php");
require_once("lib.php");
   global $PAGE, $CFG,$DB;
    $userdata = json_decode(file_get_contents('php://input'), true); 
    if(!empty($userdata)){
        if($userdata=='secure'){
                        $redirecturl=$SESSION->wantsurl;
                     if(!empty($redirecturl)){
                        $redirecturl=$SESSION->wantsurl;
                        echo json_encode($redirecturl);
                     }
        }
    }


