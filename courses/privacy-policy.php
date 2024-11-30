<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../../config.php");
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
//echo $USER->id;
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Privacy Policy');
$PAGE->set_heading('Privacy Policy');
$loginsite = 'Custom Home Page';
//$PAGE->navbar->add($loginsite);
//$PAGE->navbar->add(('Privacy Policy'), new moodle_url('/local/courses/privacy-policy.php'));
$PAGE->set_url('/local/courses/privacy-policy.php');

if (class_exists('local_courses\Privacy')) {
		new \local_courses\Privacy();
		
	}

?>



<style type="text/css" media="screen">
	@media (max-width: 600px) { 
  .intructor .tooltip_content {
    display: none!important;
}
}

.footer h5{
	color: #000;
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

<?php


?>
