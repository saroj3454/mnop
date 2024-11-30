<?php 
function wordpresslogout(\core\event\user_loggedout $event){
global $DB, $USER,$CFG;

    if($event->objecttable=="user" && $event->action=="loggedout"){ 
        require_once($CFG->dirroot.'/local/ssosync/lib.php');
        $urldata=syncauthapidata();
        $url=$urldata['wordpressurl'].'wp-content/plugins/ssoconfig/logout.php';
        redirect($url);
        
     	  }
}
