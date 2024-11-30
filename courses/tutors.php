<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
    $sortby = optional_param('sortby', 0, PARAM_RAW);
    if(empty($sortby)){
    	redirect($CFG->wwwroot."/local/courses/tutors.php?sortby=popular");
    }
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'Vrtuz - instructor'; 
$catalogue = $CFG->wwwroot.'/local/courses/instructor.php';

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('base');
$PAGE->set_title($pagetitle);
$PAGE->set_url($catalogue);
$PAGE->set_heading($SITE->fullname);


if (isset($_GET['tutorid']) && $_GET['tutorid'] !=="") {
	$tutorid = $_GET['tutorid'];
	if (class_exists('local_courses\Instructor')) {
		new \local_courses\Instructor($tutorid);
	}
}
	else {
		if (class_exists('local_courses\Allinstructor')) {
		new \local_courses\AllInstructor();
	
	}
}

?>

<style type="text/css" media="screen">
	@media (max-width: 600px) { 
  .intructor .tooltip_content {
    display: none!important;
}
}

</style>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60d32da77f4b000ac0392300/1f8sekm9q';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->