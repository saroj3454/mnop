<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'Vrtuz - instructor'; 
$catalogue = $CFG->wwwroot.'/local/courses/aboutus.php';

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);
$PAGE->set_url($catalogue);
$PAGE->set_heading($SITE->fullname);

		if (class_exists('local_courses\Aboutus')) {
		new \local_courses\Aboutus();
	
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