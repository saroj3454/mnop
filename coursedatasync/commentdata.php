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
 * @copyright   2021 Suraj Maurya surajmaurya450@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
$courseid = optional_param('id',0, PARAM_INT);

$PAGE->set_url('/local/coursedatasync/commentdata.php');

require_login();
if(!is_siteadmin()){
   redirect(new moodle_url("/"), "You don't have permission to view this page", null, \core\output\notification::NOTIFY_WARNING);
}


$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Courses Comment data');
// $PAGE->navbar->add('');
$PAGE->requires->jquery();
$previewnode = $PAGE->navbar->add(
  'Courses Comment data', 
  new moodle_url('/local/coursedatasync/commentdata.php'), 
  navigation_node::TYPE_CONTAINER
);

$thingnode = $previewnode->add(
  'Course Comment', 
  new moodle_url('/local/coursedatasync/commentdata.php')
);
$thingnode->make_active();
echo $OUTPUT->header();

   global $DB, $OUTPUT, $PAGE, $USER;


  //print_r($_POST);die;
  // $courseid = $_POST['courseid'];
  // if ($courseid == '') {
  //     redirect($CFG->wwwroot.'/local/coursedatasync/coursecomment.php', 'Please select course', null, \core\output\notification::NOTIFY_ERROR);
  // }
  
 






$getrecords   = $DB->get_records_sql("SELECT * FROM {course} ORDER BY fullname");
        $coursedata = '<option value="">Choose Course...</option>';
        foreach ($getrecords as $data) {

          if($data->id==$courseid){
            $selected="selected='true'";
          }else{
              $selected="";
          }
            $coursedata = $coursedata . '<option value="'.$data->id.'" '.$selected.'>'.$data->fullname.'</option>';
          
          
        }
?>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.0/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<style type="text/css">
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

    .flex_center{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #courseid{
        width: 60%;
        margin-left: 10px;
        height: 45px;
    }
    .dataTables_empty{
        border-right: 1px solid black;
        border-left: 1px solid black;
    }

    #example_filter input{
        border: 1px solid black;
        padding: 5px 15px;
    }
    #example_filter input:focus{
        border: 1px solid black;
    }
    @media(max-width:  768px){
       #courseid{
        width: 100%;
        margin-left: 0px;
       } 
    }
</style>

<div class="container">



<div class="row"><div class="col-md-9"></div>
   <div class="col-md-3"><a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/coursecomment.php'; ?>"><div class="btn mb-3 outline_btn">Add Course Comment</div></a></div>
</div>


            <div class="row ">
               
                <div class="col-md-12 flex_center">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group row flex_center  ">
                            <label for="inputPassword" class=" col-form-label text-right">Select Course Name :</label>
                            <div class="">
                                <select name="courseid" id='courseid' class="form-control"><?php echo $coursedata ?></select>
                            </div>
                        </div>

                       
                  </div>

                    </form>
                </div>
            </div>
        </div>


<div class="container">
  <br>
<?php 
  $coursesdata=$DB->get_record('course',array('id'=>$courseid));
  ?>
<div class="row"><h4 style="padding: 0px;
    margin: 0;
    margin-bottom: -9px;"><div id='coursenamedata' class="c_dta"><b><?php echo $coursesdata->fullname; ?></b> Users Comments</div></h4></div> 

  <hr>
<div class="row">
  <div class="col-md-10">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                 <th scope="col">S.N</th>
                <th scope="col">Name</th>
              
                <th scope="col">Comment</th>
                <th scope="col">Created Time</th>
                <th scope="col">Action</th>
                
             
             
            </tr>
        </thead>
 
  
 
        <tbody>
 <?php 

 date_default_timezone_set('Asia/Kolkata');
 $alldata=$DB->get_records('blocks_custom_coursecomment',array('courseid'=>$courseid));
 $i=1;
    foreach($alldata as $datavalue){   ?>
              <tr>
                 <td><?php echo $i++; ?> </td>
                <td><?php echo $datavalue->name; ?></td> 
               <td> <?php echo $datavalue->comment; ?></td>    
               <td> <?php echo date('D, j M Y h:i A',$datavalue->createdtime);?></td>    
               <td> <a href='<?php echo $CFG->wwwroot.'/local/coursedatasync/comment_update.php?id='.$datavalue->id.'&courseid='.$datavalue->courseid; ?>'>Edit</a>| <a href="<?php echo $CFG->wwwroot.'/local/coursedatasync/comment_delete.php?id='.$datavalue->id.'&courseid='.$datavalue->courseid; ?>" onclick="return confirm('Are you sure Delete?')" >Delete</a></td>    
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
      $("#courseid").on("change", function() {
       var courseid=$(this).val();
       javascript:window.parent.location.href= '<?php echo $CFG->wwwroot.'/local/coursedatasync/commentdata.php?id='; ?>'+courseid;

    
    

      });
  });
</script>

<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"> </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<?php

echo $OUTPUT->footer();


?>

