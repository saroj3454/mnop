<?php 
require_once("../../config.php");
require_once('course_syncform.php');
global $DB, $OUTPUT, $PAGE, $USER,$COURSE;

if (empty($USER->id)) {
	redirect($CFG->wwwroot);
}
if (is_siteadmin()) {


} else {
	redirect($CFG->wwwroot);}
function moodlerecord($table,$id){
  global $DB, $OUTPUT, $PAGE, $USER,$CFG;
  if(!empty($id)){
  $data=$DB->get_record($table,array('id'=>$id)); 
  }else{
   $data=$DB->get_records($table,array(),'id desc');  
  }
  return $data; 
}
//echo $USER->id;
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('wordpressconfiguration', 'block_customhomepage'));
$PAGE->set_heading(get_string('wordpressconfiguration', 'block_customhomepage'));
$PAGE->navbar->add(get_string('wordpressconfiguration', 'block_customhomepage'), new moodle_url('/blocks/customhomepage/wordpresssyncconfiguration.php'));
$PAGE->set_url('/blocks/customhomepage/wordpresssyncconfiguration.php');
echo $OUTPUT->header();



$mform = new sync_configuration_form(); 
$topbardataa = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
$setdata = new stdClass();
$setdata->id = $topbardataa->id;

if(substr($topbardataa->wordpressurl,-1)=='/'){
   $dataurl=$topbardataa->wordpressurl;
}else{
  $dataurl=$topbardataa->wordpressurl."/";
}

$setdata->wordpressurl = $dataurl;



$mform->set_data($setdata);



if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot);
} else if ($fromform = $mform->get_data()) {
  $topbar_slider=new stdClass();
  $topbar_slider->id=$fromform->id;
  $topbar_slider->wordpressurl=$fromform->wordpressurl;
  $topbar_slider->timecreated=$fromform->timecreated;
 $DB->update_record('blocks_customhomepage_syncurl', $topbar_slider,true);
  
redirect($CFG->wwwroot.'/blocks/customhomepage/wordpresssyncconfiguration.php','Updated Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);




}



$mform->display();




echo $OUTPUT->footer();

?>