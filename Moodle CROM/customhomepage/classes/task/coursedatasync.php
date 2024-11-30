<?php  
namespace block_customhomepage\task;
/** 
 * An example of a scheduled task.
 */
class coursedatasync extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return "Course Data Sync in wordpress";
    }
 
    /**
     * Execute the task.
     */
    public function execute() { 
      global $DB, $CFG, $USER;
      $urldata = $DB->get_record('blocks_customhomepage_syncurl',array('id'=>'1'));
    $url=$urldata->wordpressurl.'wp-content/plugins/learningcourse/ajaxdata.php';
    $r=$this->apidata($url,$this->allcoursedata());





   
   



    }


function apidata($url,$data){
    global $DB, $CFG, $USER;
require_once($CFG->dirroot.'/blocks/customhomepage/lib.php');
        $ch = curl_init($url);

        // Setup request to send json via POST
       
        $payload = json_encode(array("admin" => $data));

        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);
        // echo "<pre>";
        // print_r($result);
        // die();

        $result=json_decode($result);
         
        foreach($result as $res){
           
            $woo_course_categoriesid=$res->woo_course_categoriesid;
            $moodle_course_categoriesid=$res->moodle_course_categoriesid;
            $woo_categories_tableid=$res->woo_categories_tableid;
            categoriesdataretundata($woo_course_categoriesid,$moodle_course_categoriesid,$woo_categories_tableid);
                   foreach($res->wordpresscourse as $coursedata){
                             coursedataretundata($coursedata);
                           foreach($coursedata->wordpresssection as $coursesection){
                                coursesectionreturndata($coursesection);
                                foreach($coursesection->moduledata as $coursemodule){
                                     coursemodulereturndata($coursemodule);
                                }

                           }
                   }
                  


          
           //print_r($res);
        }
     




        // Close cURL resource
        curl_close($ch);
}

function allcoursedata(){
    global $DB, $CFG, $USER;
    require_once($CFG->dirroot.'/blocks/customhomepage/lib.php');
    $datetime=strtotime("-3 day");

    //$sql="select * from cocoon_course where timecreated>=".$datetime." or timemodified>=".$datetime;
   
   
    $alldata=array();
 
    $categoriesdatas=$DB->get_records('course_categories');
    foreach($categoriesdatas as $categoriesdata){
                $sql="select * from {course} where `category`='".$categoriesdata->id."'";
                $coursesdata =  $DB->get_records_sql($sql);
                    $coursealldata=array();
                    foreach ($coursesdata as $value) {
                        $data=array();
                        $data['id']=$value->id;
                        $data['courseid']=$value->id;
                        $data['fullname']=$value->fullname;
                        $data['shortname']=$value->shortname;
                        $data['summary']=coursedescription($value->id,$value->summary);
                        $data['visible']=$value->visible;
                        $data['courseimage']=learncourseimage($value->id);
                        $rdata=$this->courseprice($value->id);
                        $data['cost']=$rdata['cost'];
                        $data['currency']=$rdata['currency'];
                        $coursecontentdata=learncoursecontent($value->id);
                         $data['coursealldetails']=coursealldetails($value->id);
                        $data['activity']=$coursecontentdata;
                      

                      array_push($coursealldata, $data);
                    }
                   
                   categoriesdescription($categoriesdata->id,$categoriesdata->description);
                   $categoriesdata->fullcategoriesdesc=categoriesdescription($categoriesdata->id,$categoriesdata->description);
                   $categoriesdata->coursedata=$coursealldata;

        array_push($alldata, $categoriesdata);        

    }                


        return $alldata;
}

function courseprice($courseid){
    global $DB, $CFG, $USER;
     $coursespaytm =  $DB->get_record('enrol',array('courseid'=>$courseid,'enrol'=>'paytm'));
     $coursesrazorpay = $DB->get_record('enrol',array('courseid'=>$courseid,'enrol'=>'razorpay'));
    $data=array();
    $data['cost']="";
    $data['currency']="";
    
    if($coursespaytm->cost>=$coursesrazorpay->cost){
          $data['cost']=$coursespaytm->cost;
          $data['currency']=$coursespaytm->currency;
    }else{
        $data['cost']=$coursesrazorpay->cost;
        $data['currency']=$coursesrazorpay->currency;
    }

    return $data;
}



    
}


