<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'Registration';
$catalogue = $CFG->wwwroot.'/local/courses/';

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);
$PAGE->set_url($catalogue);
$PAGE->set_heading($SITE->fullname);

if (class_exists('local_courses\Commingsoon')) {
	new \local_courses\Commingsoon();
}


