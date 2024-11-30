<?php require_once(dirname(__FILE__) . '/../../config.php');
$context = context_system::instance();
require_login();
if(!has_capability('local/seodashboard:seoadmindashboard',context_system::instance()) || !has_capability('local/seodashboard:seoadmindashboard_view',context_system::instance())){
	 redirect(new moodle_url("/my/"));
}
global $DB, $OUTPUT,$CFG;
$pagetitle = "All Seo Data";
$url = new moodle_url("/local/seodashboard/allseodata.php");
$PAGE->set_url($url);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/seodashboard/assest/js/style.js'));

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);
if (class_exists('local_seodashboard\Allseodata')) {
	new \local_seodashboard\Allseodata();	
}
echo $OUTPUT->footer();