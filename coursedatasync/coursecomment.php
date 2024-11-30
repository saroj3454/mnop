<?php
// This file is part of the Contact Form plugin for Moodle - http://moodle.org/
//
// Contact Form is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Contact Form is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Contact Form.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_bulkupload
 * @category    admin
 * @copyright   2021 Suraj Maurya surajmaurya450@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
$id = optional_param('id',0, PARAM_INT);

$PAGE->set_url('/local/coursedatasync/coursecomment.php');

require_login();
if(!is_siteadmin()){
   redirect(new moodle_url("/"), "You don't have permission to view this page", null, \core\output\notification::NOTIFY_WARNING);
}


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Course Comment');
// $PAGE->navbar->add('');
$PAGE->requires->jquery();
$previewnode = $PAGE->navbar->add(
  get_string('courserating', 'local_coursedatasync'), 
  new moodle_url('/local/coursedatasync/coursecomment.php'), 
  navigation_node::TYPE_CONTAINER
);

$thingnode = $previewnode->add(
  'Course Comment', 
  new moodle_url('/local/coursedatasync/coursecomment.php')
);
$thingnode->make_active();
echo $OUTPUT->header();

  global $CFG,$DB;

if(isset($_POST['uploadsubmit'])){
  //$datacapture->submit_bulk_upload($_FILES['bulkupload']);
  $filename = $_FILES['bulkupload']['name'];
  //print_r($_FILES['bulkupload']);
  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  //print_r($_POST);die;
  $courseid = $_POST['courseid'];
  if ($courseid == '') {
      redirect($CFG->wwwroot.'/local/coursedatasync/coursecomment.php', 'Please select course', null, \core\output\notification::NOTIFY_ERROR);
  }
  if ($ext != 'csv') {
      redirect($CFG->wwwroot.'/local/bulkupload/index.php', 'File Type Only CSV upload', null, \core\output\notification::NOTIFY_ERROR);
  }


  $tmpName = $_FILES['bulkupload']['tmp_name'];
  $csvAsArray = array_map('str_getcsv', file($tmpName));
  $check = 0;
  $msg = '';
  for ($i=1; $i <count($csvAsArray); $i++) { 
    $j = $i;
 $data=$DB->get_record_sql("SELECT * FROM {blocks_custom_coursecomment} where name = '".$csvAsArray[$i][0]."' and courseid='".$courseid."'");

   if(empty($data)) {
     $userdata=new stdClass();
     $userdata->courseid=$courseid;
     $userdata->name=$csvAsArray[$i][0];
     $userdata->comment=$csvAsArray[$i][1];
      date_default_timezone_set('Asia/Kolkata');
     $userdata->createdtime=strtotime($csvAsArray[$i][2].' '.$csvAsArray[$i][3]);
     $DB->insert_record('blocks_custom_coursecomment', $userdata);

    

   }else{
      $userdata=new stdClass();
     $userdata->id=$data->id;
    $userdata->courseid=$courseid;
     $userdata->name=$csvAsArray[$i][0];
     $userdata->comment=$csvAsArray[$i][1];
      date_default_timezone_set('Asia/Kolkata');
     $userdata->createdtime=strtotime($csvAsArray[$i][2].' '.$csvAsArray[$i][3]);
     $DB->update_record('blocks_custom_coursecomment', $userdata);
   }

}

              $allcommentdata=$DB->get_records('blocks_custom_coursecomment',array('courseid'=>$courseid));
              if(!empty($allcommentdata)){
              $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/course_comment.php';
               $r=coursecommentapidata($url,$allcommentdata);
                  if(!empty($r)){
                       redirect($CFG->wwwroot.'/local/coursedatasync/coursecomment.php', 'Inserted Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
                  }
              
                }

 

}





$getrecords   = $DB->get_records_sql("SELECT * FROM {course} ORDER BY fullname");
        $coursedata = '<option value="">Choose Course...</option>';
        foreach ($getrecords as $data) {
            $coursedata = $coursedata . '<option value="'.$data->id.'">'.$data->fullname.'</option>';
        }
?>

<div class="row"><div class="col-md-9"></div>
   <div class="col-md-3"><a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/commentdata.php'; ?>"><div class="btn mb-3 btn-primary mb-2">View Course Comment</div></a></div>
</div>


<div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/files/bulkuploadcomments.csv'; ?>"  class="btn mb-3 btn-primary mb-2" title="Download Template">Download <i class="fa fa-download"></i></a>
                </div>
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label text-right">Course Name :</label>
                            <div class="col-sm-8">
                                <select name="courseid" id='courseid' class="form-control"><?php echo $coursedata ?></select>
                            </div>
                        </div>

                        <div id="commentavl">
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label text-right">Bulk File :</label>
                            <div class="col-sm-8">
                                <input type="file" name="bulkupload" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8 text-center ">
                                <input type="submit" name="uploadsubmit" accept=".csv" class="btn btn-primary mb-2">
                            </div>
                        </div>

                  </div>

                    </form>
                </div>
            </div>
        </div>

<style type="text/css">
    .form-control{
        padding: 6px 20px;
    }
</style>

<?php

echo $OUTPUT->footer();


?>

