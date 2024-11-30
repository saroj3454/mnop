<?php require_once(dirname(__FILE__) . '/../../config.php');
include('form_edit_exam.php');
$context = context_system::instance();
require_login();
global $DB, $OUTPUT,$CFG;
$edit='';
if(!empty($_GET['id'])){
	$edit='Edit';
}else{
	$edit='Insert';
}
$pagetitle = "Exam Categories ".$edit;
$url = new moodle_url("/local/seodashboard/addexamcategory.php");
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
$data=$DB->get_record_sql("SELECT * FROM `mo_exam_categories` where `id`='".$_GET['id']."'");
$setdata = new stdClass();
$setdata->id = $data->id;
$setdata->slug = $data->slug;
$setdata->description = unserialize($data->description);
$setdata->image =$data->image;
$setdata->name =$data->name;
$mform->set_data($setdata);

if ($mform->is_cancelled()) {
   redirect($CFG->wwwroot."/local/seodashboard/addexamcategory.php");
 } else if ($fromform = $mform->get_data()) {
	  $insertdata=new stdClass();
	  $insertdata->name=$fromform->name;
	  $insertdata->description=serialize($fromform->description);
	  $insertdata->image=$fromform->image;
	  $insertdata->id=$fromform->id;
	  $insertdata->slug=urlslug($fromform->name);
 	 if(!empty($fromform->id)){
 	 	$insertdata->slug=urlslug($fromform->slug);
		$DB->update_record('exam_categories',$insertdata,true);
		redirect($CFG->wwwroot."/local/seodashboard/addexamcategory.php", 'Successfully Updated...', null, \core\output\notification::NOTIFY_SUCCESS);
 	 }else{
 	 	
 	 	$insertdata->status='Active';
		$DB->insert_record('exam_categories',$insertdata,true);
		redirect($CFG->wwwroot."/local/seodashboard/addexamcategory.php", 'Successfully Inserted...', null, \core\output\notification::NOTIFY_SUCCESS);
 	 }

}
$mform->display();
echo $OUTPUT->footer();