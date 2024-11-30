<?php 
require_once("../../config.php");

global $DB, $OUTPUT, $PAGE, $USER,$COURSE;
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

function commentrecords($courseid){
  global $DB, $OUTPUT, $PAGE, $USER,$CFG;
  $data=$DB->get_records_sql("SELECT cm.id,cm.userid,u.firstname,u.lastname,u.email,u.username,cm.content,cm.timecreated FROM {context} as cc INNER JOIN {comments} as cm on cc.id=cm.contextid INNER JOIN {user} as u on cm.userid=u.id WHERE cm.component='block_comments' and cc.instanceid='".$courseid."'"); 
  return $data; 
}


$cdata=moodlerecord('course',$id);
if (empty($USER->id)) {
	redirect($CFG->wwwroot);
}
if (is_siteadmin()) {


} else {
	redirect($CFG->wwwroot);}
//echo $USER->id;
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title($cdata->fullname.' All Comments');
$PAGE->set_heading($cdata->fullname.' All Comments');
$loginsite = $cdata->fullname.' All Comments';
//$PAGE->navbar->add($loginsite);
$PAGE->navbar->add(($cdata->fullname.' All Comments'), new moodle_url('/blocks/customhomepage/allcommentupdate.php?id='.$id));
$PAGE->set_url('/blocks/customhomepage/allcommentupdate.php?id='.$id);
echo $OUTPUT->header();

?>


<?php if(!empty($_GET['sucessfully'])){ ?>
<div class="isa_success" id="sucessfully">Sucessfully <?php echo $_GET['sucessfully']; ?></div>
<?php } ?>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.0/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<style type="">
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
   
<div class="row">
  <div class="col-md-12">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                 <th scope="col">S.N</th>
                <th scope="col">User Details</th>
                   <th scope="col"> Comment</th>
                   <th scope="col"> Created Time</th>
            
                <th scope="col">Action</th>
            </tr>
        </thead>
 
  
 
        <tbody>
            
            <?php 
         date_default_timezone_set('Asia/Kolkata');

$cnt=0;

 foreach (commentrecords($id) as $recorddata) { 
$cnt++;
?>
<tr>
  <td><?php echo $cnt++; ?></td>
  <td><a href="<?php echo $CFG->wwwroot . '/user/profile.php?id='.$recorddata->userid;?>"><?php echo $recorddata->firstname." ".$recorddata->lastname;  ?></td>
  <td><?php 
   echo $recorddata->content;
   ?>
   <td><?php 
   echo date('D, j M Y h:i A',$recorddata->timecreated);
   ?>
 
    <td> <div class="ic_o_n text-center">
                <a href="<?php echo $CFG->wwwroot ?>/blocks/customhomepage/comment_update.php?id=<?= $recorddata->id; ?>&courseid=<?php echo $cdata->id;?>"><span class="pencil"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a>
                  
                
                  </div>

                   </td> 
  </tr>
<?php } ?>
            
        </tbody>


    </table>
  </div>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"> </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
  
 
  
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