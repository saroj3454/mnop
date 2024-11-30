<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'Tutor';
$tutors = $CFG->wwwroot.'/local/courses/';

require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);
$PAGE->set_url($tutors);
$PAGE->set_heading($SITE->fullname);

if (file_exists(__DIR__.'/classes/TutorForm.php')) {
	require_once __DIR__.'/classes/TutorForm.php';	
}

echo $OUTPUT->header();
if (class_exists('tutor_form')) {
		
		$mform = new tutor_form();

		if ($mform->is_cancelled()) {

		} else if ($fromform = $mform->get_data()) {
			$tutor = new \local_courses\Blog();
			$tutor->tutor_form_add_instance($fromform);
			die();
		}
		
		
 		$mform->display();
}
?>

<?php 
echo $OUTPUT->footer();