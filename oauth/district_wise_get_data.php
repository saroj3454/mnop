<?php
  require_once('../../config.php');


  global $DB,$OUTPUT,$PAGE;
  echo $OUTPUT->header();
  $ch = curl_init('https://api.clever.com/v3.0/districts/'); // Initialise cURL
  $authorization = "Authorization: Bearer ".'TEST_TOKEN'; // Prepare the        
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch); // Execute the cURL statement
  curl_close($ch);
  $result= json_decode($result);
  if(!empty($result)){
    foreach($result->data as $row){
      $data=$row->data;
    
      $std=new stdClass();
      $std->name=$data->name;
      $std->district_id=$data->district_contact->district;
      $std->createddate=time();
      $sql="SELECT * FROM {district} WHERE district_id=?";
      $district_data=$DB->get_record_sql($sql,array($data->district_contact->district));
      if(!empty($district_data)){
        $district_data->name=$data->name;
        $district_data->updateddate=time();
        $DB->update_record("district",$district_data);
      }else{
        $DB->insert_record("district",$std);
      }
      
    }
  }
?>
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="title">
        <h3>District List</h3>
      </div>
      <table class="table">
        <thead>
          <th>S.No</th>
          <th>Name</th>
          <th>District Id</th>
          <th>Created Date</th>
          <th>Updated Date</th>
        </thead>
        <tbody>
          <?php
            $sql2="SELECT * FROM {district}";
            $dis_data=$DB->get_records_sql($sql2);
            if(!empty($dis_data)){
              $i=1;
              foreach($dis_data as $d_row){

          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $d_row->name; ?></td>
            <td><?php echo $d_row->district_id; ?></td>
            <td><?php echo date('Y-m-d',$d_row->createddate); ?></td>
            <td><?php echo date('Y-m-d',$d_row->updateddate); ?></td>
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