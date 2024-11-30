<?php
include_once('../../../config.php');
include_once('../lib.php');
global $DB,$CFG,$USER;
$redirecturl=$CFG->wwwroot;
$userid=required_param('userid',PARAM_RAW);
$token=required_param('token',PARAM_RAW);
if(!empty($userid) && !empty($token)){
	$token_sql="SELECT * FROM {user_tokens} WHERE userid=? AND token=? AND status=? ORDER BY id";
	$token_data=$DB->get_record_sql($token_sql,array($userid,$token,1));
	if(!empty($token_data)){
		$userdata = $DB->get_record("user", array("id"=>$token_data->userid));
		$DB->delete_records('user_tokens',array('userid'=>$token_data->userid));
		complete_user_login($userdata);
		\core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());

		// $token_data->updateddate=time();
		// $token_data->status=0;
		// $DB->update_record('user_tokens',$token_data);
		$redirecturl=$SESSION->wantsurl;
		 if(empty($redirecturl)){
		 	$redirecturl=$CFG->wwwroot;
		 }
		 
		redirect($redirecturl);

	}else{
		redirect($redirecturl);
	}
}else{
	redirect($redirecturl);
}