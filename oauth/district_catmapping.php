<?php
  require_once('../../config.php');
  global $DB;
  $sql="SELECT d.* FROM {district} d LEFT JOIN {course_categories} cc ON cc.id=d.cat_id";
  $data=$DB->get_records_sql($sql,array());
  /*echo "<pre>";
  print_r($data);*/
  foreach($data as $row){
    if(!empty($row->cat_id)){
      $std1=new stdClass();
      $std1->id=$row->cat_id;

      $std1->name=$row->name;
      $std1->timemodified=time();
      $DB->update_record("course_categories",$std1);
      echo "updated";
    }else{
      $std=new stdClass();
      $std->name=$row->name;
      $std->timemodified=time();
      $course_id=$DB->insert_record("course_categories",$std);
      $row->cat_id=$course_id;
      $row->updateddate=time();
      $DB->update_record("district",$row);
      echo "inserted";
    }
  }

?>