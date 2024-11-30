<?php require_once("../../config.php");
global $DB, $OUTPUT, $PAGE, $USER;
 
if(empty($USER->id)){
	redirect($CFG->wwwroot);
}
if(is_siteadmin() || user_has_role_assignment($USER->id,9))
{
    
    
}
else
{redirect($CFG->wwwroot);}
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
if($_GET['id'])
{    
$eeeid=$_GET['id'];

 $commenteddata=$DB->get_record('blocks_custom_coursecomment',array('id'=>$eeeid));

  $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/delete_single_comment.php';
                $r=deletesinglecoursecommentapidata($url,$commenteddata);
                	if(!empty($r)){
               $DB->delete_records('blocks_custom_coursecomment',array('id'=>$eeeid));
 
redirect($CFG->wwwroot.'/local/coursedatasync/commentdata.php?id='.$_GET['courseid'],'Delete Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
		}
}
