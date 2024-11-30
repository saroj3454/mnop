<?php
require_once("../../config.php");
  global $PAGE, $CFG,$DB;
if (isloggedin()){
require_logout();
redirect($CFG->wordpressurl.'/user-login/?authlogin=login');
}else{
redirect($CFG->wordpressurl.'/user-login/?authlogin=login');
}