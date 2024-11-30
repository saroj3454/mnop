<?php 
function userroleassigne(\core\event\user_created $event){
	global $DB, $USER,$CFG;
    require_once($CFG->dirroot.'/local/ecommerce/lib.php');
	if($event->target=="user" && $event->action=="created"){
		if(!empty($event->relateduserid)){
			
				 $rdata=userAssignerole($event->relateduserid);	       
		}	
	}
}