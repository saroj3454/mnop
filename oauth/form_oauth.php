<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');


class districtdata extends moodleform {

       /**
     * Form definition.
     */
    function definition() {
        global $DB,$CFG, $PAGE;

        $mform    = $this->_form;

        $heading= (!empty('edit')) ? 'blockeditsettings' : 'blockaddsettings';

        
        $mform->addElement('hidden', 'id','');
  
if(!empty($_GET['id'])){

$name='District Update';
}else{
  $name= 'District ';
}

            $mform->addElement('header', 'courseheader', $name );

            $mform->setExpanded('courseheader');
           
          



//$mform->addRule('starrating', 'Enter only numeric Value', 'numeric', null, 'client');
// $mform->addRule('starrating', 'Invalid data', 'regex',"^\*{1,5}$");




$mform->addElement('text', 'districtid', 'District id',array('class'=>'cuserenrolled f_clas'));


$mform->addElement('text', 'oauty_token', 'OAUTH TOKEN',array('class'=>'cuserrated f_clas') );







 $buttonarray[] = &$mform->createElement('submit', 'submit', 'Submit'); 
       $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
        
               
    }

  


}



