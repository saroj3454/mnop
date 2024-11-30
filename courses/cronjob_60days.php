<?php
require_once(__DIR__ . '/../../config.php');
global $CFG,$USER,$DB;
error_reporting(E_ALL);
ini_set('display_errors', '1');

if(isset($_GET['mode']) && $_GET['mode']!=''){

	$get=$_GET['mode'];
	if($get=='deletedoc'){

		$get_file="Select *  from mdl_tutor_document WHERE time_stamp < NOW() - INTERVAL 60 DAY";
		$get_files = $DB->get_records_sql($get_file,array());

		if($get_files){
			foreach ($get_files as $key) {
			$get_id=$key->id;
			$createDeleteDocPath = "../theme/moove/".$key->tutor_document;
			$createDeleteVideoPath ="../theme/moove/".$key->tutor_video;

			if(unlink($createDeletePath) && unlink($createDeleteVideoPath))
			{
				$deleteSql = "Delete from {tutor_document} where id = ".$get_id;
				$rsDelete =  $DB->delete_records($deleteSql,array());
				
				if($rsDelete)
				{
					echo 'Files has been deleted';
					exit();
				}
			}
			else
			{
				echo 'Unable to delete files';
			}

		}

		}

		else{

			echo 'No Records Found';
		}
}


}



//DATABASE QUERY HERE.

?>