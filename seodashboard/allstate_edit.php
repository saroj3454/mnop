<?php require_once(dirname(__FILE__) . '/../../config.php');
include('form_allstate_edit.php');
$context = context_system::instance();
require_login();
global $DB, $OUTPUT,$CFG;
$edit='';
if(!empty($_GET['id'])){
	$edit='Edit';
}else{
	$edit='Insert';
}
$pagetitle = "State ".$edit;
$url = new moodle_url("/local/seodashboard/state.php");
$PAGE->set_url($url);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/seodashboard/assest/js/city.js'));

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);
function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
     }
 // echo $r='<div class="allstate"> <a href="'.$CFG->wwwroot .'/local/seodashboard/allstate_edit.php" class="btn bg-primary"> Add State</a></div>';    
$mform = new stateform(); 
$data=$DB->get_record_sql("SELECT * FROM `mo_exam_state` where `id`='".$_GET['id']."'");
$setdata = new stdClass();
$setdata->id = $data->id;
$setdata->slug = $data->slug;
$setdata->state_description = unserialize($data->state_description);
$setdata->image =$data->image;
$setdata->state_title =$data->state_title;
$mform->set_data($setdata);

if ($mform->is_cancelled()) {
   redirect($CFG->wwwroot."/local/seodashboard/addstate.php");
 } else if ($fromform = $mform->get_data()) {
	  $insertdata=new stdClass();
	  $insertdata->state_title=$fromform->state_title;
	  $insertdata->state_description=serialize($fromform->state_description);
	  $insertdata->image=$fromform->image;
	  $insertdata->id=$fromform->id;
	  $insertdata->slug=urlslug($fromform->state_title);
 	 if(!empty($fromform->id)){
 	 	
		$DB->update_record('exam_state',$insertdata,true);
		redirect($CFG->wwwroot."/local/seodashboard/addstate.php", 'Successfully Updated...', null, \core\output\notification::NOTIFY_SUCCESS);
 	 }else{
 	 	
 	 	$insertdata->status='Active';
		$DB->insert_record('exam_state',$insertdata,true);
		redirect($CFG->wwwroot."/local/seodashboard/addstate.php", 'Successfully Inserted...', null, \core\output\notification::NOTIFY_SUCCESS);
 	 }

}
$mform->display();
echo $OUTPUT->footer();