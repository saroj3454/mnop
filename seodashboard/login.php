<?php require_once(dirname(__FILE__) . '/../../config.php');
$PAGE->set_context(context_system::instance());
// if(!has_capability('local/seodashboard:seoadmindashboard',context_system::instance()) || !has_capability('local/seodashboard:seoadmindashboard_view',context_system::instance())){
// 	 redirect(new moodle_url("/my/"));
// }
global $DB, $OUTPUT,$CFG;
$pagetitle = "Login";

if (class_exists('local_seodashboard\Login')) {
	new \local_seodashboard\Login();	
}
