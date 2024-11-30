<?php
  require_once('../../config.php');
  global $DB,$OUTPUT;
  echo $OUTPUT->header();

  $ch = curl_init('https://api.clever.com/v3.0/schools/'); 
  $authorization = "Authorization: Bearer ".'TEST_TOKEN';
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch); // Execute the cURL statement
  curl_close($ch); // Close the cURL connection
  $result= json_decode($result);
  if(!empty($result)){
    foreach($result->data as $row){
      $data=$row->data;
      $std=new stdClass();
      $std->school_id=$data->id;
      $std->district_id=$data->district;
      $std->name=$data->name;
      $std->createddate=time();
      $sql="SELECT * FROM {school} WHERE school_id=?";
      $school_data=$DB->get_record_sql($sql,array($data->id));
      if(!empty($school_data)){
        $school_data->name=$data->name;
        $school_data->district_id=$data->district;
        $school_data->updateddate=time();
        $DB->update_record("school",$school_data);
      }else{
        $DB->insert_record('school',$std);
      }
    }
  }
  ?>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="district_title">
          <h3>School List</h3>
        </div>
        <table class="table">
          <thead>
            <th>S.No</th>
            <th>Name</th>
            <th>School Id</th>
            <th>District Name</th>
            
          </thead>
          <tbody>
            <?php
              $sql2="SELECT s.*,d.name as dname FROM {school} s LEFT JOIN {district} d ON s.district_id=d.district_id";
              $sch_data=$DB->get_records_sql($sql2);
              if(!empty($sch_data)){
                $i=1;
                foreach($sch_data as $s_row){


            ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo $s_row->name;  ?></td>
              <td><?php echo $s_row->school_id;  ?></td>
              <td><?php echo $s_row->dname;  ?></td>
            </tr>
          <?php }} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php

echo $OUTPUT->footer();
?>

