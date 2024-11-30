<?php 
require_once("../../config.php");
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
include('form_commentupdate.php');
global $DB, $OUTPUT, $PAGE, $USER,$COURSE;

$courseid= optional_param('courseid', '0', PARAM_INT);
$id = optional_param('id', '0', PARAM_INT);

function moodlerecord($table,$id){
  global $DB, $OUTPUT, $PAGE, $USER,$CFG;
  if(!empty($id)){
  $data=$DB->get_record($table,array('id'=>$id)); 
  }else{
   $data=$DB->get_records($table,array(),'id desc');  
  }
  return $data; 
}

$topbardata =moodlerecord('blocks_custom_coursecomment',$id);
$coursedt=moodlerecord('course',$courseid);

if (empty($USER->id)) {
	redirect($CFG->wwwroot);
}
if (is_siteadmin()) {


} else {
	redirect($CFG->wwwroot);}
//echo $USER->id;
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title($coursedt->fullname." User Comment Update");
$PAGE->set_heading($coursedt->fullname." User Comment Update");
$loginsite = 'Test Section';
//$PAGE->navbar->add($loginsite);



echo $OUTPUT->header();

?>




<style type="">
section.inner_page_breadcrumb.ccn_breadcrumb_default.ccn-no-clip.ccn-caps-capitalize.ccn-breadcrumb-title-v.ccn-breadcrumb-trail-v {
    display: none;
}
  .paginate_button {
  border-radius: 0 !important;
}

.edit1 {
    display: inline-block;
}
.edit1 {
    font-size: 24px;
    font-weight: 600;
}
.edit{
margin-top: 7px;
}
.edit a {
     color: #5f5f5f;
    margin-right: 6px;
    margin-left: 8px;
    margin-top: 15px;
    font-size: 16px;
}
.edit a :hover{
  text-decoration: none;
}
.he_ader {
      margin-bottom: 46px;
    margin-top: 27px;
}
.block ul.block_tree a, .block_book_toc li a, .block_site_main_menu li a, .breadcrumb a, .instancename, .navbottom .bookexit, .navbottom .booknext, .navbottom .bookprev {
    color: #555;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    height: 40px;
 /*   border-bottom: 2px solid #503b3b;*/
    border: 1px solid #503b3b;;
    /*border-top: none;
    border-left: none;
    border-right: none;*/
}
.eyes i {
    color: #b1afaf;margin-right: 10px;
}

.del i {
    color: red;margin-right: 10px;
}


table.dataTable tbody th, table.dataTable tbody td {
    padding: 17px 14px;
}
.he_ader {
    box-shadow: 0px 0px 8px 0px #b1b1b1 ;
       padding: 13px 23px;
    border-radius: 16px;
}
.dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate{
  padding-top: 16px;
}


/*@media only screen and(max-width: 568px){*/
@media screen and (max-width: 568px) {
  .edit1 {
    font-size: 12px;
    font-weight: 600;
}
.edit a{
      color: #5f5f5f;
    margin-right: 5px;
    margin-left: 5px;
    margin-top: 15px;
    font-size: 14px!important;

}
.he_ader {
   padding: 13px 16px;
  }





}

.isa_success:before {
   font-family: "Font Awesome 5 Free";
   content: "\f00c";
   display: inline-block;
   padding-right: 3px;
   vertical-align: middle;
   font-weight: 900;
   padding-right: 9px;
}

.isa_success {
   position: fixed;
    right: 17px;
    z-index: 99999;
     color: white;
    background-color: #6faf04;
    bottom: 37px;
    padding-right: 11px;
    padding-left: 5px;
    line-height: 29px;
    border-radius: 4px;
}

tr.myststus{
  color: #b1acac;
}

.ic_o_n.text-center a {
    padding-right: 15px;
}

</style>

<link rel="stylesheet" media="mediatype and|not|only (expressions)" href="print.css">
<div class="container">
<br>

    <div class="row">
        <div class="col-md-12">
            <div class="he_ader ">
               <div class="edit1 ">
             <b><?php echo $coursedt->fullname ?></b> -<small>User Comment Update</small>
               </div>

       </div>
    </div>
</div>


<div class="row">
  <div class="col-md-10">
    <?php $mform = new ccomment_form(); 
$topbardataa = $DB->get_record('blocks_custom_coursecomment',array('id'=>$id));
$setdata = new stdClass();
$setdata->id = $topbardataa->id;
$setdata->courseid = $courseid;
$setdata->comment = $topbardataa->comment;
$setdata->createdtime = $topbardataa->createdtime;
$setdata->name = $topbardataa->name;
$setdata->wooid = $topbardataa->wooid;


$mform->set_data($setdata);



if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot);
} else if ($fromform = $mform->get_data()) {
  $topbar_slider=new stdClass();
  $topbar_slider->id=$fromform->id;
  $topbar_slider->createdtime=$fromform->createdtime;
  $topbar_slider->name=$fromform->name;
  $topbar_slider->comment=$fromform->comment;
  $topbar_slider->wooid=$fromform->wooid;
  $DB->update_record('blocks_custom_coursecomment', $topbar_slider,true);
  
               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/single_course_comment_update.php';
               $r=singlecoursecommentapidata($url,$topbar_slider);


if(!empty($r)){
  
redirect($CFG->wwwroot.'/local/coursedatasync/commentdata.php?id='.$fromform->courseid,'Updated Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
}






}



$mform->display();


?>


  </div>
</div>
</div>




<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"> </script>


<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
  $('#id_icon').on( 'click',function(){
  $('#GSCCModal').modal('show');
  });
 
  
  var table = $('#example').DataTable({ 
        select: false,
        "columnDefs": [{
            className: "Name", 
            "targets":[0],
            "visible": false,
            "searchable":false
        }]
    });//End of create main table

  
//   $('#example tbody').on( 'click', 'tr', function () {
   
//     alert(table.row( this ).data()[0]);

// } );
});

</script>


<script>
 

  setTimeout(function() {
    $('#sucessfully').fadeOut('fast');
  }, 6000); // <-- time in milliseconds


</script>


<?php
echo $OUTPUT->footer();

?>
