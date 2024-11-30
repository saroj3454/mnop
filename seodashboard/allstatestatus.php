<?php
require_once('../../config.php');

$data=$DB->get_record_sql("SELECT * FROM `mo_exam_state` where id='".$_GET['id']."'");
if(!empty($_GET['delete'])){
	$data->deleted='0';
	$mes='Successfully Deleted ...';	
}else{
	if($data->status=='Active'){
	$data->status='not_active';
	}else{
	$data->status='Active';
	}
	$mes='Successfully Updated ...';	
}

$DB->update_record('exam_state',$data);
redirect($CFG->wwwroot."/local/seodashboard/addstate.php",$mes, null, \core\output\notification::NOTIFY_SUCCESS);