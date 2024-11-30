<?php
namespace local_oauth\task;
 
/**
 * An example of a scheduled task.
 */
class UserEnrollments extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name(){
        return "User Enrollments Mapping";
    }
 
    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;
        echo "run....";
       $allgroupdata=allgroupdata();
       $count=0;
    foreach ($allgroupdata as $groupvalue) {
        $alluserdata=alluserapidata();
        foreach ($alluserdata->data as $userValue) {
                if($userValue->data->roles->student->school==$groupvalue->api_groupid){
                        $userdata=array();
                        $userdata['apischoolid']=$userValue->data->roles->student->school;
                        $userdata['apiuserid']=$userValue->data->id;
                        $userdata['username']=$userValue->data->roles->student->credentials->district_username;
                        $userdata['firstname']=$userValue->data->name->first." ".$userValue->data->name->middle;
                        $userdata['lastname']=$userValue->data->name->last;
                        $userdata['gender']=$userValue->data->roles->student->gender;
                        $userdata['grade']=$userValue->data->roles->student->grade;
                        $userdata['email']=$userValue->data->email;
                        $userdata['otheremail']=$userValue->data->roles->student->email;
                        $userdata['address']=$userValue->data->roles->student->location->address;
                        $userdata['city']=$userValue->data->roles->student->location->city;
                        $userdata['state']=$userValue->data->roles->student->location->state;
                        $userdata['zip']=$userValue->data->roles->student->location->zip;
                        $userid=userAction($userdata);

                        enrolledAction($groupvalue->groupid,$groupvalue->api_courseid,$userid);
                        $count++;
                
                    //print_r($groupvalue->api_groupid,$groupvalue->groupid,$userid,);
                        require_once($CFG->libdir.'/adminlib.php');
                    purge_caches();
                
                }

        }
    }
        echo "\ntotal updated records: ".$count;
    }
   
}