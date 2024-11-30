<?php
$observers = array(
	array(
        'eventname' => '\core\event\course_category_created',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'createcategories',
        'internal' => true),
    array(
        'eventname' => '\core\event\course_category_updated',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'updatecategories',
        'internal' => true),
     array(
        'eventname' => '\core\event\course_category_deleted',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'deletecategories',
        'internal' => true),
      array(
        'eventname' => '\core\event\course_created',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'createcourse',
        'internal' => true),
      array(
        'eventname' => '\core\event\course_updated',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'updatecourse',
        'internal' => true),
      array(
        'eventname' => '\core\event\course_deleted',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'deletecourse',
        'internal' => true),
      array(
        'eventname' => '\core\event\course_section_created',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_section_created',
        'internal' => true),
       array(
        'eventname' => '\core\event\course_section_updated',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_section_updated',
        'internal' => true),
        array(
        'eventname' => '\core\event\course_section_deleted',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_section_deleted',
        'internal' => true),
        array(
        'eventname' => '\core\event\course_module_created',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_module_created',
        'internal' => true),
        array(
        'eventname' => '\core\event\course_module_updated',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_module_updated',
        'internal' => true),
        array(
        'eventname' => '\core\event\course_module_deleted',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'course_module_deleted',
        'internal' => true),
        array(
        'eventname' => '\core\event\enrol_instance_created',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'enrol_instance_created',
        'internal' => true),
        array(
        'eventname' => '\core\event\enrol_instance_updated',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'enrol_instance_updated',
        'internal' => true),
        array(
        'eventname' => '\core\event\enrol_instance_deleted',
        'includefile' => '/local/coursedatasync/datasync.php',
        'callback' => 'enrol_instance_deleted',
        'internal' => true),
       
        

      

       
      




    
	

);

?>

