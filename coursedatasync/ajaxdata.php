<?php require_once('../../config.php');
if($_REQUEST['action']=='courserate'){
	require_once($CFG->dirroot.'/local/coursedatasync/lib.php');
global $DB, $OUTPUT, $PAGE, $USER;
	$ratedata = $DB->get_record("blocks_custom_courserate", array("courseid"=>$_REQUEST['courseid']));
    $coursesdata=$DB->get_record('course',array('id'=>$_REQUEST['courseid']));
	$data=array();
	
	if(!empty($ratedata)){
	$data['coursename']="<b>".$coursesdata->fullname ."</b> Course Rating and Enrolled Users Data Update";	
	$data['courseid']=$coursesdata->id;	
	$data['id']=$ratedata->id;	
	$data['starrating']=$ratedata->starrating;
	$data['userenrolled']=$ratedata->userenrolled;
	$data['userrated']=$ratedata->userrated;

	     $startrate1=$ratedata->startrate1;
			$startrate2=$ratedata->startrate2;
			$startrate3=$ratedata->startrate3;
			$startrate4=$ratedata->startrate4;
			$startrate5=$ratedata->startrate5;

			if(empty($ratedata->startrate1) && $ratedata->startrate1=='0'){
			$startrate1="";
			}
			if(empty($ratedata->startrate2)){
			$startrate2="";
			}
			if(empty($ratedata->startrate3)){
			$startrate3="";
			}
			if(empty($ratedata->startrate4)){
			$startrate4="";
			}
			if(empty($ratedata->startrate5)){
			$startrate5="";
			}
			$data['startrate1']=$startrate1;
			$data['startrate2']=$startrate2;
			$data['startrate3']=$startrate3;
			$data['startrate4']=$startrate4;
			$data['startrate5']=$startrate5;
			
			$r=startratingdisplay($ratedata->userrated,$startrate5,$startrate4,$startrate3,$startrate2,$startrate1);
			$data['percent']=$r['percent'];
			$data['rateing']=$r['rateing'];

	}else{
	$data['coursename']="<b>".$coursesdata->fullname ."</b> Course Rating and Enrolled Users Data Insert";	
	}

 echo json_encode($data);
}
if($_REQUEST['action']=='coursecommentdata'){
global $DB, $OUTPUT, $PAGE, $USER;
	$coursesdata=$DB->get_record('course',array('id'=>$_REQUEST['courseid']));
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
                <th scope="col">Course</th>
              
                <th scope="col">Total Users</th>
                
             
             
            </tr>
        </thead>
 
  
 
        <tbody>
<tr>
                 <td> S.N</td>
                <td> Course</td>
              
               <td> Total Users</td>
                
             
             
            </tr>

 </tbody>
</table>
  </div>
</div>

<?php } 