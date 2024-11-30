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
 * @package     local_oauth
 * @category    admin
 * @copyright   2022 lds
 * @license      lds
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/oauth/lib.php');
require_once('form_oauth.php');
$id = optional_param('id',0, PARAM_INT);

$PAGE->set_url('/local/oauth/all_district.php');

require_login();
if(!is_siteadmin()){
   redirect(new moodle_url("/"), "You don't have permission to view this page", null, \core\output\notification::NOTIFY_WARNING);
}
 $mform = new districtdata();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('District Setting');
// $PAGE->navbar->add('');

$previewnode = $PAGE->navbar->add('District Setting', new moodle_url('/local/oauth/all_district.php'),navigation_node::TYPE_CONTAINER);

// $thingnode = $previewnode->add(
//   'District Setting', 
//   new moodle_url('/local/oauth/all_district.php')
// );
// $thingnode->make_active();
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
    
</div> <div class="col-md-3 flex_end"><a href="<?php echo $CFG->wwwroot.'/local/oauth/all_district.php'; ?>">

  <div class="btn mb-3 color_btn mb-2"> District Add</div></a></div>
   <div class="col-md-3"><a href="<?php echo $CFG->wwwroot.'/local/oauth/lunchdistrict.php'; ?>"><div class="btn mb-3 outline_btn mb-2">View District</div></a></div>
</div>

<?php
$topbardata = $DB->get_record('all_district',array('id'=>$id));
//echo $topbardata->slider_content['text'];
// $mform->set_data($topbardata);
$setdata = new stdClass();
$setdata->id = $id;
$setdata->districtid=$topbardata->districtid;
$setdata->oauty_token=$topbardata->oauty_token;



$mform->set_data($setdata);

if ($mform->is_cancelled()) {
  redirect($CFG->wwwroot);
} else if ($fromform = $mform->get_data()) {
 
    // $recorddata=$DB->get_record('all_district',array('districtid'=>$fromform->districtid));
    // if(!empty($recorddata)){     
    //      redirect($CFG->wwwroot.'/local/oauth/all_district.php', 'Already data Available', null, \core\output\notification::NOTIFY_WARNING);
    // }
      if(empty($fromform->id)){

        $topbar_slider=new stdClass();
        $topbar_slider->districtid=$fromform->districtid;
        $topbar_slider->oauty_token=$fromform->oauty_token;
        $topbar_slider->createdtime=time();
        $data=$DB->insert_record('all_district', $topbar_slider,true);
            $responsedata=getdistrictIDdata($fromform->districtid,$fromform->oauty_token);
            if(!empty($data) && !empty($responsedata['name'])){
            $topbar_slider->id=$data;
            $topbar_slider->name=$responsedata['name'];
            $topbar_slider->launch_date=$responsedata['launch_date'];
                if(!empty($responsedata['courseid'])){
                     $topbar_slider->courseid=$responsedata['courseid'];
                }

             $DB->update_record('all_district', $topbar_slider,true); 
            }

             redirect($CFG->wwwroot.'/local/oauth/all_district.php','Inserted Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);  

      }else{
        $topbar_slider=new stdClass();
        $topbar_slider->id=$fromform->id;
        $topbar_slider->districtid=$fromform->districtid;
        $topbar_slider->oauty_token=$fromform->oauty_token;
        $topbar_slider->updatedtime=time();
        $DB->update_record('all_district', $topbar_slider,true);

            $responsedata=getdistrictIDdata($fromform->districtid,$fromform->oauty_token);


            if(!empty($fromform->id)){
                     $topbar_slider->id=$fromform->id;
                    if(empty($responsedata['name'])){
                    $topbar_slider->name="";
                    $topbar_slider->launch_date="";
                    }else{
                    $topbar_slider->name=$responsedata['name'];
                    $topbar_slider->launch_date=$responsedata['launch_date'];   
                    }

                    if(!empty($responsedata['courseid'])){
                     $topbar_slider->courseid=$responsedata['courseid'];
                     }
                    
                    $DB->update_record('all_district', $topbar_slider,true); 
            }

              
               redirect($CFG->wwwroot.'/local/oauth/all_district.php','Updated Sucessfully', null, \core\output\notification::NOTIFY_SUCCESS);
            


 
      }

  




}


$mform->display();



echo $OUTPUT->footer();
