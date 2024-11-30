<?php

// error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../config.php');
global $DB , $CFG, $OUTPUT, $SITE, $PAGE;
?>
<style> <?php include 'style.css'; ?></style>
<?php
$pagetitle = 'courses';
$catalogue = $CFG->wwwroot.'/local/courses/';

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title($pagetitle);
$PAGE->set_url($catalogue);
$PAGE->set_heading($SITE->fullname);
$categoryid = NULL;
$courseid = NULL;
$coursesort=NULL;
$searchbycatid=NULL;

if (isset($_GET['categoryid']) && $_GET['categoryid'] !=="") {
	$categoryid = $_GET['categoryid'];
	if (class_exists('local_courses\Category')) {
		// echo "test";
		// die();
		new \local_courses\Category($categoryid);
		
	}
}

if (isset($_GET['courseid']) && $_GET['courseid'] !=="" ) {
	$courseid = $_GET['courseid'];
	if (class_exists('local_courses\Course')) {
		// echo "test";
		// die();
		new \local_courses\Course($courseid);
		
	}
}

if (isset($_GET['tutorid']) && $_GET['tutorid'] !=="") {
	$tutorid = $_GET['tutorid'];
	if (class_exists('local_courses\Instructor')) {
		// echo "test";
		// die();
		new \local_courses\Instructor($tutorid);
		
	}
}

else{
	if (!(isset($_GET['courseid']) || isset($_GET['categoryid']))) {
		if (class_exists('local_courses\Course')) {
		// 		echo "test";
		// die();
			new \local_courses\Course(NULL);
		
		}		
	}
}
?>


<style type="text/css">
	.foo-socials a.ico-facebook:hover {
    color: rgb(59, 89, 152)!important;
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

<style type="text/css">
a:focus, a:hover {
    text-decoration: none!important;
}
    @media only screen and (max-width: 600px) {
        section.sec_tab {
    padding: 0px!important;
}
.heading.text-center h2 {
    width: 100%;
    margin: 10px;
    font-size: 25px;
}
li.nav-item {
    width: 100%;
    margin-top: 2%;
}
a.t_hree_btn {
    padding: 8px 5px 8px 5px!important;
    background-color: #2a2356;
    border-radius: 3px;
    border: 1px solid #2a2356!important;
    font-weight: bold;
    color: #fff!important;
    margin-right: 28px;
    margin-top: 2%;
    display: inline-block;
}
    
</style>