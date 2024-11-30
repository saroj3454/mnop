<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');


class sync_configuration_form extends moodleform {

       /**
     * Form definition.
     */
    function definition() {
        global $DB,$CFG, $PAGE;
        $mform    = $this->_form;
        $heading= (!empty('edit')) ? 'blockeditsettings' : 'blockaddsettings';
        $mform->addElement('hidden', 'id','');
        $mform->addElement('header', 'a_headr','Sync Configuration');
        $mform->addElement('text', 'wordpressurl', get_string('wordpress_url', 'block_customhomepage'), $attributes);
        $mform->addHelpButton('wordpressurl', 'wordpressurl','block_customhomepage');
    $mform->addElement('submit', 'submit','Update', array('class' => 'form-submit'));
            
    }

  


}



