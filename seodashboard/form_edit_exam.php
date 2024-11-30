<?php 
require_once("$CFG->libdir/formslib.php");
 
class stateform extends moodleform {
    //Add elements to form
    public function definition() {
       global $CFG, $DB, $PAGE, $USER; 	
       	$attributes=array('class' => 'option-select', 'title'=>'');
       	$valid=array('class'=>'custom-valid');
        $mform = $this->_form; // Don't forget the underscore!    
        $mform->addElement('hidden', 'id');
       // $mform->addElement('html', '<div class="headingdata"><h2></h2><div>');
        // $mform->addElement('select','state_title',$select, $rdata);
        $mform->addElement('text', 'name','Exam Categories Name'); 
        $mform->addElement('text', 'slug', 'Slug'); 
        $mform->addElement('editor', 'description', 'Description');
        $mform->addElement('filepicker', 'image', get_string('file'), null,
                   array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', 'Submit'); 
       $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
 
    
}