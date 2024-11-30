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
class Instructor  
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
    $this->templateContext->totals = $this->gettotalCourseandstudents(@$tutorid);
		$this->templateContext->logo = $this->extracom->getLogo();
    $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
    $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
		$this->templateContext->favicon = $this->extracom->favicon();
		$this->templateContext->sitename = $this->extracom->siteName();
        $this->templateContext->tutordetails = $this->tutordetails(@$tutorid);
		$this->templateContext->footerContent = $this->extracom->footerContentSocial();
		$this->templateContext->getintouchcontent = $this->extracom->footerContent();
		$this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
    $this->templateContext->hasblogs = count($this->get_blogs()) > 0 ? true : false;
    $this->templateContext->islogedin_user=islogedin_user();
    $this->templateContext->blogs = $this->get_blogs();
    // echo "<pre>";
    //  print_r($this->templateContext->blogs);
    //  die();

		return $OUTPUT->render_from_template('local_courses/instructor', $this->templateContext);
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

    public function tutordetails($tutorid){
        global $DB, $CFG,$PAGE;
        $t_course='';
        $details=array();

        if(isset($_GET['tutorid']) && $_GET['tutorid']!=''){
            $tutor_id=$_GET['tutorid'];
        
        }

        if(!empty($tutor_id)){
            $user = $DB->get_record('user', array('id' => $tutor_id));

            $profile="SELECT * FROM {user_info_data} ud inner join {user_info_field} uf on uf.id=ud.fieldid where uf.id=25 and userid = $tutor_id";
            $profile1 = $DB->get_record_sql($profile);

            $education="SELECT * FROM {user_info_data} ud inner join {user_info_field} uf on uf.id=ud.fieldid where uf.id=26 and userid = $tutor_id";
            $education1 = $DB->get_record_sql($education);

            $certifications="SELECT * FROM {user_info_data} ud inner join {user_info_field} uf on uf.id=ud.fieldid where uf.id=27 and userid = $tutor_id";
            $certifications1 = $DB->get_record_sql($certifications);

            $teachingexperience="SELECT * FROM {user_info_data} ud inner join {user_info_field} uf on uf.id=ud.fieldid where uf.id=28 and userid = $tutor_id";
            $teachingexperience1 = $DB->get_record_sql($teachingexperience);

            $otherworkexperiences="SELECT * FROM {user_info_data} ud inner join {user_info_field} uf on uf.id=ud.fieldid where uf.id=29 and userid = $tutor_id";
            $otherworkexperiences1 = $DB->get_record_sql($otherworkexperiences);
            
            $object = new stdClass();
            if(isset($profile1->data)){
                $object->tutorprofile = $profile1->data;
            }
            if(isset($education1->data)){
                $object->education = $education1->data;
            }
            if(isset($certifications1->data)){
                $object->certifications = $certifications1->data;
            }
            if(isset($teachingexperience1->data)){
                $object->teachingexperience = $teachingexperience1->data;
            }
            if(isset($otherworkexperiences1->data)){
                $object->otherworkexperiences = $otherworkexperiences1->data;
            }
            $details[] = $object;
        }
        return $details;
    }

    private function get_blogs(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
        $addquery = '';
        $blogs = '';
        if (isset($_GET['tutorid']) AND !empty($_GET['tutorid'])) {
            $blog_category = $_GET['tutorid'];

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
    $profileurl = $CFG->wwwroot."/theme/image.php/".$PAGE->theme->name."/core/1588856393/u/f1";
    $user_piture = $DB->get_record('files', array('id' => $picture));
    if ($user_piture) {
      $profileurl = $this->extracom->get_user_picture($userid, 200);
    } 
    return $profileurl;
  } 



  public function gettotalCourseandstudents($tutorid){
        global $DB, $CFG,$PAGE;
        $t_course='';
        $totals=array(0);

        if(isset($_GET['tutorid']) && $_GET['tutorid']!=''){
          $tutor_id=$_GET['tutorid'];
        
        }

        if(!empty($tutor_id)){
            $user = $DB->get_record('user', array('id' => $tutor_id));
            // echo "$user";
            // die();

         $course1="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $tutor_id";
       $tcourse1 = $DB->get_records_sql($course1);
       $getallcourseid=array(0);
       foreach ($tcourse1 as $key) {
           $getallcourseid[]=$key->id;
       }
      $imd=implode(',', $getallcourseid);
      // echo "<pre>";
      //  print_r($imd);
      //  die()
       

     /* echo $student="SELECT u.id,u.username,u.firstname,u.lastname,u.email FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND ct.instanceid IN ($imd)";*/


                            $bloginfo = $DB->get_record_sql("SELECT count(*)
                                as total_blog FROM {blog_list} WHERE status=1 AND userid=?", array($user->id));
           // $blog->total_blog = $bloginfo->total_blog;

            $tcourseid = $DB->get_fieldset_sql("SELECT DISTINCT c.id  FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid  AND userid=?", array($user->id));
                // echo "<pre>";
                //     print_r($tcourseid);
                // echo '</pre>';
                 $tstudent=0;
                 $tteacher=0;

                 if(!empty($tcourseid)){
    /*$tstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  and  ra.roleid=5 INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid WHERE c.id in(".implode(',',$tcourseid).")");*/
    $tstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
   /* $tteacher = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  and  ra.roleid!=5 INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid WHERE c.id in(".implode(',',$tcourseid).")");*/
    $tteacher = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
}





       $student="SELECT count(u.id)as total_student FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND u.id=$tutor_id and ct.instanceid IN ($imd)";
       $studentinfo = $DB->get_record_sql($student);
       $t_student=$tstudent; //$studentinfo->total_student;
        // echo "<pre>";
        //  print_r($t_student);
       
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
     /* echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<br>";
      echo "<pre>";
      print_r($totals);
      echo "</pre>";*/
  return $totals;
}


    public function gettotalCourse($tutorid){
        global $DB, $CFG,$PAGE;
        $t_course='';
        $tutor_array=array();

        if(isset($_GET['tutorid']) && $_GET['tutorid']!=''){
          $tutor_id=$_GET['tutorid'];
        
        }
      /*  echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "tutor_id:".$tutor_id;*/

        if(!empty($tutor_id)){
            $user = $DB->get_record('user', array('id' => $tutor_id));

         $course1="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $tutor_id";


                $sqlt="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id JOIN {role_assignments} ra ON ra.userid=ue.userid
                WHERE ue.userid = $tutor_id AND ra.roleid IN(3,4)";
                $t_coursedata=$DB->get_records_sql($sqlt);
                

       $tcourse1 = $DB->get_records_sql($course1);
       $courselevel="";
       $total_student=0;
       foreach($tcourse1 as $course){
            $customfield_data = $DB->get_record_sql("SELECT cf.configdata,cd.intvalue FROM {customfield_data} as cd INNER JOIN {customfield_field} as cf on cd.fieldid=cf.id WHERE cf.shortname='courselevel' AND cd.instanceid=$course->id");
                if($customfield_data){
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
             /* echo "<pre>";
              print_r($context);
              echo "</pre>"; */
              $students = get_role_users(5 , $context);
              /*echo "<pre>";
              print_r($students);
              echo "</pre>";*/

             // $total_student=$total_student+$students;

               $tstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND c.id=?",array(7,$course->id));
               
              $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
              $teachers = get_role_users($role->id, $context);
                $teacherArray = array();
                foreach ($teachers as $teacher) {
                    $user = $DB->get_record('user', array('id' => $tutor_id));
                    $teacher->teacher_name=$user->firstname.''.$user->lastname;
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
              // echo $course->enrollStudent;
              //  die();
              $course->teachers = $teacherArray;
              $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
              $tutor_array[] = $course;
       }
       //echo ""
/*       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       echo "<br>";
       foreach($t_coursedata as $course_list){
            $course_en_sql="SELECT u.*,en.courseid FROM {user} u  JOIN {user_enrolments} ue ON u.id=ue.userid JOIN {enrol} en ON en.id=ue.enrolid JOIN {role_assignments} ra ON ra.userid=ue.userid AND ra.userid=u.id WHERE en.courseid=? AND u.deleted=? AND u.suspended=? AND ra.roleid=? ";
            $course_en_data=$DB->get_records_sql($course_en_sql,array($course_list->id,0,0,5));
            echo "<pre>";
             echo "course id:".$course_list->id."<br>";
            echo "course name:". $course_list->fullname."<br>";

          
            echo "Total student:".sizeof($course_en_data);
           
            echo "</pre>";
       }*/
      
      /*  echo "<pre>";
        print_r($tcourse1);
        echo "</pre>";*/
        // echo "<pre>";
        //  print_r($tcourse1);
        //  die();
       $getallcourseid=array(0);
       foreach ($tcourse1 as $key) {
           $getallcourseid[]=$key->id;
       }
      $imd=implode(',', $getallcourseid);
       

       /*echo $student="SELECT u.id,u.username,u.firstname,u.lastname,u.email FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND ct.instanceid IN ($imd)";*/

       $student="SELECT count(u.id)as total_student FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid WHERE ra.roleid = 5 AND u.id=$tutor_id and ct.instanceid IN ($imd)";
       $studentinfo = $DB->get_record_sql($student);
       $t_student=$studentinfo->total_student;
       // echo "total student:".$t_student;
       
     //echo count(enrol_get_users_courses($tutor_id));


      

        $course="SELECT c.*,count(c.id)total_course FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                WHERE ue.userid = $tutor_id";
                
        $tcourse = $DB->get_records_sql($course);
          // echo "<pre>";
          //  print_r($tcourse);
          //  echo "</pre>";
           // die();
       /* echo "<pre>";
        print_r($tcourse);
        echo count($tcourse);
        echo "</pre>";*/
        $totals=array();
        foreach ($tcourse as $value) {
            $value->total_learner=$t_student;
            $value->tutor_name = $user->firstname.' '.$user->lastname;
            $value->total_course;
            $totals[] = $value;
        }

        $sql1 = "select contextid from mdl_role_assignments where roleid=3  and userid=$tutor_id";
        $res1 = $DB->get_records_sql($sql1);
        $contextid=array();
        $newinstanceid=array(0);
        foreach ($res1 as  $value) {
        $sql = "select instanceid from mdl_context where id=".$value->contextid." and contextlevel=50";
        $res = $DB->get_records_sql($sql);
        foreach ($res as $value) {
        $newinstanceid[]=$value->instanceid;
         
     
          }
       
       
        }
      /*  echo "<pre>";
        print_r($newinstanceid);
        echo "</pre>";*/
       
      $allcourse_id=implode(',',$newinstanceid);
      $allcourse1="SELECT * FROM mdl_course where id in($allcourse_id)";
      /*echo "<pre>";

      echo "</pre>";*/
       
        
        /*$allcourse1="SELECT c.* FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id WHERE ue.userid = $tutor_id";*/
      
        
        $allcourses = $DB->get_records_sql($allcourse1);


        $courselevel = '';
       /* echo "<pre>";
        print_r($allcourses);
        echo "</pre>";*/
      /*  
        echo "<pre>";
        
        print_r($allcourses);
        echo "</pre>";*/
         /*echo '<pre>';
         print_r($allcourses);
         die();*/

        /* foreach ($allcourses as $course) {
                $customfield_data = $DB->get_record_sql("SELECT cf.configdata,cd.intvalue FROM {customfield_data} as cd INNER JOIN {customfield_field} as cf on cd.fieldid=cf.id WHERE cf.shortname='courselevel' AND cd.instanceid=$course->id");
                if($customfield_data){
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
              $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
              $teachers = get_role_users($role->id, $context);
                $teacherArray = array();
                foreach ($teachers as $teacher) {
                    $user = $DB->get_record('user', array('id' => $tutor_id));
                    $teacher->teacher_name=$user->firstname.''.$user->lastname;
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
              $course->enrollStudent = count($students);
              $course->teachers = $teacherArray;
              $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
              $tutor_array[] = $course;
          }*/



      }
        /*echo '<pre>';
        print_r($tutor_array);
        die();*/
       
       /* echo "OK";
        print_r($tutor_array);
        echo "</pre>";*/
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


}