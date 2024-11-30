<?php

namespace local_courses;

error_reporting(E_ALL);
ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use local_courses\extra;
use theme_moove\util\theme_settings;
use local_courses\Category;


use theme_moove\output\core_renderer;

defined('MOODLE_INTERNAL') || die();

/**
 * @course class 
 */
global $OUTPUT,$PAGE;

class Course 
{
    protected $courseid;
    protected $slidercourseimages;
    protected $templateContext;
    protected $extracom;
    protected $themesettings;
    protected $instanceofCategory;
    
    function __construct($courseid)
    {

        $this->courseid = $courseid;
        $this->slidercourseimages = array();
        $this->templateContext = new stdClass;
        $this->extracom = new extra;
        $this->themesettings = new theme_settings;

        echo $this->index();
    }

    public function index(){
        global $OUTPUT;
        
        
        if ($this->courseid) {
            $this->slidercourseimages = $this->sliderImages($this->courseid);
        }else{
            $this->slidercourseimages = array();
        }

        $this->templateContext->pagi = $this->getPagination();
        $this->templateContext->logo = $this->extracom->getLogo();
        //$this->templateContext->user_menu=$this->extracom->user_menu($user = null, $withlinks = null);
        $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
        $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
        $this->templateContext->favicon = $this->extracom->favicon();
        $this->templateContext->sitename = $this->extracom->siteName();
        $this->templateContext->footerContent = $this->extracom->footerContentSocial();
        $this->templateContext->getintouchcontent = $this->extracom->footerContent();
        $this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
        $this->templateContext->showslider = count($this->slidercourseimages) > 0 ? true:false; 
        $this->templateContext->slider = $this->slidercourseimages;
        $this->templateContext->hascategory = count($this->getAllCategory()) > 0 ? true:false;
        $this->templateContext->category = $this->getAllCategory();
        $this->templateContext->hasrelatedCourse = count($this->relatedCourses($this->courseid)) > 0 ? true:false;
        $this->templateContext->hasteachers = count($this->teachersInThisCategory()) > 0 ? true : false;
        $this->templateContext->islogedin_user=islogedin_user();
         // echo "<pre>";
         // print_r($this->templateContext->hasteachers);
         // die();
        $this->templateContext->teachers = array_values($this->teachersInThisCategory());
        // echo "<pre>";
        //  print_r($this->templateContext->teachers);
        //  die();
        $this->templateContext->categorycoursename = "DISCOVER DIFFERENT COURSE CATEGORIES";
        $this->templateContext->categorycoursedescription = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut est nulla, pulvinar dignissim sapien eget, ultrices congue nunc. Vestibulum quam.";

        
                
        if ($this->courseid) {
            $this->templateContext->relatedCourses = $this->relatedCourses($this->courseid);
            $this->templateContext->coursedetails = $this->getCourseDetails();
            $this->templateContext->hasinstructor = (count($this->instuctorsDetails($this->courseid)) > 0 ? true : false);
             // echo "<pre>";
             // print_r($this->templateContext->hasinstructor);
             // die();
            $this->templateContext->instructor = array_values($this->instuctorsDetails($this->courseid));
            $this->templateContext->sectiondetails = $this->courseSectionsDetails();
            $this->templateContext->singlecoursecategorydetails = $this->getSingleCategoryDetails($this->courseid);
            


            if (count($this->courseSectionsDetails()) > 0) {

                $this->templateContext->hassections = TRUE;
            }else{
                $this->templateContext->hassections = FALSE;    
            }
           
            return $OUTPUT->render_from_template('local_courses/cataloguecoursedetails', $this->templateContext);
        }else{
             
                if (isset($_GET['coursesort']) AND !empty($_GET['coursesort'])) {
                  $this->templateContext->coursesort = $_GET['coursesort'];
                    
                 
                }
                if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid'])) {
                  $this->templateContext->currentCategory = $_GET['searchbycatid'];
                 
                }
            $this->templateContext->pagi = $this->getPagination();
            $this->templateContext->hascourses = count($this->getcourses()) > 0 ? true:false;
            $this->templateContext->courses = $this->getcourses();

            
            return $OUTPUT->render_from_template('local_courses/courses', $this->templateContext);
        }

    }
   
    private function getCourseDetails(){
        global $DB,$CFG;
        if (!is_null($this->courseid)) {
            
            $crs = $DB->get_record_sql('SELECT * FROM {course} WHERE category != 0 AND visible = 1 AND id = ?', [$this->courseid]);
            $crs->enrolled=$CFG->wwwroot.'/enrol/index.php?id='.$crs->id;

            $enrol_details = $DB->get_records_sql("SELECT enrol,cost,currency FROM {enrol} WHERE courseid=$crs->id AND status=0");
            // echo "<pre>";
            //  print_r($enrol_details);
            //  die();
                $enrol_price = 'Free';
                foreach ($enrol_details as $key => $value) {
                    if (!empty($value->cost)) {
                        $enrol_price = $value->cost.' '.$value->currency;
                    }
                }

                $crs->course_price = $enrol_price;

            return $crs;
        }


    }

    private function instuctorsDetails($courseid){
        global $DB;
        $instructors = array();
        $show_users = [3, 4];
        if (!is_null($courseid)) {
            $coursecontext = context_course::instance($courseid);

            if (!empty($coursecontext)) { 
                $sql =  "SELECT * FROM {role_assignments} WHERE contextid = $coursecontext->id AND roleid IN (".implode(',',$show_users).")";
                $role_assignments =  $DB->get_records_sql($sql, array());
                
                if (!empty($role_assignments)) {
                    foreach ($role_assignments as  $teachers) {
                        if ($DB->record_exists('user', array('id' => $teachers->userid, 'deleted' => 0, 'suspended' => 0, 'confirmed' => 1))) {
                            array_push($instructors, $this->local_user_details($teachers->userid));
                        }
                    }  
                }
            }
        }
        return $instructors;
    }




    public  function local_user_details($userid = null, $imgsize = 200) {
        global $USER, $PAGE, $DB,$CFG;
        $userobj = optional_param('id', $userid, PARAM_INT);
        $user = $DB->get_record('user', ['id' => $userobj], '*', MUST_EXIST);
       // $CFG->wwwroot.'/local/courses/tutor.php?tutorid='.$teacher->id;
        $user->user_url = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$user->id;
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        $user->userimage = $userimg->get_url($PAGE);
        return $user;
    }

    public function course_image($courseid) {
        global $DB, $CFG;
        $courserecord = $DB->get_record('course', array('id' => $courseid));
        $course = new core_course_list_element($courserecord);
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
            if ($isimage) {
                return $url;
            } else {
                return $CFG->wwwroot.'/local/catalogue/pix/noimage.png';
            }
        }
    }

    public  function courseSectionsDetails(){
        $courseid = $this->courseid;
        global $DB,$CFG, $USER,$PAGE;
        require_once($CFG->dirroot.'/course/lib.php');
        require_once($CFG->libdir.'/completionlib.php');
        if (is_null($courseid)) {
            return FALSE;
        }
        $userid = null;
        $ttlArt = 0;
        $nofitem=0;
        if (isloggedin()) {
            $userid = $USER->id;
        }
        $array_of_sections = array();

        $coursesection = $DB->get_records_sql("SELECT * FROM {course_sections} WHERE course=$courseid AND visible = 1 AND sequence != '' ");
        foreach($coursesection as $csvalue){
            if($csvalue->name==""){
                $sectionname="Tile ".$nofitem;
            }else{
                $sectionname=$csvalue->name;
            }
            $nofitem++; 
            $count = 0; 
            $cnt = 0;
            $titem=$csvalue->sequence;
            $sequenceid=explode(',',$titem);
            $topic_name = $sectionname; 

            $test = array();
            foreach($sequenceid as $sequenceval){ 
                if($sequenceval){ 
                    $c_modulesinfo = $DB->get_record_sql("SELECT * FROM {course_modules} WHERE course=$courseid and deletioninprogress=0  and id=$sequenceval" );
                    if (@$c_modulesinfo->module) {
                        $moduleinfo = $DB->get_record_sql("SELECT * FROM {modules} WHERE id=$c_modulesinfo->module");

                        $modulename=$moduleinfo->name;
                        $s= "select * from  mdl_$modulename WHERE id=$c_modulesinfo->instance";
                        $tableinfo =$s;
                        $courseinfossss = $DB->get_records_sql($tableinfo);
                        if ($courseinfossss) {
                            foreach($courseinfossss as $tavleRow){
                                $count++;
                                $ttlArt++;
                                if($csvalue->section == 0){
                                    $topic_name = "course details";
                                }else{
                                    if ($topic_name == 'Tile 0') {
                                        $topic_name = 'Tile 1';
                                    }
                                } 
                                $tname=$tavleRow->name;
                                $completionstate = null;
                                if (!is_null($userid)) {
                                $curs_mdl_cmpinfo = $DB->get_records_sql("SELECT completionstate,viewed,timemodified FROM {course_modules_completion} WHERE coursemoduleid =$c_modulesinfo->id and userid=$userid and completionstate=1"); 
                
                                    if ($curs_mdl_cmpinfo) {
                                        $completionstate = array();
                                        array_push($completionstate, array('completionstate'=>$curs_mdl_cmpinfo[1]->completionstate,
                                                                           'viewed'=>$curs_mdl_cmpinfo[1]->viewed,
                                                                           'timemodified'=>date("l, d M Y, h:i A",$curs_mdl_cmpinfo[1]->timemodified)));
                                        $cnt = count((array)$curs_mdl_cmpinfo);
                                    }  
                                }
                                $module_url = $CFG->wwwroot.'/course/view.php?id='.$courseid;
                                $modulename = $modulename;
                                $module_id = $c_modulesinfo->id;
                                $modulename_Name = $tname;
                                $sectiondeatils = array('modulename'=>$modulename,
                                                        'module_id'=>$module_id,
                                                        'modulename_Name'=>$tname,
                                                        'module_url'=>$module_url,
                                                        'completionstate'=>$completionstate);
                                array_push($test, $sectiondeatils);
                            }

                        }
                    }
                }
            }
            if ($cnt!=0 and $count!=0) {
                $report = $cnt/$count*100;
            }else{
                $report = 0;
            }
            $cnt = 0;
            $coursesectionobj = new stdClass();
            $coursesectionobj->sectionid = $csvalue->id;
            $coursesectionobj->section_sequence = $csvalue->section;
            $coursesectionobj->sectionname = $topic_name;
            $coursesectionobj->progress = number_format($report);
            $coursesectionobj->summary = $csvalue->summary;
            $coursesectionobj->sectiondata = $test;
            array_push($array_of_sections, $coursesectionobj);
        }
        return $array_of_sections;
    }

    private function getAllCategory(){
        global $DB;
        $coursecategoriesss = array();
       $coursecategories = $DB->get_records("course_categories", array("parent" => 0, "visible"=>1));
       if (!empty($coursecategories)) {
           foreach ($coursecategories as $value) {
               $value->image = $this->getCategoryImage($value->id);
               if ($value->description) {
               $value->description = $this->extracom->limittext($value->description, 20, $value->id);
               }
               $value->url = '/local/courses/index.php?categoryid='.$value->id;
               $value->cat_name = $value->name;
               $value->cat_id=$value->id;
               array_push($coursecategoriesss, $value);
           }
       }
       // echo "<pre>";print_r($coursecategoriesss);die;
       return $coursecategoriesss;
    }

     private function getSingleCategoryDetails($courseid){
        global $DB;
        $singlecoursecategory = array();
        
        $getcoursecat = $DB->get_record("course", array("visible"=>1,'id'=>$courseid));


       if (!empty($getcoursecat)) {
            $categoryid = $getcoursecat->category;
            //echo $categoryid;
            //echo '<br>';
           
           $getcoursecat1 = $DB->get_record("course_categories",array("visible"=>1,'id'=>$categoryid));
           $getparentid=$getcoursecat1->parent;

            if($getparentid!=0){
               
                 $getcoursecat11 = $DB->get_records("course_categories",array("visible"=>1,'id'=>$getparentid));
                   foreach ($getcoursecat11 as $value) {
                   $value->image = $this->getCategoryImage($value->id);
                   if ($value->description) {
                   $value->description = $this->extracom->limittext($value->description, 20, $value->id);
                   }
                   $value->url = '/local/courses/index.php?categoryid='.$value->id;
                   $value->cat_name = $value->name;
                   $value->cat_id=$value->id;
                   array_push($singlecoursecategory, $value);
                }
           }

           else{ $getcoursecat11 = $DB->get_records("course_categories",array("visible"=>1,'id'=>$categoryid));
                   foreach ($getcoursecat11 as $value) {
                   $value->image = $this->getCategoryImage($value->id);
                   if ($value->description) {
                   $value->description = $this->extracom->limittext($value->description, 20, $value->id);
                   }
                   $value->url = '/local/courses/index.php?categoryid='.$value->id;
                   $value->cat_name = $value->name;
                   $value->cat_id=$value->id;
                   array_push($singlecoursecategory, $value);
                } 
            }

         }

         //echo "<pre>";print_r($singlecoursecategory);die;
       return $singlecoursecategory;
    }




    private function relatedCourses($courseid){
        global $DB;
        $courseReturn = array();
        $value = $DB->get_record("course", array("visible"=>1,'id'=>$courseid));
        if ($value) {
            if (!is_null($value->category)) {
                $categoryid = $value->category;
                $allcourses = $DB->get_records_sql("SELECT * FROM {course} WHERE category = $categoryid AND visible = 1 ORDER BY id desc");        
                $courselevel = '';
                foreach ($allcourses as $course) {
                    $course->summary = $this->extracom->limittext($course->summary, 15, $course->id);
                    $course->courseimage = $this->course_image($course->id);
                   
                    // $course->catagoryname = $this->extracom->catagoryname($course->category);
                    $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
                    $courseReturn[] = $course;
                }
            }
        }
       return $courseReturn;
    }

    private function getCategoryImage($categoryid = null){
        global $CFG;
        if (!is_null($categoryid)) {
            if ($this->categoryimages($categoryid)) {
                return $this->categoryimages($categoryid);
            }else{
                return $CFG->wwwroot.'/local/courses/pix/noimg.jpg';
            }
        }
    }
    private function teachersInThisCategory(){
        global $DB, $CFG,$PAGE;
        $teacherArray = array();
        if (true) {
            $allcourses = $DB->get_records_sql("SELECT * FROM {course} WHERE visible = 1");        
            foreach ($allcourses as $course) {

                $context = context_course::instance($course->id); 
                // echo "<pre>";
                // print_r($context);
                // die();
                $students = get_role_users(5 , $context);

                $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
                // $teachers = get_role_users($role->id, $context);
                $teachers = $DB->get_records_sql("SELECT  u.*  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".$course->id.")",array(7));
                foreach ($teachers as $teacher) {
                    $profileurl = $CFG->wwwroot."/theme/image.php/".$PAGE->theme->name."/core/1588856393/u/f1";
             
                    $student_course_arry = enrol_get_users_courses($teacher->id, true, Null, 'visible DESC,sortorder ASC');

                    $courseEnrollArray = array();
                    $totalCourses = count($student_course_arry);

                    // echo "<pre>";
                    // print_r($student_course_arry);
                    // echo "test";
                    // die();
                    foreach ($student_course_arry as $value) {
                         $courseEnrollArray[] = $value;
                    }

                    $user = $DB->get_record('user', array('id' => $teacher->id));
                    // $user_piture = $DB->get_record('files', array('id' => $user->picture));
                    // if ($user_piture) {
                        $profileurl = $this->extracom->get_user_picture($teacher->id, 200);
                    // } 
                    $teacher->user_url = $CFG->wwwroot.'/user/profile.php?id='.$teacher->id;
                    
                    $teacher->tutor_url = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$teacher->id;
                    $teacher->description = $this->extracom->limittext($user->description, 20, 2);
                    $teacher->profileurl = $profileurl;
                    $teacher->totalCourses = $totalCourses;
                    if (!array_key_exists($teacher->id, $teacherArray)) {
                        $teacherArray[$teacher->id] = $teacher;
                    }
                }
            }
        }
            return $teacherArray;
    }

    protected function getcourses($param=null){
        global $DB, $CFG;
        $cat_id='';
        $courses = array();
         $addquery = '';


        if (!is_null($param)) {
            $categoryid = $param['categoryid'];
            if (self::get_check_coursecatageory($categoryid)) {
                $allcourses = $DB->get_records_sql('SELECT * FROM {course} WHERE category = ? AND visible = 1', [$categoryid]);
            }
          else{

            if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid']) && $_GET['searchbycatid']!='all') {
              $getallcatid=array();
                    $getallid=$DB->get_records("course_categories",array('parent'=>$_GET['searchbycatid']));
                    $getallcatid[]=$_GET['searchbycatid'];
                    foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                    }
                    $getids=implode(',', $getallcatid);
                    //print_r($getallcatid);
                    $addquery .= 'AND c.category in('.$getids.')';
                  /*$addquery1 .= 'AND c.category='.$_GET['searchbycatid'];*/
            }
            if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'desc') {
              $addquery .= ' ORDER BY c.id '.$_GET['coursesort'];
            }
            if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'popular') {
                $allcourses = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery GROUP BY c.id, c.fullname ORDER BY c.id DESC");
             /* $allcourses = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments
             FROM {course} c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid) ue ON ue.courseid = c.id where c.category!=0 and c.visible=1 $addquery GROUP BY c.id, c.fullname ORDER BY enrolments DESC, c.fullname");*/
            }else{

              $allcourses = $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery ");
             

            }           
          }
        }
        else{                          

                if (isset($_GET['searchbycatid']) && !empty($_GET['searchbycatid']) && $_GET['searchbycatid']!='all') {
                  $getallcatid=array();
                    $getallid=$DB->get_records("course_categories",array('parent'=>$_GET['searchbycatid']));
                    $getallcatid[]=$_GET['searchbycatid'];
                    foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                    }
                    $getids=implode(',', $getallcatid);
                    //print_r($getallcatid);
                    $addquery .= 'AND c.category in('.$getids.')';
                  /*$addquery1 .= 'AND c.category='.$_GET['searchbycatid'];*/
                  /*print_r($_GET['searchbycatid']);
                  die();*/
                }
                if (isset($_GET['coursesort']) && !empty($_GET['coursesort']) && $_GET['coursesort'] == 'desc') {
                   
                  $addquery .= ' ORDER BY c.id '.$_GET['coursesort'];

                  /*print_r($_GET['coursesort']);
                  die();*/
                }
               
                if (isset($_GET['coursesort']) && !empty($_GET['coursesort']) && $_GET['coursesort'] == 'popular') {
                    $allcourses = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery GROUP BY c.id, c.fullname ORDER BY c.id DESC");
                 /* $allcourses = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments
                 FROM {course} c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid) ue ON ue.courseid = c.id where c.category!=0 and c.visible=1 $addquery GROUP BY c.id, c.fullname ORDER BY enrolments DESC, c.fullname");*/
                }else{


                  $allcourses = $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery ");
                }
            }



        if(!empty($allcourses))
            {   

               
                $pag='';
                $total_records=count($allcourses);
                $one_page=12;
                $lastPage= ceil ($total_records / $one_page);

                $currentPage = '';
                if(isset($_GET['page']))
                {
                 $currentPage = $_GET['page'];
                }
                else
                {
                 $currentPage = 1;
                }
                $startFrom = ($currentPage * $one_page) - $one_page;
                //$start_from = ($currentPage-1)*$page;
                $firstPage=1;
                $nextPage = $currentPage + 1;
                $previousPage = $currentPage - 1;
                $addquery1='';
               /* $result= $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 order by id DESC LIMIT $start_from,$one_page $addquery ");*/

                if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid']) && $_GET['searchbycatid']!='all') {
                 $getallcatid=array();
                    $getallid=$DB->get_records("course_categories",array('parent'=>$_GET['searchbycatid']));
                    $getallcatid[]=$_GET['searchbycatid'];
                    foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                    }
                    $getids=implode(',', $getallcatid);
                    //print_r($getallcatid);
                    $addquery1 .= 'AND c.category in('.$getids.')';
                  /*$addquery1 .= 'AND c.category='.$_GET['searchbycatid'];*/
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'desc') {
                  $addquery1 .= ' ORDER BY c.id '.$_GET['coursesort'];
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'popular') {
                   
                 /* $allcourses1 = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments
                 FROM {course} c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid) ue ON ue.courseid = c.id where c.category!=0 and c.visible=1 order by c.id DESC LIMIT $startFrom,$one_page $addquery1 GROUP BY c.id, c.fullname ORDER BY enrolments DESC, c.fullname");*/

                 $allcourses1 = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery1 GROUP BY c.id, c.fullname ORDER BY c.id DESC LIMIT $startFrom,$one_page");
                }else{
                   /* echo 'hello LDS';
                    echo $query="SELECT * FROM {course} as c where c.category!=0 and c.visible=1 ORDER BY c.id desc";
                    die();*/
                   
               $allcourses1 = $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery1  LIMIT $startFrom,$one_page");
                }

                    foreach ($allcourses1 as $course) {
                    //$course->pag=$pag;
                    $course->summary = $this->extracom->limittext($course->summary, 30,0 );
                   $course->courseimage = $this->course_image($course->id);

                    $course->cat_id = $course->id;
                   //$course->catagoryname = $this->catagoryname($course->category);
                   $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
                   $course->hascourseteacher = count($this->instuctorsDetails($course->id)) > 0 ? true:false;
                   $course->courseteacher = $this->instuctorsDetails($course->id);

                   array_push($courses, $course);
                }
             }
            else{ 
               
                $nodata = new stdClass;
                $nodata->courseimage='/local/courses/images/nofound.png';
                array_push($courses, $nodata);
            }
        
        return $courses;
    
    }

function getPagination(){
     global $DB, $CFG;
     $addquery1='';
     if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid']) && $_GET['searchbycatid']!='all') {
                  $getallcatid=array();
                    $getallid=$DB->get_records("course_categories",array('parent'=>$_GET['searchbycatid']));
                    $getallcatid[]=$_GET['searchbycatid'];
                    foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                    }
                    $getids=implode(',', $getallcatid);
                    //print_r($getallcatid);
                    $addquery1 .= 'AND c.category in ('.$getids.')';
                  /*$addquery1 .= 'AND c.category='.$_GET['searchbycatid'];*/
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'desc') {
                  $addquery1 .= ' ORDER BY c.id '.$_GET['coursesort'];
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'popular') {
                   
                   
                   $allcourses1 = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery1 GROUP BY c.id, c.fullname ORDER BY c.id DESC");
                 /* $allcourses1 = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments
                 FROM {course} c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid) ue ON ue.courseid = c.id where c.category!=0 and c.visible=1 $addquery1 GROUP BY c.id, c.fullname ORDER BY enrolments DESC, c.fullname");*/
                }else{

                   /* echo $query="SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery1";
                    die();*/
                    
               $allcourses1 = $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery1");
                }
   
     if(!empty($allcourses1))
            {
                $pag='';
                $total_records=count($allcourses1);
                $one_page=12;
                $lastPage= ceil ($total_records / $one_page);

                $currentPage = '';
                if(isset($_GET['page']))
                {
                 $currentPage = $_GET['page'];
                }
                else
                {
                 $currentPage = 1;
                }
                $startFrom = ($currentPage * $one_page) - $one_page;
                //$start_from = ($currentPage-1)*$page;
                $firstPage=1;
                $nextPage = $currentPage + 1;
                $previousPage = $currentPage - 1;
                $addquery1='';
               /* $result= $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 order by id DESC LIMIT $start_from,$one_page $addquery ");*/

                if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid']) && $_GET['searchbycatid']!='all') {
                   $getallcatid=array();
                    $getallid=$DB->get_records("course_categories",array('parent'=>$_GET['searchbycatid']));
                    $getallcatid[]=$_GET['searchbycatid'];
                    foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                    }
                    $getids=implode(',', $getallcatid);
                    //print_r($getallcatid);
                    $addquery1 .= 'AND c.category in ('.$getids.')';
                  /*$addquery1 .= 'AND c.category='.$_GET['searchbycatid'];*/
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'desc') {
                  $addquery1 .= ' ORDER BY c.id '.$_GET['coursesort'];
                }
                if (isset($_GET['coursesort']) AND $_GET['coursesort'] == 'popular') {

                   /* echo 'SELECT c.*, c.fullname, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue
                   JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id  GROUP BY c.id, c.fullname ORDER BY enrolments DESC, c.fullname $addquery1 LIMIT $startFrom,$one_page';
                 die();*/
                   /*echo $query="SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery1 GROUP BY c.id, c.fullname ORDER BY 3 DESC LIMIT $startFrom,$one_page"; 

                 die();*/

                   
                  $allcourses2 = $DB->get_records_sql("SELECT c.*, COUNT(*) AS enrolments FROM mdl_course c JOIN (SELECT DISTINCT e.courseid, ue.id AS userid FROM mdl_user_enrolments ue JOIN mdl_enrol e ON e.id = ue.enrolid) ue ON ue.courseid = c.id $addquery1 GROUP BY c.id, c.fullname ORDER BY c.id DESC LIMIT $startFrom,$one_page");
                }else{
                   /* echo 'hello';
                    echo $query="SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery1";
                   die();*/

               $allcourses2 = $DB->get_records_sql("SELECT * FROM {course} as c where c.category!=0 and c.visible=1 $addquery1 LIMIT $startFrom,$one_page");
                }


                if($allcourses2){
                $pag='<nav aria-label="Page navigation">';
                $pag.= '<ul class="pagination">';

                   if($currentPage != $firstPage){
                   $pag.='<li class="page-item">
                      <a class="page-link" href="?page='.$firstPage.'" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">First</span>           
                      </a>
                    </li>';
                }
        
                if($currentPage >= 2) { 
                    $pag.='<li class="page-item"><a class="page-link" href="?page='.$previousPage.'">'.$previousPage.'</a></li>';
                 } 
                $pag.='<li class="page-item active"><a class="page-link" href="?page='.$currentPage.'">'.$currentPage.'</a></li>';
                 if($currentPage != $lastPage) {
                $pag.=' <li class="page-item"><a class="page-link" href="?page='.$nextPage.'"> '.$nextPage.'</a></li>';
                   $pag.='<li class="page-item">
                      <a class="page-link" href="?page='.$lastPage.'" aria-label="Next">
                        <span aria-hidden="true">Last</span>
                      </a>
                    </li>';
               }
             $pag.='</ul>';
            $pag.='</nav>';

        }

        else{

            $pag='';
        }

    return $pag;
}

}


     function sliderImages($courseid){
        global $DB, $CFG;
        $coursesliders = array();
        $courserecord = $DB->get_record('course', array('id' => $courseid));

        $course = new core_course_list_element($courserecord);
        foreach ($course->get_course_overviewfiles() as $file){
            
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);

             $enrol_details = $DB->get_records_sql("SELECT enrol,cost,currency FROM {enrol} WHERE courseid=$courseid AND status=0");
                $enrol_price = 'Free';
                foreach ($enrol_details as $key => $value) {
                    if (!empty($value->cost)) {
                        $enrol_price = $value->cost.' '.$value->currency;
                    }
                }

            
            if ($isimage) {
                $newslider = new stdClass;
                $newslider->course_price = $enrol_price;
                $newslider->enrolled=$CFG->wwwroot.'/enrol/index.php?id='.$courseid;
                $newslider->key = $file->get_id();
                $newslider->image = $url;
                $newslider->title = $courserecord->fullname;
                $newslider->summary = $courserecord->summary;
                array_push($coursesliders, $newslider);
            }
        }
        return $coursesliders;
    }

        public function categoryimages($categoryid){
        global $DB, $CFG;
        // $slidersimages = array();

        if (!is_null($categoryid)) {
           $catslders = $DB->get_records_sql("SELECT * FROM {files} WHERE component = 'customcategory' AND filearea = 'categoryimage' AND itemid = ".$categoryid);
                
                if (!empty($catslders)) {
                    foreach ($catslders as $key => $value) {
                         if ($value->filename !== ".") {
                             return $CFG->wwwroot.'/local/courses/file.php/'.$value->pathnamehash.'/0/'.$value->filename;
                        }   
                    }
                }
        }
        // return $slidersimages;
    } 

}
?>


 <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60d32da77f4b000ac0392300/1f8sekm9q';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<style type="text/css">
a:focus, a:hover {
    text-decoration: none!important;
}
    @media only screen and (max-width: 600px) {
        section.sec_tab {
    padding: 0px!important;
}
.heading.text-center h2 {
    width: 100%;
    margin: 10px;
    font-size: 25px;
}
li.nav-item {
    width: 100%;
    margin-top: 2%;
}
a.t_hree_btn {
    padding: 8px 5px 8px 5px!important;
    background-color: #2a2356;
    border-radius: 3px;
    border: 1px solid #2a2356!important;
    font-weight: bold;
    color: #fff!important;
    margin-right: 28px;
    margin-top: 2%;
    display: inline-block;
}
    
</style>