<?php require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/local/seodashboard/classes/Stateinsert.php');

$context = context_system::instance();
require_login();
global $DB, $OUTPUT,$CFG;
$id = optional_param('id',0, PARAM_INT);
$action = optional_param('action','null',PARAM_RAW);
$pagetitle = "All State and City ";
if(empty($id)){
    $url='?action='.$action;
}else{
	$url='?action='.$action.'&id='.$id;
}

$url = new moodle_url("/local/seodashboard/state.php".$url);
$PAGE->set_url($url);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/seodashboard/assest/js/city.js'));
echo $OUTPUT->header();
// echo $OUTPUT->heading($pagetitle);
// if (class_exists('local_seodashboard\Stateinsert')) {

// 	new \local_seodashboard\Stateinsert();
// }
new Stateinsert();

echo $OUTPUT->footer();