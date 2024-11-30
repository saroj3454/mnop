<?php
namespace local_oauth\task;
 
/**
 * An example of a scheduled task.
 */
class Districts extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name(){
        return "Districts Add & Update";
    }
 
    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;
        echo "run....";
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
                $std=new \stdClass();
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
       
    }
   
}