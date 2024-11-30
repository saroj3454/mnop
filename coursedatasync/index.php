<?php
// This file is part of the Contact Form plugin for Moodle - http://moodle.org/
//
// Contact Form is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Contact Form is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Contact Form.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_bulkupload
 * @category    admin
 * @copyright   2021 lds
 * @license      lds
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
require_once($CFG->dirroot . '/local/coursedatasync/form_courserate.php');
$id = optional_param('id',0, PARAM_INT);

$PAGE->set_url('/local/coursedatasync/index.php');

require_login();
if(!is_siteadmin()){
   redirect(new moodle_url("/"), "You don't have permission to view this page", null, \core\output\notification::NOTIFY_WARNING);
}
$mform = new courserate();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('courserating', 'local_coursedatasync'));
// $PAGE->navbar->add('');

$previewnode = $PAGE->navbar->add(
  get_string('courserating', 'local_coursedatasync'), 
  new moodle_url('/local/coursedatasync/index.php'), 
  navigation_node::TYPE_CONTAINER
);

$thingnode = $previewnode->add(
  get_string('courserating', 'local_coursedatasync'), 
  new moodle_url('/local/coursedatasync/index.php')
);
$thingnode->make_active();
echo $OUTPUT->header();
?>
<style type="text/css">
    .f_clas input{
    width: 40% !important;
}
.d_clas input{
    width: 20% !important;
}
.dj1{
    width: 13%;
    float: left;
}
div#startratingdata {
    font-size: 16px;
    font-weight: 700;
    color: black;
}
#avragepercentdata{
      font-size: 16px;
    font-weight: 700;
    color: black;
}

.outline_btn{
    background: transparent;
    color: #2441e7;
    border: 2px solid #2441e7;
    padding: 14px 20px;
    font-size: 15px;
}

    .outline_btn:hover{
        background: #2441e7;
        color: #fff;
    }

    .color_btn{
        background: #2441e7;
    color: #fff;
    border: 2px solid #2441e7;
    padding: 14px 20px;
    font-size: 15px;
    }/*
    .color_btn:hover{
        background: transparent;
    color: #2441e7;
    }*/

    .btn:hover{
        color: #fff;
    }
    .flex_end{
    display: flex;
    justify-content: flex-end;
}

@media(max-width:  768px){
    .flex_end{
        justify-content: flex-start;
    }
}

</style>

<div class="row"><div class="col-md-6">
    
</div> <div class="col-md-3 flex_end"><a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/coursecomment.php'; ?>">

  <div class="btn mb-3 color_btn mb-2">Add Course Comment</div></a></div>
   <div class="col-md-3"><a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/commentdata.php'; ?>"><div class="btn mb-3 outline_btn mb-2">View Course Comment</div></a></div>
</div>

<?php
$topbardata = $DB->get_record('blocks_custom_courserate',array('id'=>$id));
//echo $topbardata->slider_content['text'];
// $mform->set_data($topbardata);
$setdata = new stdClass();
$setdata->id = $id;

$setdata->courseid=$topbardata->courseid;
$setdata->starrating=$topbardata->starrating;
$setdata->userenrolled=$topbardata->userenrolled;
$setdata->userrated=$topbardata->userrated;


    $startrate5=$topbardata->startrate5;
    $startrate4=$topbardata->startrate4;
    $startrate3=$topbardata->startrate3;
    $startrate2=$topbardata->startrate2;
    $startrate1=$topbardata->startrate1;  
    if(empty($topbardata->startrate1)){
    $startrate1="";
    }
    if(empty($topbardata->startrate2)){
    $startrate2="";
    }
    if(empty($topbardata->startrate3)){
    $startrate3="";
    }
    if(empty($topbardata->startrate4)){
    $startrate4="";
    }
    if(empty($topbardata->startrate5)){
    $startrate5="";
    }
    $setdata->startrate5=$startrate5;
    $setdata->startrate4=$startrate4;
    $setdata->startrate3=$startrate3;
    $setdata->startrate2=$startrate2;
    $setdata->startrate1=$startrate1;  

$mform->set_data($setdata);

if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot);
} else if ($fromform = $mform->get_data()) {
 
      if(empty($fromform->id)){
        $topbar_slider=new stdClass();
        $topbar_slider->courseid=$fromform->courseid;
      
        $topbar_slider->startrate5=$fromform->startrate5;
        $topbar_slider->startrate4=$fromform->startrate4;
        $topbar_slider->startrate3=$fromform->startrate3;
        $topbar_slider->startrate2=$fromform->startrate2;
        $topbar_slider->startrate1=$fromform->startrate1;
        $topbar_slider->userenrolled=$fromform->userenrolled;

        $topbar_slider->userrated=$fromform->userrated;
        $topbar_slider->createdtime=time();
        $data=$DB->insert_record('blocks_custom_courserate', $topbar_slider,true);

          if(!empty($data)){
				$urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/courseratingsync.php';
               $r=courserateapidata($url,$topbar_slider);

              if(!empty($r)){
         redirect($CFG->wwwroot.'/local/coursedatasync/index.php?id='.$data,'inserted Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
         			}
              }

      }else{
        $topbar_slider=new stdClass();
        $topbar_slider->id=$fromform->id;
        $topbar_slider->courseid=$fromform->courseid;
       
        $topbar_slider->startrate5=$fromform->startrate5;
        $topbar_slider->startrate4=$fromform->startrate4;
        $topbar_slider->startrate3=$fromform->startrate3;
        $topbar_slider->startrate2=$fromform->startrate2;
        $topbar_slider->startrate1=$fromform->startrate1;



        $topbar_slider->userenrolled=$fromform->userenrolled;
        $topbar_slider->userrated=$fromform->userrated;
        $topbar_slider->updatedtime=time();
        $DB->update_record('blocks_custom_courserate', $topbar_slider,true);

               $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
               $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/courseratingsync.php';
               $r=courserateapidata($url,$topbar_slider);

              if(!empty($r)){
              	redirect($CFG->wwwroot.'/local/coursedatasync/index.php?id='.$fromform->id,'Updated Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
              }


 
      }

  




}


$mform->display();

?>
<script type="text/javascript">
    $("<span>%</span>").insertAfter('input[name^=startrate]');
      $("#id_courseid").on("change", function() {
       var courseid=$(this).val();
       
        $('#ratingdatad').css('display','none');
          $('#id_starrating').val("");
           $('#id_userenrolled').val("");
           $('#id_userrated').val("");
           $("[name='id']").val("");

           $("#id_startrate5").val("");
           $("#id_startrate4").val("");
           $("#id_startrate3").val("");
           $("#id_startrate2").val("");
           $("#id_startrate1").val("");

           $("#startratingdata").html("");
           $("#avragepercentdata").html("");

       $.ajax({  
    type: 'POST',  
    url: '<?php echo $CFG->wwwroot.'/local/coursedatasync/ajaxdata.php'; ?>', 
    data: { courseid: courseid,action:'courserate' },
    success: function(response) {
     const obj= JSON.parse(response);
     $('#id_courseheader legend a').html(obj.coursename);

    
     var id=obj.id;
    
       if (typeof id !== 'undefined'){
        $('#ratingdatad').css('display','block');
          $('#id_starrating').val(obj.starrating);
           $('#id_userenrolled').val(obj.userenrolled);
           $('#id_userrated').val(obj.userrated);
           $("[name='id']").val(obj.id);

           $("#id_startrate5").val(obj.startrate5);
           $("#id_startrate4").val(obj.startrate4);
           $("#id_startrate3").val(obj.startrate3);
           $("#id_startrate2").val(obj.startrate2);
           $("#id_startrate1").val(obj.startrate1);

           $("#startratingdata").html(obj.rateing);
           $("#avragepercentdata").html(obj.percent);
       }
     // $("[name='id']").val(obj.percent);
     // $("[name='id']").val(obj.rateing);


         $('#ratederror').html('');
        $('#id_submit').removeAttr("disabled");
    

      // console.log(obj.courseid);
       // console.log();
    }
});
      });
    $("#id_userrated").on("keyup", function() {

 var userrated=$(this).val();
var userenrolled=$("#id_userenrolled").val();

if(userenrolled.length>0){
        if(userrated.length>0){
             if(parseInt(userrated)>parseInt(userenrolled)){

              $('#ratederror').html('Please Enter in value equal to less than the Users Enrolled');
             $('#id_submit').attr("disabled", 'disabled');
            }else{
               $('#ratederror').html('');
                $('#id_submit').removeAttr("disabled");

            }
        }
}
});

 $("#id_userenrolled").on("keyup", function() {
 var userenrolled=$(this).val();
var userrated=$("#id_userrated").val();
if(userenrolled.length>0){
    if(parseInt(userrated)>parseInt(userenrolled)){
      $('#ratederror').html('Please Enter in value equal to  less than the Users Enrolled');
     $('#id_submit').attr("disabled", 'disabled');
    }else{
       $('#ratederror').html('');
        $('#id_submit').removeAttr("disabled");

    }
}

});







</script>
<?php

echo $OUTPUT->footer();

