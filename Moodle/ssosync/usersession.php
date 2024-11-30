<?php require_once("../../config.php");
        global $CFG,$USER,$DB;
require_once("lib.php");
$tokendata = $_GET['token'];
if(!empty($_GET['token'])){
	$tokenuserdata=$DB->get_record('local_ssosync_authtoken',array('authtoken'=>$tokendata));
	if(!empty($tokenuserdata)){
		 $DB->delete_records('local_ssosync_authtoken',array('userid'=>$tokenuserdata->userid));
		 $redirecturl=$SESSION->wantsurl;
		 if(empty($redirecturl)){
		 	$redirecturl=$CFG->wwwroot;
		 }
		$userdata = $DB->get_record("user", array("id"=>$tokenuserdata->userid));
		// if($userdata->picture==)
		if($userdata->picture!=$USER->picture){
			$USER->picture=$userdata->picture;
		}
		

		
	    complete_user_login($userdata);
        \core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());
        redirect($redirecturl);

	}else{
		redirect($CFG->wwwroot);
	}
}else{
	redirect($CFG->wwwroot);
}
