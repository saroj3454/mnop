<?php
require_once("../../config.php");
  global $PAGE, $CFG,$DB;

if(!empty($CFG->wordpressurl)){
	if (isloggedin()){
		require_logout();
		redirect($CFG->wordpressurl.'/user-login/?authlogin=login');
	}else{
		redirect($CFG->wordpressurl.'/user-login/?authlogin=login');
	}

}else{
	redirect($CFG->wwwroot."/login/index.php");
}

