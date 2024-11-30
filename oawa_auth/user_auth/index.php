<?php 
include('../../../config.php');
include('../lib.php');

$data = json_decode(file_get_contents('php://input'),true); 

 echo json_encode(user_auth($data));


