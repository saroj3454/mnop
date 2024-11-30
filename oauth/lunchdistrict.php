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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.0/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
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


<div class="container">
  <br>


  <hr>
<div class="row">
  <div class="col-md-12">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                 <th scope="col">S.N</th>
                <th scope="col">District Id</th>
              
                <th scope="col">OAUTH TOKEN</th>
                <th scope="col">District Name</th>
                <th scope="col">Launched Date</th>
                <th scope="col">Sync Action</th>
                <th scope="col">Edit|Delete</th>
                
             
             
            </tr>
        </thead>
 
  
 
        <tbody>
 <?php 

 date_default_timezone_set('Asia/Kolkata');
 $alldata=$DB->get_records('all_district');
 $i=1;
    foreach($alldata as $datavalue){   ?>
              <tr>
                 <td><?php echo $i++; ?> </td>
                  <td> <?php echo substr($datavalue->districtid,0,6); ?>...</td>    
                  <td> <?php echo substr($datavalue->oauty_token,0,6); ?>...</td>    
                <td><?php echo $datavalue->name; ?></td> 
               <td> <?php echo date('F j, Y', strtotime($datavalue->launch_date)); ?></td>    
              <td>Not Sync</td>
               <td> <a href='<?php echo $CFG->wwwroot.'/local/oauth/all_district.php?id='.$datavalue->id ?>'>Edit</a>| 
                <a href="<?php echo $CFG->wwwroot.'/local/oauth/district_delete.php?id='.$datavalue->id ?>" onclick="return confirm('Are you sure Delete?')" >Delete</a></td>    
            </tr>
          <?php } ?>




 </tbody>
</table>
  </div>
</div>
  
</div>
<script> 
  $(document).ready(function () {
  $('#example').DataTable();
  }); 
</script>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>


<?php

echo $OUTPUT->footer();
