<?php

use format_elearnified\util\completionhelper;


require_once('../../config.php');
require_once($CFG->dirroot . '/completion/classes/progress.php');
require_once($CFG->dirroot . "/local/ecommerce/classes/models/productModel.php");
require_once($CFG->dirroot . "/local/ecommerce/classes/models/producttypeModel.php");
require_once($CFG->dirroot . "/local/ecommerce/classes/models/categorytypeModel.php");
require_once($CFG->dirroot . "/local/ecommerce/classes/models/promocodeModel.php");
require_once($CFG->dirroot . "/local/ecommerce/classes/models/cartModel.php");
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/filelib.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("HTTP/1.0 200 Successfull operation");
$getpatameter = json_decode(file_get_contents('php://input', True), true);
$functionname = null;
$args = null;
if (is_array($getpatameter)) {
    $functionname = $getpatameter['wsfunction'];
    $args = $getpatameter['wsargs'];
}


function update_user_field($userid,$key,$data){ 
    global $DB;	
    // $data=new stdClass();
    // $data->
    if($DB->record_exists('user_info_data',array('userid'=>$userid,'fieldid'=>$key))){

             $sql = "UPDATE mdl_user_info_data SET `data`= '".$data."' WHERE userid=$userid AND fieldid=$key";
			//die;
           $res =$DB->execute($sql);
     }else{
		// echo "new data"; die;
        $stdobj = new stdClass();
        $stdobj->userid=$userid;
        $stdobj->fieldid = $key;
        $stdobj->data = $data;
		//print_r($stdobj);
		
        $DB->insert_record('user_info_data',$stdobj);
     }
	// print_r($stdobj);
	// die; 
  }
class APIManager
{
    public $status = 0;
    public $message = "Error";
    public $data = null;
    public $code = 404;
    public $error = array(
        "code" => 404,
        "title" => "Server Error.",
        "message" => "Server under maintenance"
    );
    function __construct()
    {
        $this->code = 404;
        $this->error = array(
            "code" => 404,
            "title" => "Server Error..",
            "message" => "Missing functionality"
        );
    }
    private function sendResponse($data)
    {
        $this->status = 1;
        $this->message = "Success";
        $this->data = $data;
        $this->code = 200;
        $this->error = null;
    }
    private function sendError($title, $message, $code = 400)
    {
        $this->status = 0;
        $this->message = "Error";
        $this->data = null;
        $this->code = $code;
        $this->error = array(
            "code" => $code,
            "title" => $title,
            "message" => $message
        );
    }
function useractivitydata($pagedata){
     global $DB,$USER,$OUTPUT; 

     $perpagerecords="10";
     $pageid=$pagedata->pageid;
   
    
    
     if(empty($pageid)){
        $sql="SELECT *  FROM {logstore_standard_log} WHERE `userid` = '".$USER->id."' ORDER BY `id` DESC LIMIT 0, 10";
     }else{
              $pageid=$pagedata->pageid - 1;
              $startingpage = $pageid * $perpagerecords;
              $pageto=$pageid*$perpagerecords;
        /* $from=$pageid-1;
         $pagefrom=$pageid*$perpagerecords-10;*/

        $sql="SELECT *  FROM {logstore_standard_log} WHERE `userid` = '".$USER->id."' ORDER BY `id` DESC LIMIT ".$startingpage.", ".$perpagerecords."";
     }
    $logsdata=$DB->get_records_sql($sql);
    $arraydata=array();
    foreach ($logsdata as $event) {
                  try {
                $eventname=$event->eventname;
                $otherdata=json_decode($event->other, true);
                $data=$eventname::create(['component'=>$event->component, 'action'=>$event->action, 'target'=>$event->target, 'objecttable'=>$event->objecttable, 'objectid'=>$event->objectid, 'crud'=>$event->crud, 'edulevel'=>$event->edulevel, 'contextid'=>$event->contextid, 'contextlevel'=>$event->contextlevel, 'contextinstanceid'=>$event->contextinstanceid, 'userid'=>$event->userid, 'courseid'=>$event->courseid, 'relateduserid'=>$event->relateduserid, 'anonymous'=>$event->anonymous, 'other'=>$otherdata, 'timecreated'=>$event->timecreated ,'origin'=>$event->origin, 'realuserid'=>$event->realuserid]);
              
                $pushdata=array();
                $pushdata['evename']=$data->get_name();
                $pushdata['description']=$data->get_description();
                $pushdata['id']=$event->id;
                $pushdata['timecreated']=self::timeAgo($event->timecreated);
                  }catch(Exception $e) {
                     return;

                     }
  

                array_push($arraydata,$pushdata);
            
              
                            

   }

    // print_r($data->get_description());
                 $setting = array("activitydata"=>$arraydata);
                $data = $OUTPUT->render_from_template('block_elearnified_recent_activites/activity', $setting); 
               $this->sendResponse($data);



}

function pagerecordsdata($currentdata){
    global $DB,$USER,$OUTPUT; 

    if(empty($currentdata->pageid)){
        $current='1'-1;
    }else{
       $current=$currentdata->pageid-1;
    }
    $perpagerecords="10";
    $logsdata=$DB->get_field_sql("SELECT count(id) as total  FROM `mdl_logstore_standard_log` WHERE `userid` = '".$USER->id."' ORDER BY `id` DESC limit 0,1");
    $page="";
    $pagedata="";
        
    $data=self::smallpagination($logsdata, $perpagerecords, $current);
     foreach($data as $pageingdata) {

            if($pageingdata['active'] =="active"){
                $active="active";
            }else{
                 $active="";
            }
            $pagedata.='<li pageid="' .$pageingdata['display']. '" class="pageid page-item '.$active.'">' .$pageingdata['display']. ' </li>';  
        }  
    $setting = array("pagedata"=>$pagedata);
    $data = $OUTPUT->render_from_template('block_elearnified_recent_activites/pagerecords', $setting); 
    $this->sendResponse($data);
}


public static function timeAgo($time_ago)
{
 
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

    public function userEditemailvalidate($args)
    {
        global $CFG, $PAGE, $USER, $OUTPUT, $DB;
        if (!empty($args->userid)) {
            $userid = $args->userid;
        } else {
            $userid = $USER->id;
        }
        $return = array();

        $edata = $DB->get_record_sql("SELECT * FROM {user} where (`username`='" . $args->email . "' or `email`='" . $args->email . "') and id!='" . $userid . "'");
        if (!empty($edata)) {
            $return['email'] = "already exists";
            $return['status'] = "1";
        } else {
            $return['status'] = "0";
        }
        $this->sendResponse($return);
    }

    public function useroldPasswordvalidate($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        if (!empty($args->userid)) {
            $userid = $args->userid;
        } else {
            $userid = $USER->id;
        }
        $return = array();
        $userdata = $DB->get_record('user', array('id' => $userid));
        if (!empty($userdata)) {
            if (!$user = authenticate_user_login($userdata->username, $args->oldpassword, true, $reason, false)) {
                $return['status'] = "1";
                $return['returnstatus'] = "Old password is not Correct";
                $this->sendError('Error', $return['returnstatus'], 400);
            } else {
                $return['status'] = "0";
            }
            $this->sendResponse($return);
        }
    }


    public function savepersonalinformation($args)
    {
        global $USER, $CFG, $DB, $SESSION, $OUTPUT, $COURSE;
        require_once($CFG->dirroot . '/user/lib.php');
         $companydata = $DB->get_record_sql("SELECT c.*,uic.name as companyshortname FROM {company_domains} as cd INNER JOIN {company} as c on cd.companyid=c.id INNER JOIN {user_info_category} as uic on c.profileid=uic.id  INNER JOIN {company_users} as cu on cd.companyid=cu.companyid left join {department} as d ON c.id=d.company and d.parent='0' where c.suspended='0' AND cd.domain LIKE '%" . $_SERVER['SERVER_NAME'] . "%' and cu.userid='".$USER->id."'");
          $companyshortname = str_replace(" ", "",$companydata->companyshortname);
       
        if($USER->id) {
            if ($args->formdata != "") {

                $newdata = array();
                $userdata = new stdClass();
                $userdata->id = $USER->id;
                $userdata->firstname = $args->formdata['firstname'];
                $userdata->lastname = $args->formdata['lastname'];
                     $orgdata=$DB->get_record_sql("SELECT *  FROM {company_users} WHERE userid ='".$USER->id."'");
                      if(!empty($orgdata)){
                        $elorganisation=$orgdata->departmentid;
                          if($orgdata->managertype!=0){
                             $userdata->email = $args->formdata['email'];
                             $orgdata->departmentid=$args->formdata['organisation'];
                             $DB->update_record('company_users',$orgdata);  

                          }
                      }
               

                $userdata->address = $args->formdata['address'];
                $userdata->city = $args->formdata['state'];
                $userdata->country = $args->formdata['country'];
                $userdata->phone1 = $args->formdata['contact'];

                 $edata = $DB->get_record_sql("SELECT * FROM {user} where (`username`='" . $args->formdata['email'] . "' or `email`='" . $args->formdata['email'] . "') and id!='" . $USER->id . "'");
                 if (!empty($edata)) {
                $return['email_error'] = "already exists";
                $return['status'] = "0";
                $return['message'] = "fail";
                } else {
                    user_update_user($userdata, false, false);
                    if(!empty($companydata->companyshortname)){
                    $userid = $USER->id;
                        if ($userid) {
                            // $newdata[$companyshortname.'organisation'] = $args->formdata['organisation'];
                            $newdata[$companyshortname.'houseno'] = $args->formdata['houseno'];
                            $newdata[$companyshortname.'zip'] = $args->formdata['zip1'];
                            $newdata[$companyshortname.'landmark'] = $args->formdata['landmark'];
                            $newdata[$companyshortname.'dob'] = $args->formdata['dob'];
                            $newdata[$companyshortname.'gender'] = $args->formdata['gender'];
                            foreach($newdata as $key => $data){
                                $rdata=$DB->get_record('user_info_field',array('shortname'=>$key));
                                if(!empty($rdata)){
                                    update_user_field($userid, $rdata->id,$data);
                                }
                                
                              }
                        }
                    }   

                    $return['status'] = "1";
                    $return['message'] = "Updation Succesfully";

                }

            if(!empty($args->formdata['newpassword']) && !empty($args->formdata['oldpassword'])){
                    $userdata = $DB->get_record('user', array('id' => $userid));
                    if (!empty($userdata)) {
                        if (!$user = authenticate_user_login($userdata->username, $args->formdata['oldpassword'], true, $reason, false)) {
                            $return['status'] = "0";
                            $return['password_error'] = "Old password is not Correct";
                            $return['message'] = "fail"; 
                        } else {


                            $user=$DB->get_record('user',array('id'=>$USER->id));
                                  $userauth = get_auth_plugin($user->auth);
                                if (!empty($args->formdata['newpassword'])) {
                                    if (!$userauth->user_update_password($user, $args->formdata['newpassword'])) {
                                        print_error('errorpasswordupdate', 'auth');
                                    }
                                }
                            $return['status'] = "1";
                            $return['message'] = "Updation Succesfully";    
                        }
                        
                    }
              }          
  
                $this->sendResponse($return);

            } else {
                $this->sendError("Error", "Data not found", 404);
            }
        } else {
            $this->sendError('USer error', 'User not Logged in');
        }
    }

    public function filterprcproducts($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        require_once($CFG->dirroot . "/local/ecommerce/classes/models/productModel.php");
        $filterArg = new stdClass();
        if (empty($args->catid)) {
            $filterArg->category = $args->allcat;
        } else {
            $filterArg->category = array($args->catid);
        }
        $filterArg->cartID = $args->cartID;
        $filterArg->page = 0;
        $filterArg->perpage = 4;
        $PM = new productModel();
        $filtereddata = $PM->filterall($filterArg);

        foreach ( $filtereddata['data'] as $key => $item) {
            $item->price=number_format($item->price,2,".",","); 

            }
        $setting = array('config_title' => $config_prc_title, 'products' => $filtereddata['data']);
        $data = $OUTPUT->render_from_template('local_ecommerce/prcproductspage', $setting);
        $this->sendResponse($data);
    }
    public function userchangeprofilephoto($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        require_once($CFG->libdir . '/adminlib.php');
        require_once($CFG->libdir . '/filelib.php');
        $fs = get_file_storage();
        if (!empty($args->userid)) {
            $userid = $args->userid;
        } else {
            $userid = $USER->id;
        }
        $wsfiledata_decoded = file_get_contents($args->image);

        $context = context_user::instance($userid, MUST_EXIST);
        $user = core_user::get_user($userid, '*', MUST_EXIST);
        $newpicture = $user->picture;
        $userdata = $DB->get_record('user', array('id' => $userid));
        $fileinfo = array(
            'contextid' => $context->id,
            'component' => 'user',
            'filearea' => 'newicon',     // usually = table name
            'itemid' => '0',               // usually = ID of row in table
            'filepath' => "/",           // any path beginning and ending in /
            'filename' => 'f1.jpg'
        ); // any filename
        if (!empty($args->image)) {
            $user->imagefile = 0;
            $fs->create_file_from_string($fileinfo, $wsfiledata_decoded);
            $filemanageroptions = array(
                'maxbytes'       => $CFG->maxbytes,
                'subdirs'        => 0,
                'maxfiles'       => 1,
                'accepted_types' => 'optimised_image'
            );
            file_prepare_draft_area($user->imagefile, $context->id, 'user', 'newicon', 0, $filemanageroptions);
            $user->imagefile = $user->imagefile;
        } else {
            $user->deletepicture = 1;
        }
        core_user::update_picture($user, $filemanageroptions);

        $userpicture = new user_picture(core_user::get_user($userid));
        $userpicture->size = 150; // Size f1.
        $profileimageurl = $userpicture->get_url($PAGE)->out(false);


        $this->sendResponse(array('imageurl' => $profileimageurl));
    }


    public function changeprofilephoto($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        $alldata = array();
        if (!empty($args->photo)) {
            $sql = "SELECT * FROM {user}  WHERE id=? ";
            $userData = $DB->get_record_sql($sql, array($USER->id));
            if ($userData->picture != "") {
                $userData->picture = ($args->photo);
                $userDataupdate = $DB->update_record('user', $userData);
                array_push($alldata, $userDataupdate);
                $this->sendResponse('updation done');
            } else {
                $userData->picture = $args->photo;
                $userDataupdate = $DB->insert_record('user', $userData);
                array_push($alldata, $userDataupdate);
                $this->sendResponse('insertion done');
            }
            if (!empty($alldata)) {
                $data = $OUTPUT->render_from_template('block_elearnified_profile_image/index', $alldata);
                $this->sendResponse($data);
                echo "<pre>";
                print_r($alldata);
                echo "</pre>";
            }
        } else {
            $this->sendResponse('data not found');
        }
    }

    public function myelearnifiedcourses($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        $alldata = array();
        $courseData = null;
        $current_time = time();
        switch ($args->status) {
            case "active":
                $sql = "SELECT c.* FROM {user_enrolments}  ue  JOIN {enrol} as e on ue.enrolid=e.id  JOIN {course}  c on e.courseid=c.id WHERE ue.userid=? AND e.roleid = ? AND ue.status=? AND (ue.timeend >= ?  OR  ue.timeend = ? ) ";
                $courseData = $DB->get_records_sql($sql, array($USER->id, 5, 0, $current_time, 0));
                break;
            case "deactive":
                $sql = "SELECT c.* FROM {course} c JOIN {enrol} e on e.courseid=c.id JOIN {user_enrolments} ue  on ue.enrolid=e.id WHERE ue.userid=? && e.roleid = ?  AND ue.timeend < ?  AND  ue.timeend <> ? ";
                $courseData = $DB->get_records_sql($sql, array($USER->id, 5, $current_time, 0));
                break;
            case "suspended":
                $sql = "SELECT c.*  FROM {user_enrolments}  ue  JOIN {enrol}  e on ue.enrolid=e.id  JOIN {course}  c on e.courseid=c.id WHERE ue.userid=? && e.roleid = ? AND  ue.status=?";
                $courseData = $DB->get_records_sql($sql, array($USER->id, 5, 1));
                break;
            default:
                $sql = "SELECT c.*  FROM {user_enrolments}  ue  JOIN {enrol}  e on ue.enrolid=e.id  JOIN {course}  c on e.courseid=c.id WHERE ue.userid=? && e.roleid = ?";
                $courseData = $DB->get_records_sql($sql, array($USER->id, 5));
        }
        if (!empty($courseData)) {
            if (!empty($args->coursename)) {
                $alldata = array();
                foreach ($courseData as $course) {
                    if (strpos(strtolower($course->fullname), strtolower($args->coursename)) !== false) {
                        $percentage = completionhelper::get_course_completion_percentage($course, $USER->id);
                        if (empty($percentage)) {
                            $percentage = "0.00";
                        }

                        $percentage = $percentage['percentage'];
                        $data = array('courseid' => $course->id, 'courseimage' => $this->elearnifiedcourseimage($course->id), 'coursesname' => $this->elearnifiedcoursename($course->id), "course_progress" => $percentage);
                        array_push($alldata, $data);
                    }
                }
            } else {
                foreach ($courseData as $courseValue) {
                    $percentage = completionhelper::get_course_completion_percentage($courseValue, $USER->id);


                    if (empty($percentage)) {
                        $percentage = "0.00";
                    }
                    $percentage = $percentage['percentage'];
                    $data = array('courseid' => $courseValue->id, 'courseimage' => $this->elearnifiedcourseimage($courseValue->id), 'coursesname' => $this->elearnifiedcoursename($courseValue->id), "course_progress" => $percentage);
                    array_push($alldata, $data);
                }
            }
            if (!empty($alldata)) {
                $data = $OUTPUT->render_from_template('block_elearnified_courses_dashboard/mycourses', array('coursedata' => $alldata));
                $this->sendResponse($data);
            } else {
                $this->sendResponse('Data not found');
            }
        } else {
            $this->sendResponse('Data not found');
        }
    }

    public function elearnifiedcourses($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER, $DB;
        $data = $OUTPUT->render_from_template('block_elearnified_courses_dashboard/course_dashboard', array());
        $this->sendResponse($data);
    }

    public function elearnifiedcourseimage($courseid)
    {
        global $DB, $CFG;
        $imageurl = "";
        require_once($CFG->dirroot . '/course/classes/list_element.php');
        $course = $DB->get_record('course', array('id' => $courseid));
        $course = new core_course_list_element($course);
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $imageurl = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' . $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
            return $imageurl;
        }
        return $imageurl;
    }

    public function elearnifiedcoursename($courseid)
    {
        global $DB, $CFG;
        $course = $DB->get_record('course', array('id' => $courseid));
        return $course->fullname;
    }
    //_scheduleTasks
    public function scheduleTasks($args)
    {
        global $DB, $CFG, $USER, $OUTPUT;
        $schedule = array();
        $sql = "SELECT s.*,c.fullname as cname,ss.starttime as dur ,sa.attended as atte,ss.appointmentlocation as slotmylocation FROM {elscheduler} s JOIN {course} c ON c.id=s.course JOIN {elscheduler_slots} ss ON ss.elschedulerid=s.id JOIN {elscheduler_appointment} sa ON  sa.slotid=ss.id WHERE sa.studentid=? AND ss.starttime >= ? ";
        $scheduledata = $DB->get_records_sql($sql, array($USER->id, time()));
        foreach ($scheduledata as $schedules) {
            $schedule_data = array(
                'slotmylocation' => $schedules->slotmylocation,
                'id' => $schedules->id,
                'name' => $schedules->cname,
                'time' => date("d F Y", $schedules->dur),
                'attended' => $schedules->atte,
            );
            if ($schedules->atte == 1) {
                $schedule_data['attended'] = 'Cancelled';
                $schedule_data['class'] = 'schedules-deactive';
            } else {

                $schedule_data['attended'] = 'Active';
                $schedule_data['class'] = 'schedules-active';
            }
        }

        $this->data = $schedule_data;
        $data = $OUTPUT->render_from_template('block_elearnified_courses_dashboard/scheduler', array('scheduler' => $schedule_data));
        $this->sendResponse($data);
    }


    public function usernameEmailexit($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $return = array();
        $udata = $DB->get_records_sql("SELECT * FROM {user} where `username`=?", array($args->username, $args->username));
        if (!empty($udata)) {
            $return['username'] = "allready exists";
        }
        $edata = $DB->get_records_sql("SELECT * FROM {user} where `username`=? or `email`=?", array($args->email, $args->email));
        if (!empty($edata)) {
            $return['email'] = "allready exists";
        }
        if (!empty($return)) {
            $return['status'] = "1";
        } else {
            $return['status'] = "0";
        }
        $this->sendResponse($return);
    }
    public function newuserRegister($args)
    {
        global $CFG, $DB, $SESSION;



        $user = new stdClass();
        $user->username = strtolower($args->username);
        $user->auth = "elearnified";
        $user->password = md5($args->password);
        $user->mnethostid = "1";
        $user->timecreated = time();
        $user->email = strtolower($args->email);
        $user->email2 = strtolower($args->email_a);
        $user->firstname = $args->fname;
        $user->lastname = $args->lname;
        $user->lastname = $args->lname;
        $user->submitbutton = "Create my new account";


        require_once($CFG->dirroot . '/user/profile/lib.php');
        require_once($CFG->dirroot . '/user/lib.php');
        $plainpassword = $user->password;
        $user->password = hash_internal_user_password($user->password);
        if (empty($user->calendartype)) {
            $user->calendartype = $CFG->calendartype;
        }

        $user->id = user_create_user($user, false, false);

        user_add_password_history($user->id, $plainpassword);

        // Save any custom profile field information.
        profile_save_data($user);

        // IOMAD.
        $companydata = $DB->get_records_sql = $DB->get_records_sql("SELECT c.* FROM {company_domains} as cd INNER JOIN {company} as c on cd.companyid=c.id where c.suspended='0' AND cd.domain LIKE '%" . $_SERVER['SERVER_NAME'] . "%'");
        if (!empty($companydata)) {
            // IOMAD.
            foreach ($companydata as $companyvalue) {
                if (!empty($companyvalue->id)) {
                    require_once($CFG->dirroot . '/local/iomad/lib/company.php');
                    $company = new company($companyvalue->id);

                    // assign the user to the company.
                    $company->assign_user_to_company($user->id);

                    // Assign them to any department.
                    $defaultdepartment = company::get_company_parentnode($companyvalue->id);
                    $company->assign_user_to_department($defaultdepartment->id, $user->id);


                    if ($CFG->local_iomad_signup_autoenrol) {
                        $company->autoenrol($user);
                    }
                }
            }
        }

        // Save wantsurl against user's profile, so we can return them there upon confirmation.
        if (!empty($SESSION->wantsurl)) {
            set_user_preference('auth_email_wantsurl', $SESSION->wantsurl, $user);
        }

        // Trigger event.
        \core\event\user_created::create_from_userid($user->id)->trigger();

        // if (!send_confirmation_email($user)) {
        //     print_error('auth_emailnoemail', 'auth_email');
        // }


        $return = array();
        $return['status'] = '1';
        $this->sendResponse($return);
    }



    public function openAddProduct($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        $PTM = new producttypeModel();
        $CTM = new categorytypeModel();
        $PCM = new promocodeModel();
        $allproducts = array_values($PM->getall());
        $allproducttype = array_values($PTM->getall());
        $allcategorytype = array_values($CTM->getall());
        $allcategorytype = array_values($CTM->getall());
        $allpromocode = array_values($PCM->getall());
        $allcourses = array_values($PM->relatedAllProduct());
        if ($args->id) {
            $id = $args->id;
            $product = $PM->getproduct($id);
            $related_product_arr = explode(",", $product->relatedproducts);
            foreach ($allcourses as $key => $course) {
                $allcourses[$key]->selected = "";
                if (in_array($course->id, $related_product_arr)) {
                    $allcourses[$key]->selected = "selected";
                }
            }
        }
        $allcompany = array_values($DB->get_records("company", array(), 'name', 'id, name'));
        foreach ($allcompany as $key => $cmp) {
            $allcompany[$key]->selected = (($cmp->id == $product->companyid) ? "selected" : '');
        }
        foreach ($allcategorytype as $key => $ctype) {
            $allcategorytype[$key]->selected = (($ctype->id == $product->category) ? "selected" : '');
        }
        foreach ($allproducttype as $key => $ptype) {
            $allproducttype[$key]->selected = (($ptype->id == $product->type) ? "selected" : '');
        }
        foreach ($allpromocode as $key => $pcode) {
            $allpromocode[$key]->selected = (($pcode->id == $product->promocode) ? "selected" : '');
        }
        $newpdata = array(
            "prodtype" => $allproducttype,
            "categorytype" => $allcategorytype,
            "allcompany" => $allcompany,
            "promocode" => $allpromocode,
            "allprod" => $allproducts,
            "id" => ($product->id ? $product->id : 0),
            "popular" => ($product->popular ? $product->popular : 0),
            "productid" => ($product->productid ? $product->productid : ""),
            "name" => ($product->name ? $product->name : ""),
            "type" => ($product->type ? $product->type : ""),
            "category" => ($product->category ? $product->category : ""),
            "description" => ($product->description ? $product->description : ""),
            "relatedproducts" => ($product->relatedproducts ? $product->relatedproducts : ""),
            "price" => ($product->price ? $product->price : ""),
            "images" => ($product->images ? $product->images : array()),
            "default_image" => ($product->image ? $product->image : 0),
            "allcourses" => $allcourses,
        );
        $this->newpdata = $newpdata;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/new_product', $newpdata);
        $this->sendResponse($data);
    }
    public function openViewProduct($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        if ($args->id) {
            $id = $args->id;
            $product = $PM->viewproduct($id);
        }
        $newpdata = array(
            "id" => ($product->id ? $product->id : 0),
            "productid" => ($product->productid ? $product->productid : ""),
            "name" => ($product->name ? $product->name : ""),
            "type" => ($product->type ? $product->type : ""),
            "ptname" => ($product->ptname ? $product->ptname : ""),
            "companyname" => ($product->companyname ? $product->companyname : ""),
            "categoryname" => ($product->categoryname ? $product->categoryname : ""),
            "category" => ($product->category ? $product->category : ""),
            "description" => ($product->description ? $product->description : ""),
            "relatedproducts" => ($product->relatedproducts ? $product->relatedproducts : ""),
            "price" => ($product->price ? $product->price : ""),
            "images" => ($product->images ? $product->images : array()),
        );
        $this->product = $product;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/view_product', $newpdata);
        $this->sendResponse($data);
    }
    public function viewproductAddtocard($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        if ($args->id) {
            $id = $args->id;
            $cartID="";
            if(!empty($args->cartID)){
                $cartID=$args->cartID;
            }
            $product = $PM->viewproduct($id,$cartID);
        }
        $newpdata = array(
            "id" => ($product->id ? $product->id : 0),
            "productid" => ($product->productid ? $product->productid : ""),
            "name" => ($product->name ? $product->name : ""),
            "type" => ($product->type ? $product->type : ""),
            "ptname" => ($product->ptname ? $product->ptname : ""),
            "promocode" => ($product->promocode ? $product->promocode : 0),
            "companyname" => ($product->companyname ? $product->companyname : ""),
            "categoryname" => ($product->categoryname ? $product->categoryname : ""),
            "category" => ($product->category ? $product->category : ""),
            "description" => ($product->description ? $product->description : ""),
            "relatedproducts" => ($product->relatedproducts ? $product->relatedproducts : ""),
            "price" => ($product->price ? number_format($product->price,2,".",",") : ""),
            "images" => ($product->images ? $product->images : array()),
            "mainimage" => ($product->images ? $product->images[0] : null),
            "rating" => ($product->rating ? $product->rating : 5),
            "ratingdata" => $product->ratingdata,
            "ratingdetails" => $product->ratingdetails,
            "totalreviews" => $product->totalreviews,
            "cartexit" => $product->cartexit
        );
        $this->product = $product;
        $this->newpdata = $newpdata;
        $data = $OUTPUT->render_from_template('block_elearnified_catalogue/popdetail', $newpdata);
        $this->sendResponse($data);
    }
    public function deleteProduct($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PM = new productModel();
        if ($args->productid) {
            $id = $args->productid;
            if ($PM->deleteproduct($id)) {
                $this->sendResponse("Deleted Successfully");
            } else {
                $this->sendError("Operation Failed", "Failed to delete product");
            }
        } else {
            $this->sendError("Operation Failed", "Missing paramters");
        }
    }
    public function openstatusmessage($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $template = $args->template ? $args->template : "eventstatus";
        $status = $args->status ? $args->status : "success";
        $message = $args->message ? $args->message : "default message";
        $templatedata = array(
            "status" => $status,
            "message" => $message,
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/' . $template, $templatedata);
        $this->sendResponse($data);
    }
    public function addmorfile($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/fileselector', array());
        $this->sendResponse($data);
    }
    public function saveNewProduct($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PM = new productModel();
        if ($PM->saveProduct($args)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function openImportForm($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $new_data = array();
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/import_product', $new_data);
        $this->sendResponse($data);
    }
    public function importproduct($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        $allnewproducts = array();
        $errordata = array();
        if (!empty($args->csvfile)) {
            $csvFile = fopen($args->csvfile, 'r');
            fgetcsv($csvFile);
            $rowcounter = 0;
            while (($getData = fgetcsv($csvFile)) !== FALSE) {
                $product = new stdClass();
                $rowcounter++;
                $product->company = $getData[0];
                if (!empty($getData[0])) {
                    if ($name = $DB->get_record("company", array("name" => $getData[0]))) {
                        $product->name = $company->name;
                    } else {
                        array_push($errordata, "Unable to find Company with name as '" . $getData[0] . "' at row " . $rowcounter);
                    }
                }
                $product->name = $getData[1];
                if (!empty($getData[2])) {
                    if ($category = $DB->get_record("categorytype", array("name" => $getData[2]))) {
                        $product->category = $category->id;
                    } else {
                        array_push($errordata, "Unable to find Name with category as '" . $getData[2] . "' at row " . $rowcounter);
                    }
                }
                $product->category = $getData[2];
                $product->description = $getData[3];
                if (!empty($getData[4])) {
                    if ($promocode = $DB->get_record("promocode", array("promoid" => $getData[4]))) {
                        $product->promocode = $promocode->id;
                    } else {
                        array_push($errordata, "Unable to find Promocode with code as '" . $getData[4] . "' at row " . $rowcounter);
                    }
                }
                if (!empty($getData[5])) {
                    $product->price = $getData[5];
                } else {
                    array_push($errordata, "Price missing at row " . $rowcounter);
                }
                // if(!empty($errordata)){
                //     $product->images = array();
                //     $product->imagesname = array();
                //     if(!empty($getData[6])){
                //         try {
                //             if($imagedata = self::geturldata($getData[6])){
                //                 // array_push($product->images, $imagedata);
                //                 array_push($product->imagesname, $imagedata);
                //             } else {
                //                 array_push($errordata, "Unable to find Image at URL '".$getData[6]."' at row ".$rowcounter);
                //             }
                //         } catch (Exception $e) {
                //             array_push($errordata, "Unable to get Image at URL '".$getData[6]."' at row ".$rowcounter);
                //         }
                //         array_push($product->image, array_pop(explode("/", $getData[6])));
                //     }
                //     // if(!empty($getData[7])){
                //     //     try {
                //     //         if($imagedata = file_get_contents($getData[7])){
                //     //             array_push($product->images, $imagedata);
                //     //         } else {
                //     //             array_push($errordata, "Unable to find Image at URL '".$getData[7]."' at row ".$rowcounter);
                //     //         }
                //     //     } catch (Exception $e) {
                //     //         array_push($errordata, "Unable to get Image at URL '".$getData[7]."' at row ".$rowcounter);
                //     //     }
                //     //     array_push($product->imagesname, array_pop(explode("/", $getData[7])));
                //     // }
                //     // if(!empty($getData[8])){
                //     //     try {
                //     //         if($imagedata = file_get_contents($getData[8])){
                //     //             array_push($product->images, $imagedata);
                //     //         } else {
                //     //             array_push($errordata, "Unable to find Image at URL '".$getData[8]."' at row ".$rowcounter);
                //     //         }
                //     //     } catch (Exception $e) {
                //     //         array_push($errordata, "Unable to get Image at URL '".$getData[8]."' at row ".$rowcounter);
                //     //     }
                //     //     array_push($product->imagesname, array_pop(explode("/", $getData[8])));
                //     // }
                //     // if(!empty($getData[9])){
                //     //     try {
                //     //         if($imagedata = file_get_contents($getData[9])){
                //     //             array_push($product->images, $imagedata);
                //     //         } else {
                //     //             array_push($errordata, "Unable to find Image at URL '".$getData[9]."' at row ".$rowcounter);
                //     //         }
                //     //     } catch (Exception $e) {
                //     //         array_push($errordata, "Unable to get Image at URL '".$getData[9]."' at row ".$rowcounter);
                //     //     }
                //     //     array_push($product->imagesname, array_pop(explode("/", $getData[9])));
                //     // }                                                                                                                                                                                                                     
                // }
                array_push($allnewproducts, $product);
            }
            $this->new_data = $allnewproducts;
            fclose($csvFile);
            if (empty($errordata)) {
                foreach ($allnewproducts as $key => $product) {
                    $PM->saveProduct($product);
                }
                $this->sendResponse("Import successfully");
            } else {
                // $this->new_data = $errordata; 
                $this->sendError("Error importing files", implode('<br>', $errordata), $code = 400);
                //$this->sendResponse("Import Failed",$abc);
            }
        } else {
            $this->sendError("Error importing files", "Failed to import try again", $code = 400);
        }
    }

    public function confirmationbox($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $newpdata = array(
            "itemid" => ($args->itemid ? $args->itemid : 0),
            "heading" => ($args->heading ? $args->heading : "Confirm"),
            "subheading" => ($args->subheading ? $args->subheading : ""),
            "description" => ($args->description ? $args->description : ""),
            "action" => ($args->action ? $args->action : ""),
            "data" => json_encode($args)
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/confirmation-box', $newpdata);
        $this->sendResponse($data);
    }

    public function filterProducts($args)
    {
        global $OUTPUT;
        try {
            $PM = new productModel();
            $data = $PM->filterall($args);


            $settings = array(
                "products" => $data['data'],
                "total" => $data['total']

            );
            $this->query = $data;
            $this->filtersdata = $data['filtersdata'];
            $maincontaine = $OUTPUT->render_from_template('block_elearnified_catalogue/filtereddata', $settings);
            $limitfrom = $args->perpage * $args->page;
            $limitto = $limitfrom + $args->perpage;
            $allpagesdrops = array(10, 20, 50, 100);
            $allpagesdropsoptions = array();
            foreach ($allpagesdrops as $key => $drops) {
                $dd = array("option" => $drops, "selected" => ($drops == $args->perpage ? 'selected' : ''));
                array_push($allpagesdropsoptions, $dd);
            }
            $pagination = '';
            $paginationsettings = array(
                "total" => $settings['total'],
                "from" => $limitfrom + 1,
                "to" => ($settings['total'] > $limitto ? $limitto : $settings['total']),
                "allpagesdropsoptions" => $allpagesdropsoptions,
                "pagination" => self::pagination($settings['total'], $args->perpage, $args->page),

            );
            if ($settings['total'] > $limitto) {
                $pagination = $OUTPUT->render_from_template('block_elearnified_catalogue/catelogpagination', $paginationsettings);
            }
            $this->sendResponse(array("maincontaine" => $maincontaine, "pagination" => $pagination));
        } catch (Exception $e) {
            print_r($e);
            $this->sendError("Operation Failed", "Failed to filter product");
        }
    }
    public function pagination($total, $perpage, $current)
    {
        $allpagination = array();
        $totalpages = floor($total / $perpage);
        for ($i = 0; $i <= $totalpages; $i++) {
            $pagedata = array(
                "display" => $i + 1,
                "value" => $i,
                "active" => ($i == $current ? "active" : '')
            );
            if ($total > $perpage) {
                if ($i < 2 || (($i >= $current - 2) && ($i <= $current + 2)) || $i > ($totalpages - 2)) {
                    array_push($allpagination, $pagedata);
                }
            }
        }
        return $allpagination;
    }
    public function smallpagination($total, $perpage, $current)
    {
        $allpagination = array();
        $totalpages = floor($total / $perpage);
        for ($i = 0; $i <= $totalpages; $i++) {
            $pagedata = array(
                "display" => $i + 1,
                "value" => $i,
                "active" => ($i == $current ? "active" : '')
            );
            if ($total > $perpage) {
                if ($i < 2 || (($i >= $current - 1) && ($i <= $current + 1)) || $i > ($totalpages - 2)) {
                    array_push($allpagination, $pagedata);
                }
            }
        }
        return $allpagination;
    }

    public function openAddPromoCode($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new promocodeModel();
        if ($args->id) {
            $id = $args->id;
            $promocode = $PM->getbyID($id);
        }
        $newpdata = array(
            "id" => ($promocode->id ? $promocode->id : 0),
            "promoid" => ($promocode->promoid ? $promocode->promoid : ""),
            "type" => ($promocode->type ? $promocode->type : ""),
            "discount" => ($promocode->discount ? $promocode->discount : ""),
            "startdate" => ($promocode->startdate ? date("Y-m-d", $promocode->startdate) : ""),
            "enddate" => ($promocode->enddate ? date("Y-m-d", $promocode->enddate) : ""),
            "status" => ($promocode->status ? $promocode->status : "")
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/promocode/add_promocode', $newpdata);
        $this->sendResponse($data);
    }

    public function savePromocode($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PM = new promocodeModel();
        if ($PM->save($args)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function deletePromocode($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PM = new promocodeModel();
        if ($args->id) {
            $id = $args->id;
            if ($PM->delete($id)) {
                $this->sendResponse("Deleted Successfully");
            } else {
                $this->sendError("Operation Failed", "Failed to delete product");
            }
        } else {
            $this->sendError("Operation Failed", "Missing paramters");
        }
    }
    public function addToCart($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        // echo "<pre>";
        // print_r($args);
        // die();
        $CM = new cartModel($args->cartid);
        if ($CM->add($args)) {
            $this->sendResponse(array("message" => "Updated successfully", "cartid" => $CM->cartid));
        } else {
            $this->sendError("Operation Failed", "Failed to add item to cart");
        }
    }
    public function removeFromCart($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $CM = new cartModel($args->cartid);
        if ($CM->remove($args)) {
            $this->sendResponse(array("message" => "Updated successfully", "cartid" => $CM->cartid));
        } else {
            $this->sendError("Operation Failed", "Failed to add item to cart");
        }
    }
    public function loadformregister($args)
    {
        global $CFG, $PAGE, $OUTPUT, $USER;

        require_once($CFG->libdir . '/authlib.php');

        include_once($CFG->dirroot . '/theme/elearnified/ccn/page_handler/ccn_page_handler.php');

        $registerform = "";
        $_ccnlogin = '';





        $authsequence = get_enabled_auth_plugins(true); // Get all auths, in sequence.
        $potentialidps = array();

        foreach ($authsequence as $authname) {
            $authplugin = get_auth_plugin($authname);
            $potentialidps = array_merge($potentialidps, $authplugin->loginpage_idp_list($CFG->wwwroot));
        }




        if (!empty($potentialidps)) {
            $_ccnlogin .= '<div class="row my-3">';
            foreach ($potentialidps as $idp) {
                $_ccnlogin .= '<div class="col-6">';
                $_ccnlogin .= '<a class="btn btn-fb" ';
                $_ccnlogin .= 'href="' . $idp['url']->out() . '" title="' . s($idp['name']) . '">';
                if (!empty($idp['iconurl'])) {
                    $_ccnlogin .= '<img src="' . s($idp['iconurl']) . '" width="24" height="24" class="mr-1"/>';
                }
                $_ccnlogin .= s($idp['name']) . '</a></div>';
            }

            $_ccnlogin .= '</div>';
        }


        if (!isloggedin() or isguestuser()) {
            $registerform = $OUTPUT->render_from_template('local_ecommerce/userregister', array('data' => 'ddddddd', 'authlogin' => $_ccnlogin));
        }

        $this->sendResponse($registerform);
    }



    public function loadCart($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $CM = new cartModel($args->cartid);
        $subtotal = 0;
        $vat = 0;
        $vatpercent = 0;
        $shipping = 0;
        $discount = 0;
        $havediscount = false;
        $havevat = true;
        $displaycartfooter = false;
        $purchasedata="";
        $allpurCourse=array();
        $cartitems = $CM->getallitems();
        foreach ($cartitems as $key => $item) {
            $displaycartfooter = true;
            $subtotal += $item->itemfinalprice;
            if ($item->itemfinalprice > 0) {
                $discount = $item->discount;
            }
            $item->itemfinalprice=number_format($item->itemfinalprice,2,".",",");    
            $item->itemprice=number_format($item->itemprice,2,".",","); 

            if(!empty($item->purchasedata)){
                if($item->purchasedata=="disable"){
                    $purchasedata="disable";
                    array_push($allpurCourse,$item->itemname);
                } 
            }

        }
        $vat = ($subtotal * $vatpercent / 100);
        $shipping = ($subtotal > 0 ? $shipping : 0);
        $total = ($subtotal + $vat + $shipping) - $discount;
        if(empty($purchasedata)){
        $paymenturl=$CFG->wwwroot."/local/ecommerce/payment/";
       }else{
        $paymenturl="javascript:void(0)";
       }
        $newpdata = array(
            "cartsize" => sizeof($cartitems),
            "cartitems" => $cartitems,
            "subtotal" =>  number_format($subtotal, 2, ".", ","),
            "havevat" => number_format($havevat, 2, ".", ","),
            "vatpercent" => number_format($vatpercent,2, ".", ","),
            "vat" => number_format($vat, 2, ".", ","),
            "shipping" => number_format($shipping, 2, ".", ","),
            "discount" => number_format($discount, 2, ".", ","),
            "havediscount" => number_format($havediscount, 2, ".", ","),
            "displaycartfooter" => $displaycartfooter,
            "total" => number_format($total, 2, ".", ","),
            "buttonpurchasedata"=>$purchasedata,
             "purchaseCoursedata"=>implode(",",$allpurCourse),
            "checkouturl" => $CFG->wwwroot . "/local/ecommerce/checkout/",
            "paymenturl" => $paymenturl,
            "backtocart" => $CFG->wwwroot . "/local/ecommerce/",



        );

        $this->newpdata = $newpdata;

        $data = $OUTPUT->render_from_template('block_elearnified_catalogue/allcartitem', $newpdata);
        $cartout = $OUTPUT->render_from_template('block_elearnified_checkout/cartout', $newpdata);
        $cart_summary = $OUTPUT->render_from_template('block_elearnified_cart_summary/cartitem', $newpdata);
        $this->sendResponse(array("itemlist" => $data, "cartout" => $cartout, 'cart_summary' => $cart_summary, "count" => sizeof($cartitems), "cartid" => $CM->cartid));
    }

    public function validate_email($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        require_once($CFG->dirroot . "/login/lib.php");
        require_once($CFG->dirroot . "/local/ecommerce/lib.php");
        $user = $DB->get_record("user", array('email' => $args->email));
        if (!empty($user)) {
            $resetrecord = core_login_generate_password_reset($user);
            local_ecommerce_for_get_confirmation_email($user, $resetrecord);
            //send_password_change_confirmation_email($user, $resetrecord);
            $this->sendResponse(array('status' => '1'));
        } else {
            $this->sendResponse(array('status' => '0'));
        }
        //$data = $OUTPUT->render_from_template('theme_elearnified/elearnified_forgotpassword', $user); 

    }

    public function getCartCount($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $CM = new cartModel($args->cartid);
        $cartitems = $CM->getallitems();
        if (sizeof($cartitems) == 0) {
            $carttotal = "";
        } else {
            $carttotal = sizeof($cartitems);
        }
        $this->sendResponse(array("total" => $carttotal, "cartid" => $CM->cartid));
    }
    public function applyPromoCode($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $CM = new cartModel();
        $this->sendResponse($CM->applyPromoCode($args));
    }
    public function applyPromoCodeonProduct($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $CM = new cartModel();
        $this->sendResponse($CM->applyPromoCodeonProduct($args));
    }
    public function confrmationbox($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $newpdata = array(
            "itemid" => ($args->itemid ? $args->itemid : 0),
            "heading" => ($args->heading ? $args->heading : "Confirm"),
            "subheading" => ($args->subheading ? $args->subheading : ""),
            "description" => ($args->description ? $args->description : ""),
            "action" => ($args->action ? $args->action : ""),
            "data" => json_encode($args)
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/confirmation-box', $newpdata);
        $this->sendResponse($data);
    }
    public function deleteProductType1($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PCM = new categorytypeModel();
        if ($PCM->delete($args->id)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function saveNewProductcategory($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new categorytypeModel();
        if ($PTM->saveProductCategory($args)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function openAddProductType($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PTM = new producttypeModel();
        if ($args->id) {
            $id = $args->id;
            $producttype = $PTM->getbyID($id);
        }

        $newpdata = array(
            "id" => ($producttype->id ? $producttype->id : 0),
            "name" => ($producttype->name ? $producttype->name : ""),
            "description" => ($producttype->description ? $producttype->description : ""),
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/producttype/editproducttype', $newpdata);
        $this->sendResponse($data);
    }
    public function deleteProductType($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new producttypeModel();
        if ($PTM->delete($args->id)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function saveNewProducttype($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new producttypeModel();
        if ($PTM->save($args)) {
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function openAddProductcat($args)
    {
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PTM = new categorytypeModel();
        if ($args->id) {
            $id = $args->id;
            $producttype = $PTM->getbyID($id);
        }

        $newpdata = array(
            "id" => ($producttype->id ? $producttype->id : 0),
            "name" => ($producttype->name ? $producttype->name : ""),
            "description" => ($producttype->description ? $producttype->description : ""),
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/productcategory/editproductcategory', $newpdata);
        $this->sendResponse($data);
    }

    public function confrmationbox1($args)
    {
        global $CFG, $PAGE, $OUTPUT;
        $newpdata = array(
            "itemid" => ($args->itemid ? $args->itemid : 0),
            "heading" => ($args->heading ? $args->heading : "Confirm"),
            "subheading" => ($args->subheading ? $args->subheading : ""),
            "description" => ($args->description ? $args->description : ""),
            "action" => ($args->action ? $args->action : ""),
            "data" => json_encode($args)
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/confirmation-box', $newpdata);
        $this->sendResponse($data);
    }
    public function check_user_login()
    {
        global  $SESSION, $CFG; //$SESSION->wantsurl ///local/ecommerce/checkout/
        $SESSION->wantsurl = $CFG->wwwroot . '/local/ecommerce/checkout/';
        if (isloggedin()) {
            $this->sendResponse("User Already loggedin");
        } else {
            $this->sendError("user not logged in",  $SESSION->wantsurl);
        }
    }
    public function validatetoken($args)
    {
        global $CFG;
        $this->sesskey = sesskey();
        if ($args->sesskey == sesskey()) {
            return true;
        } else {
            $this->sendError("error", "request not authenticated");
            return false;
        }
    }
    public function addItemRating($args)
    {
        $CM = new cartModel($args->cartid);
        if ($CM->addCartItemRating($args)) {
            $this->sendResponse("Rating Added successfully");
        } else {
            $this->sendError("error", "request not authenticated");
        }
    }
}
$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
    if (is_array($args)) {
        $args = (object)$args;
    }
    if ($baseobject->validatetoken($args)) {
        $baseobject->$functionname($args);
    }
}
echo json_encode($baseobject);
