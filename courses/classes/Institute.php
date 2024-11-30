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

defined('MOODLE_INTERNAL') || die();

/**
 * @course class 
 */
class Institute  
{ 

  
  protected $tutorid;
  protected $blogid;
  protected $slidercourseimages;
  protected $templateContext;
  protected $extracom;
  protected $themesettings;
  protected $instanceofCategory;
  
  function __construct($blogid)
  {  

    $this->blogid = $blogid;
    $this->slidercourseimages = array();
    $this->templateContext = new stdClass;
    $this->extracom = new extra;
    $this->themesettings = new theme_settings;
    echo $this->index();
  }

  public function index(){
    global $OUTPUT;
    
    $this->templateContext->noofcourses = $this->gettotalCourse(@$tutorid);
    $this->templateContext->logo = $this->extracom->getLogo();
    $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
    $this->templateContext->favicon = $this->extracom->favicon();
    $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
    $this->templateContext->sitename = $this->extracom->siteName();
    $this->templateContext->footerContent = $this->extracom->footerContentSocial();
    $this->templateContext->getintouchcontent = $this->extracom->footerContent();
    $this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
    $this->templateContext->hasblogs = count($this->get_blogs()) > 0 ? true : false;
    $this->templateContext->blogs = $this->get_blogs();

    $this->templateContext->institute = $this->getinstitute();
    $this->templateContext->institute;
    $this->templateContext->islogedin_user=islogedin_user();
 
    $this->templateContext->total_course = is_array($this->templateContext->noofcourses) && sizeof($this->templateContext->noofcourses) > 0;

     // echo "<pre>";
     //  print_r($this->templateContext->total_course);
     //  die();
    $this->templateContext->tutors = is_array($this->templateContext->institute->allteacher)?$this->templateContext->institute->allteacher:array();

    $this->templateContext->havetutors = is_array($this->templateContext->tutors) && sizeof($this->templateContext->tutors) > 0;

   // $this->templateContext->teacherdetails = $this->teacherdetails();
    return $OUTPUT->render_from_template('local_courses/institute', $this->templateContext);
  }


  private function getinstitute(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
        $blog = new stdClass();

        $addquery = '';
        // $blog->picture_url = self::getUserProfile($user->picture,$user->id);
        // $blog->userid = $user->id;
        $picture_url = "";
            
        $userid=$_GET['instituteid'];
       /* echo $userid;
        die;*/
        

        $imageofinstitution="SELECT institution_img as data FROM {tutor_document} ud WHERE ud.user_id = ".$userid;
        $imageofinstitution1 = $DB->get_record_sql($imageofinstitution);

        if(isset($imageofinstitution1->data)){
            $iuiur = $CFG->wwwroot.'/theme/moove/'.$imageofinstitution1->data;
            $blog->imageofinstitution = $iuiur;
            $user = $DB->get_record('user', array('id' => $userid));
            // $blog->author_name = $user->firstname.' '.$user->lastname;
            $blog->contact_number = $user->phone1;
            $blog->email_id = $user->email;
            $blog->picture_url = self::getUserProfile($user->picture,$user->id);
            $blog->userid = $userid;
            $Nameofinstitution="SELECT data FROM {user_info_data} uid INNER JOIN {user_info_field} uif ON uid.fieldid = uif.id WHERE uif.id = 9 AND uid.userid = ".$userid;
            $Nameofinstitution1 = $DB->get_record_sql($Nameofinstitution);

            if(isset($Nameofinstitution1->data)){
                $blog->Nameofinstitution = $Nameofinstitution1->data;
                // $blog->aaa="DKBOSS";
            }
            
            $disofinstitution="SELECT data FROM {user_info_data} uid INNER JOIN {user_info_field} uif ON uid.fieldid = uif.id WHERE uif.id = 22 AND uid.userid = ".$userid;
            $disofinstitution1 = $DB->get_record_sql($disofinstitution);

            if(isset($disofinstitution1->data)){
                $blog->disofinstitution = $disofinstitution1->data;
            }

            $bloginfo = $DB->get_record_sql("SELECT count(*) as total_blog FROM {blog_list} WHERE status=1 AND userid=".$userid);
            if(isset($bloginfo->total_blog)){
                $blog->total_blog = $bloginfo->total_blog;
            }

            $tcourseid = $DB->get_fieldset_sql("SELECT DISTINCT c.id  FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid =".$blog->userid);
            if($_GET['instituteid']==$blog->userid)
             $allcourseid = $DB->get_fieldset_sql("SELECT DISTINCT c.id  FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid =".$blog->userid);
     }
        
                 $tstudent=0;
                 $tteacher=0;
                 $total_course=0;
            $blog->allteacher = array();   
if(!empty($tcourseid)){
   /* $tstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  and  ra.roleid=5 INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid WHERE c.id in(".implode(',',$tcourseid).")");*/
   /* $tteacher = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  and  ra.roleid!=5 INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid WHERE c.id in(".implode(',',$tcourseid).")");*/
    //$tteacher = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
     $tstudent = $DB->get_field_sql("SELECT DISTINCT count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
      
            if($_GET['instituteid']==$blog->userid){
        $allteacher = $DB->get_records_sql("select  u.*  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));

            }
            $tteacher=count($allteacher);
          /*  echo "<pre>";
                echo count($allteacher)."<br>";
                echo $tteacher. "<br>";
                print_r($allteacher);

            echo "</pre>";*/
            /*echo "<pre>";
            print_r($allteacher);
            echo "</pre>";*/
            foreach ($allteacher as $key => $teacher) {
                $profileurl = $CFG->wwwroot."/theme/image.php/".$PAGE->theme->name."/core/1588856393/u/f1";
                $user_piture = $DB->get_record('files', array('id' => $teacher->picture));
                if ($user_piture) {
                    $profileurl = $this->extracom->get_user_picture($teacher->id, 200);
                } 
                $teacher->user_url = $CFG->wwwroot.'/user/profile.php?id='.$teacher->id;
                $teacher->tutor_url = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$teacher->id;
                $teacher->profileurl = $profileurl;
                array_push($blog->allteacher, $teacher);
            }
            




            // $studentinfo = $DB->get_record_sql("SELECT count(u.id)as total_student FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND u.id=".$userid);

            // if(isset($studentinfo->total_student)){
            //     $blog->total_student = $studentinfo->total_student;
            // }

            // $tutorinfo = $DB->get_record_sql("SELECT count(u.id)as total_tutor FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 3 AND u.id=".$blog->userid);

            //     if(isset($tutorinfo->total_tutor)){
            //         $blog->total_tutor = $tutorinfo->total_tutor;
            //     }
                        
            $tcourse = $DB->get_record_sql("SELECT count(c.id)total_course FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                        WHERE ue.userid =".$userid);
            if(isset($tcourse->total_course)){
                $total_course = $tcourse->total_course;
            }
        }
        $blog->total_student = $tstudent;
        $blog->total_tutor = $tteacher;
        $blog->total_course = $total_course;
        
        return $blog;
    }
    /*private function get_blogs(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
            $blogs = $DB->get_records_sql("SELECT * FROM {post}");        
            if (!empty($blogs)) {
                foreach ($blogs as $blog) {
                    $picture_url = "";
                    $user = $DB->get_record('user', array('id' => $blog->userid));
                    $picture = $DB->get_record_sql("SELECT * FROM {files} WHERE itemid=$blog->id and component='blog' and filename!='.'");
                    if ($picture) {
                        $picture_url = $this->extracom->get_user_picture($user->id, 200);
                    }else{
                         Load defaults images.
                        $picture_url = $CFG->wwwroot.'/local/courses/pix/default-blog.png';
                    }
                    $blog->picture_url = $picture_url;
                    $blog->userid = $user->id;
                    $blog->author_name = $user->firstname.' '.$user->lastname;
                    $blog->summary = $this->extracom->limittext($blog->summary, 20, $blog->id);
                    $blogArray[] = $blog;
                }
            }

            return $blogArray;
    }*/

    private function get_blogs(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
        $addquery = '';
        $blogs = '';
        if (isset($_GET['instituteid']) AND !empty($_GET['instituteid'])) {
            $blog_category = $_GET['instituteid'];

            if ($blog_category) {
                // $addquery = ' AND categoryid='.$blog_category->id;
             
                $blogs = $DB->get_records_sql("SELECT * FROM {blog_list} WHERE status=1 AND userid=$blog_category");
            }
        }

        

        if (!empty($blogs)) {
          foreach ($blogs as $blog) {
            $picture_url = "";
            $user = $DB->get_record('user', array('id' => $blog->userid));
            //$category=$DB->get_record('blog_category',array('id'=>$blog->categoryid));
            $category = $DB->get_record_sql("SELECT * FROM {blog_category} WHERE id=$blog->categoryid");

            @$blog->categoryname = $category->categoryname;
            $blog->blogdetails = $CFG->wwwroot.'/local/blogs/blog.php?blogid='.$blog->id;
            $blog->blog_image = self::getBlogImage($blog->blogimage);
            $blog->picture_url = self::getUserProfile($user->picture,$user->id);
            $blog->userid = $user->id;
            $blog->author_name = $user->firstname.' '.$user->lastname;
            $blog->user_url = $CFG->wwwroot.'/user/profile.php?id='.$user->id;
            $blog->summary = $this->extracom->limittext($blog->description, 15, $blog->id);
            $blog->date = date('m/d/Y', $blog->timecreated);
            $blogArray[] = $blog;
          }
        }
        //echo "<pre>";print_r($blogArray);die;
        return $blogArray;
    }

    public function getBlogImage($blogid){
    global $DB, $CFG, $USER;
    $picture = $DB->get_record_sql("SELECT * FROM {files} WHERE itemid=$blogid and component='user' and filename!='.'");
    if ($picture) {
      $iuiur = $CFG->wwwroot.'/local/courses/file.php/'.$picture->pathnamehash.'/0/'.$picture->filename;
      return $picture_url = $iuiur;
    }else{
      return $picture_url = $CFG->wwwroot.'/local/courses/pix/default-blog.png';
    }
  }

  public function getUserProfile($picture,$userid){
    global $DB, $CFG, $USER,$PAGE;
    $profileurl = $CFG->wwwroot."/local/courses/images/head.png";
    $user_piture = $DB->get_record('files', array('id' => $picture));
    if ($user_piture) {
      $profileurl = $this->extracom->get_user_picture($userid, 200);
    } 
    return $profileurl;
  } 



  public function gettotalCourseandstudents($tutorid){
        global $DB, $CFG,$PAGE;
        $t_course='';
        $totals=array();

        if(isset($_GET['tutorid']) && $_GET['tutorid']!=''){
          $tutor_id=$_GET['tutorid'];
        
        }

        if(!empty($tutor_id)){
            $user = $DB->get_record('user', array('id' => $tutor_id));

         $course1="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $tutor_id";
       $tcourse1 = $DB->get_records_sql($course1);
       $getallcourseid=array(0);
       foreach ($tcourse1 as $key) {
           $getallcourseid[]=$key->id;
       }
      $imd=implode(',', $getallcourseid);
       // echo "<pre>";
       // print_r($imd);
       // die();

     /* echo $student="SELECT u.id,u.username,u.firstname,u.lastname,u.email FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND ct.instanceid IN ($imd)";*/
       $student="SELECT count(u.id)as total_student FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND u.id=$tutor_id and ct.instanceid IN ($imd)";
       $studentinfo = $DB->get_record_sql($student);
       $t_student=$studentinfo->total_student;
       
     //echo count(enrol_get_users_courses($tutor_id));
      

        $course="SELECT c.*,count(c.id)total_course FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $tutor_id";
                
        $tcourse = $DB->get_records_sql($course);
        
        foreach ($tcourse as $value) {
            $value->total_learner=$t_student;
            $value->tutor_name = $user->firstname.' '.$user->lastname;
            $value->total_course;
            $totals[] = $value;
        }
      }
  return $totals;
}


    public function gettotalCourse($instituteid){
        global $DB, $CFG,$PAGE;
        $t_course='';
        $tutor_array=array();

        if(isset($_GET['instituteid']) && $_GET['instituteid']!=''){
          $instituteid=$_GET['instituteid'];
        
        }

        if(!empty($instituteid)){
            $user = $DB->get_record('user', array('id' => $instituteid));

         $course1="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $instituteid";
       $tcourse1 = $DB->get_records_sql($course1);
       $getallcourseid=array(0);
       foreach ($tcourse1 as $key) {
           $getallcourseid[]=$key->id;
       }
        $allcourse_id=implode(',',$getallcourseid);
        $allcourse1="SELECT * FROM mdl_course where id in($allcourse_id)";
        $allcourses = $DB->get_records_sql($allcourse1);
        $courselevel = '';

         foreach ($allcourses as $course) {
                $customfield_data = $DB->get_record_sql("SELECT cf.configdata,cd.intvalue FROM {customfield_data} as cd INNER JOIN {customfield_field} as cf on cd.fieldid=cf.id WHERE cf.shortname='courselevel' AND cd.instanceid=$course->id");
                if ($customfield_data) {
                    $options = str_replace('\r\n','/', $customfield_data->configdata);
                    $options = json_decode($options);
                    $options = explode('/', $options->options);
                    $intvalue = $customfield_data->intvalue-1;
                    $courselevel = $options[$intvalue];
                }
                $course->level = $courselevel;
                $course->enroll=$CFG->wwwroot.'/enrol/index.php?id='.$course->id;
                $enrol_details = $DB->get_records_sql("SELECT enrol,cost,currency FROM {enrol} WHERE courseid=$course->id AND status=0");
                $enrol_price = 'Free';
                foreach ($enrol_details as $key => $value) {
                    if (!empty($value->cost)) {
                        $enrol_price = $value->cost.' '.$value->currency;
                    }
                }

              $context = context_course::instance($course->id); 
              $students = get_role_users(5 , $context);
              $tstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND c.id =?",array(7,$course->id));
              $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
              $teachers = get_role_users($role->id, $context);
                $teacherArray = array();
                foreach ($teachers as $teacher) {
                   $teacher->teacher_name=$teacher->firstname.''.$teacher->lastname;
                  $teacher->userimage = $this->get_user_picture($teacher->id);
                   $teacherArray[] = $teacher;
                     $course->user_url1 = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$teacher->id;
                   break;
                }

              $course->course_price = $enrol_price;
              $course->update_date = date('M Y', $course->timemodified);
              $course->summary = $this->limittext($course->summary, 30, $course->id);
              $course->courseimage = $this->course_image($course->id);
              $course->catagoryname = $this->catagoryname($course->category);
              $course->enrollStudent = $tstudent; //count($students);
              $course->teachers = $teacherArray;
              $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
              $tutor_array[] = $course;
          }



      }
        /*echo '<pre>';
        print_r($tutor_array);
        die();*/
       return $tutor_array;
    }


    private function limittext($text, $limit, $blogid) {
    $text = strip_tags($text);
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos   = array_keys($words);
          $text  = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
  }

   private function course_image($courseid) {
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
                return $CFG->wwwroot.'/local/courses/pix/noimage.png';
            }
        }
    }

    private  function get_user_picture($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $user = $DB->get_record('user', array('id' => $userid));
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        return $userimg->get_url($PAGE);
    }
    private function catagoryname($catagoryid){
        global $DB;
        $catagories = $DB->get_record("course_categories", array('id' =>$catagoryid));
        return $catagories->name;
    } 
  //    public function teacherdetails($allteacher){
  //       global $DB,$USER, $PAGE,
  //       $allteacher = $DB->get_records_sql("select  u.*  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  and  ra.roleid!=5 INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid WHERE c.id in(".implode(',',$tcourseid).")");

    
  //           }
       
  //   return 
  // }






}