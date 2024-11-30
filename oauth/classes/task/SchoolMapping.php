<?php
namespace local_oauth\task;
 
/**
 * An example of a scheduled task.
 */
class SchoolMapping extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name(){
        return "School Mapping";
    }
 
    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;
        echo "run....";
      
        $allschool=allschool();
        $count=0;
        foreach ($allschool->data as $schoolValue) {
            $schooldata=schooldata($schoolValue->data->id);
            foreach ($schooldata->data as $apicourseValue) {
                groupAction($schoolValue->data->id,$schoolValue->data->name,$apicourseValue->data->id);
                $count++;
            }
        }

        echo "\ntotal updated records: ".$count;
    }
   
}