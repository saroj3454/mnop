<?php
// This file is part of Assignement module
//
// this file lib file using in this local plugin
/**
 * Plugin function  are defined here.
 *
 * @package     assign_submission_viva
 * @category    Assignement locallib file
 * @copyright   2023 Andrew Robinson
 * @license     fullversion assign_submission_viva
 */

/** assign_submissio form component name of the plugin that extends assign_submission_plugin
 * https://moodledev.io/docs/apis/plugintypes/assign/submission
 */
defined('MOODLE_INTERNAL') || die();
class assign_submission_viva extends assign_submission_plugin {

    /**
     * Get the name of the viva text submission plugin
     * @return string
    */
    public function get_name() {
             return get_string('viva', 'assignsubmission_viva');
    }
      /**
     * Save the settings for viva submission plugin
     *
     * @param stdClass $data
     * @return bool
     */
     public function save_settings(stdClass $data) {
            return true;
    }
    /**
     * Add form elements for settings
     *
     * @param mixed $submission can be null
     * @param MoodleQuickForm $mform
     * @param stdClass $data
     * @return true if elements were added to the form
     */
    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
        global $CFG, $USER, $PAGE, $COURSE,$OUTPUT;
        $mform->addElement('html','<style>
.modrecordterm .form-check.d-flex {
    flex-direction: column;
}
#id_submissionheader {
  border-bottom: 0;
}
#id_submitbutton {
  display: none;
}
.form-group.fitem.btn-cancel {
  display: none;
}
.question{
font-weight: 700;
margin-top: 15px;
}
video {
  pointer-events: none;
}
.editsubmissionform {
  position: relative;
}
.spinner-border.text-primary ,.sptext {
  position: absolute;
  top: 45%;
  left: 50%;
  z-index: 99999;
}
.sptext {
  top: 53%;
}
.uploadrecord::after {
  content: "";
  background-color: rgba(0,0,0,.02);
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 99;
}
.sptext {
top: 49%;
left: 45%;
display: flex;
justify-content: center;
align-content: center;
z-index: 9999999999;
color: white;
font-weight: 700;
}

</style>
<div id="myuploaderloader" style="display:none;"><div class="uploadrecord"><div class="spinner-border text-primary" role="status"><span class="sr-only"></span></div><div class="sptext">Video Uploading...</div></div></div>
<span modviva class="modviva"><div id="fitem_id_chechbb" class="form-group row  fitem   ">
    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0"><label id="id_gggggggg_label" class="d-inline word-break" for="id_viva">
                '.get_string('viva_name', 'assignsubmission_viva').'  
                </label>
        <div class="form-label-addon d-flex align-items-center align-self-start">    
        </div>
    </div>
    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text">
      <video autoplay="" id="stream-elem" controls width="580" height="400" muted="true" controlBar={false}>
        <source src="" type="" >
    </video>
        <div class="form-control-feedback invalid-feedback" id="id_error_gggggggg"> 
        </div>
    </div>
</div>');
        $mform->addElement('checkbox', 'recordterm', get_string('viva_agree', 'assignsubmission_viva'),'',array('class'=>'modrecordterm'));
$mform->addElement('html','<div id="fitem_id_mbtn" class="form-group row  fitem   ">
    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0"><label id="id_gggggggg_label" class="d-inline word-break" >
              
                </label>
        <div class="form-label-addon d-flex align-items-center align-self-start">    
        </div>
    </div>
    <div class="col-md-9 form-inline align-items-start felement flex-column" data-fieldtype="text">
     <div  class="btn btn-secondary" startrecording>Start Recording</span>
<div vivaquestion></div>
        <div class="form-control-feedback invalid-feedback" id="id_error_gggggggg"> 
        </div>
    </div>

<div  class="btn btn-primary" stoprecording style="display:none">Stop recording and submit</div>

<div class="question" allquestion></div>

<input type="hidden" name="vblobn" value="" id="vblobn">
<input type="hidden" name="vuserid" value="'.$submission->userid.'" id="userid">
<input type="hidden" name="vassigneid" value="'.$submission->id.'" id="vassigneid">
<input type="hidden" name="vassignmentid" value="'.$submission->assignment.'" id="vassignmentid">
</div></span>
');
$mform->disable_form_change_checker();
        $PAGE->requires->js(new moodle_url('/mod/assign/submission/viva/js/modvivascript.js'));
        $PAGE->requires->jquery();
        $PAGE->requires->jquery_plugin('ui');
         return true;
      }

       /**
     * Save data to the database
     *
     * @param stdClass $submission
     * @param stdClass $data
     * @return true
    * https://moodledev.io/docs/apis/plugintypes/assign/submission
     */
    public function save(stdClass $submission, stdClass $data) {
        global $USER,$DB, $OUTPUT,$CFG;
        return true;
    }
    /**
     * Display the list of files  in the submission status table
     *
     * @param stdClass $submission
     * @return video link
     * https://moodledev.io/docs/apis/plugintypes/assign/submission
     */

    public function view_summary(stdClass $submission, & $showviewlink) {
    	 global $DB;
    	$fileinfodata=$DB->get_record_sql("SELECT * FROM {files} WHERE itemid='".$submission->assignment."' and component='viva_assignsubmission_video' and filename!='.' and userid='".$submission->userid."'");
    	if(!empty($fileinfodata)){
    		return html_writer::link(new moodle_url('/local/viva/file.php/'.$fileinfodata->pathnamehash.'/0/'.$fileinfodata->filename),$fileinfodata->filename, array('class' => 'linkviva','target'=>'_blank')); 
    	}	
         
    }
     
}