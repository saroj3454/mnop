<?php
	include('../../config.php');
	global $DB;
	$sql="SELECT s.*,d.cat_id as d_cat_id FROM {school} s INNER JOIN {district} d ON s.district_id=d.district_id LEFT JOIN {course_categories} c ON c.id=s.cat_id WHERE d.cat_id IS NOT NULL";
	$data=$DB->get_records_sql($sql,array());
	foreach($data as $row){
		if(!empty($row->cat_id)){
			$std1=new stdClass();
      		$std1->id=$row->cat_id;
      		$std1->name=$row->name;
      		$std1->timemodified=time();
      		$DB->update_record("course_categories",$std1);
      		echo "updated<br>";
		}else{
			$cat_id=$row->d_cat_id;
			unset($row->d_cat_id);
			$std=new stdClass();
			$std->name=$row->name;
			$std->timemodified=time();
			$std->parent=$cat_id;
			$c_id=$DB->insert_record("course_categories",$std);
			$row->cat_id=$c_id;
			$DB->update_record("school",$row);
			echo "iserted and updated <br> ";;
		}
	}
?>