<?php require_once("../../config.php");
        global $CFG,$USER,$DB;
require_once("lib.php");
$tokendata = $_GET['token'];
if(!empty($_GET['token'])){
	$tokenuserdata=$DB->get_record('admin_tokens',array('token'=>$tokendata));
	if(!empty($tokenuserdata)){
		 require_logout();
		 $DB->delete_records('admin_tokens',array('admin_userid'=>$tokenuserdata->userid));
		 $redirecturl=$CFG->wwwroot;
		$userdata = $DB->get_record("user", array("id"=>$tokenuserdata->admin_userid));
	    complete_user_login($userdata);
        \core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());
        redirect($redirecturl);

	}else{
		redirect($CFG->wwwroot);
	}
}else{
	redirect($CFG->wwwroot);
}
