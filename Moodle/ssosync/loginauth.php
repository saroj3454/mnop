<?php
require_once("../../config.php");
  global $PAGE, $CFG,$DB;
  $condata=get_config('local_ssosync');
  
if(!empty($condata->wordpressurl)){
	if (isloggedin()){
		require_logout();
		redirect($condata->wordpressurl.'login/?authlogin=login');
	}else{
		redirect($condata->wordpressurl.'login/?authlogin=login');
	}

}else{
	redirect($CFG->wwwroot."/login/index.php");
}

