<?php
require_once("../../config.php");
  global $PAGE, $CFG,$DB;
if (isloggedin()){
require_logout();
redirect($CFG->wwwroot."/login/index.php");
}else{
redirect($CFG->wwwroot."/login/index.php");
}