<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
 $sortby = optional_param('sortby', 0, PARAM_RAW);
   /* if(empty($sortby)){
    	redirect($CFG->wwwroot."/local/courses/institutions.php?sortby=popular");
    }*/
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'Become-a-tutor';
$catalogue = $CFG->wwwroot.'/local/courses/';

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);
$PAGE->set_url($catalogue);
$PAGE->set_heading($SITE->fullname);



if (isset($_GET['instituteid']) && $_GET['instituteid'] !=="" ) { 
	$instituteid = $_GET['instituteid'];
	if (class_exists('local_courses\Institute')) {
		new \local_courses\Institute($instituteid);
	}
}
else
if (class_exists('local_courses\Allinstitutions')) {
	new \local_courses\Allinstitutions();
}

?>


<!-- 
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>fdjgjhg</h1>
			</div>
		</div>
	</div>

</section>
 -->

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



