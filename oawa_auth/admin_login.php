<?php 
require_once("../../config.php");
include('lib.php');

$data = json_decode(file_get_contents('php://input'),true);
 echo json_encode(admin_Auth($data['admin'],$data['userid']));