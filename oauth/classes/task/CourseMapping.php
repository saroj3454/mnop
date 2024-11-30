<?php
namespace local_oauth\task;
 
/**
 * An example of a scheduled task.
 */
class CourseMapping extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name(){
        return "Course Mapping";
    }
 
    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;
        echo "run....";
        $alldistrict=alldistrict();
        $count=0;
        foreach ($alldistrict as $districtValue) {
        // $value->districtid;
            $get_course=get_courseApidata();
            foreach ($get_course->data as $value) {
                if($value->data->district==$districtValue->district_id){
                    coursesAction($value->data->name,$value->data->id,$districtValue->district_id,$districtValue->cat_id);
                    $count++;
                }
    
            }
        }
        echo "\ntotal course updated records: ".$count;
       
    }
   
}