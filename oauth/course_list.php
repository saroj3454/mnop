<?php
	require_once('../../config.php');
	global $DB,$OUTPUT,$PAGE;
  	echo $OUTPUT->header();
  	$ch = curl_init('https://api.clever.com/v3.0/courses/'); // Initialise cURL
  	$authorization = "Authorization: Bearer ".'TEST_TOKEN'; // Prepare the        
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); 
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	$result = curl_exec($ch); // Execute the cURL statement
  	curl_close($ch);
  	$result= json_decode($result);
  	if(!empty($result)){
  		foreach($result->data as $row){
  			$data=$row->data;
  			$sql="SELECT * FROM {clever_courses} WHERE course_id=?";
  			$course_data=$DB->get_record_sql($sql,array($data->id));
  			if(!empty($course_data)){
  				$course_data->name=$data->name;
  				$course_data->district_id=$data->district;
  				$course_data->updateddate=time();
  				$DB->update_record("clever_courses",$course_data);
  				// echo "updated";
  			}else{
  				$std=new stdClass();
  				$std->name=$data->name;
  				$std->course_id=$data->id;
  				$std->district_id=$data->district;
  				$std->createddate=time();
  				$DB->insert_record("clever_courses",$std);
  				// echo "inserted";
  			}
  		}
  	}else{
  		echo "No data found";
  	}
?>