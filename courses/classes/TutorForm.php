<?php 
@error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', '0');
$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 0;
require_once("$CFG->libdir/formslib.php");
 
class tutor_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG , $DB;
 
        $mform = $this->_form;  
       
             
        $filemanager_options = array();
        $filemanager_options['accepted_types'] = 'pdf';
        $filemanager_options['maxbytes'] = 2;
        $filemanager_options['maxfiles'] = 1;
        $filemanager_options['mainfile'] = true;

        $mform->addElement('filemanager', 'tutor_document','Tutor Document' , null, $filemanager_options);
        $mform->addRule('tutor_document',null,'required',null,'client');

        $filemanager_options1 = array();
        $filemanager_options1['accepted_types'] = 'mp4';
        $filemanager_options1['maxbytes'] = 2;
        $filemanager_options1['maxfiles'] = 1;
        $filemanager_options1['mainfile'] = true;

        $mform->addElement('filemanager', 'tutor_video', 'Tutor Video', null, $filemanager_options1);
        $mform->addRule('tutor_video',null,'required',null,'client');
       
       
        
       /* $mform->addElement('editor', 'description', get_string('description', 'local_blogs'), null, array('maxfiles' => EDITOR_UNLIMITED_FILES));
        $mform->addRule('description',null,'required',null,'client');
        $mform->setDefault('description', array('text'=>$blog->description));*/
       
        $this->add_action_buttons();

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

