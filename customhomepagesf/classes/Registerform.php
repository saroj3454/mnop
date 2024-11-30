<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/formslib.php');


class Registerform extends moodleform {

       /**
     * Form definition.
     */
    function definition() {
        global $CFG, $PAGE;

        $mform    = $this->_form;

        
        $mform->addElement('hidden', 'id','');
        $currentgrp = array();
        $currentgrp[0] =  $mform->createElement('text', 'fname','First Name',array('class'=>'flable na2','required'=>'true','placeholder'=>'First Name'));
        $currentgrp[2] =  $mform->createElement('text', 'lname','Last name',array('class'=>'flable na2 ','placeholder'=>'Last Name'));
         $mform->addGroup($currentgrp, 'currentgrp',
                    '', null, false);
           // $mform->addRule('currentgrp', null, 'required', null, 'client');
         $mform->addElement('text', 'email','Email', array('placeholder'=>'example@email.com'));
         $mform->addElement('password', 'password','Password',array('placeholder'=>'Password'));
        $mform->addElement('button','submit','Register',array('class' => 'form-submit'));
       
       
    }

     function validation($data, $files) {
     	  global $DB;
        $errors = array();
     	 $errors['fname']= "Please select ateast one event";
        return  $errors ;
    }

  


}



