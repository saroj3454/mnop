<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');


class ccomment_form extends moodleform {

       /**
     * Form definition.
     */
    function definition() {
        global $DB,$CFG, $PAGE;

        $mform    = $this->_form;

        $heading= (!empty('edit')) ? 'blockeditsettings' : 'blockaddsettings';

        
        $mform->addElement('hidden', 'id','');
        $mform->addElement('hidden', 'courseid','');
        $mform->addElement('hidden', 'wooid','');
       $mform->addElement('text', 'name',"Name");
 $mform->addElement('date_time_selector', 'createdtime','Created Time');
 $mform->addElement('textarea', 'comment',"Comment", 'wrap="virtual" rows="20" cols="50"');
       
        
                $mform->addElement('submit', 'submit','Update', array('class' => 'form-submit'));
            
    }

  


}



