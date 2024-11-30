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
class Allinstructor 
{



    function __construct()
    {

        $this->templateContext = new stdClass;
        $this->extracom = new extra;
        $this->themesettings = new theme_settings;
        echo $this->index();
    }

    public function index(){
        global $OUTPUT, $CFG;

        $this->templateContext->output = $OUTPUT;
        $this->templateContext->logo = $this->extracom->getLogo();
        $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
        $this->templateContext->favicon = $this->extracom->favicon();
        $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
        $this->templateContext->sitename = $this->extracom->siteName();
        $this->templateContext->footerContent = $this->extracom->footerContentSocial();
        $this->templateContext->getintouchcontent = $this->extracom->footerContent();
        $this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
         $this->templateContext->islogedin_user=islogedin_user();
        $this->templateContext->hascategory = count($this->getAllCategory()) > 0 ? true:false;
        $this->templateContext->category = $this->getAllCategory();

        $this->templateContext->tutors = $this->gettutors();
        // echo "<pre>";
        //   print_r($this->templateContext->tutors);
        //   die();
        $this->templateContext->notutors = count($this->gettutors()) > 0 ?'':'https://www.vrtuz.com/local/courses/images/nofound.png';
        $this->templateContext->previous=null;
        $this->templateContext->next=null;
        //$this->templateContext->showslider = array_values($this->slideshow());
        if (isset($_GET['sortby']) AND !empty($_GET['sortby'])) {
          $this->templateContext->coursesort = $_GET['sortby'];
        }
        if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid'])) {
          $this->templateContext->currentCategory = $_GET['searchbycatid'];
        }
/*Pagination starts here*/
$start = optional_param('start', 0, PARAM_INT);
$perpage = optional_param('perpage', 6, PARAM_INT);
if ($start >= sizeof($this->templateContext->tutors)) {
    $start = 0;
}
$allparam = $_GET;
if($start != 0){
  $allparam['start'] = $start-$perpage;
  if($allparam['start'] < 0){ $allparam['start'] = 0; }
  $allparam['perpage'] = $perpage;
  $this->templateContext->previous=$CFG->wwwroot.'/local/courses/tutors.php?'.$this->mapparams($allparam);
}
if(sizeof($this->templateContext->tutors)>$start){
  if($start+$perpage <=sizeof($this->templateContext->tutors)){
    $allparam['start'] = $start+$perpage;
    $allparam['perpage'] = $perpage;
    $this->templateContext->next=$CFG->wwwroot.'/local/courses/tutors.php?'.$this->mapparams($allparam);
  }
  $this->templateContext->tutors = array_values(array_slice($this->templateContext->tutors, $start, $perpage, true));
}
$this->templateContext->hastutors = count($this->templateContext->tutors) > 0 ? true:false;
/*Pagination ends here*/
  return $OUTPUT->render_from_template('local_courses/all-instructors', $this->templateContext);

       
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
               $value->url = '/local/courses/tutors.php?categoryid='.$value->id;
               $value->cat_name = $value->name;
               $value->cat_id=$value->id;
               array_push($coursecategoriesss, $value);
           }
       }
       // echo "<pre>";print_r($coursecategoriesss);die;
       return $coursecategoriesss;
    }
    private function mapparams($params){
      $returndata = array();
      foreach ($params as $key => $param) {
        array_push($returndata, $key."=".$param);
      }
      return implode("&", $returndata);
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
    }

    private function gettutors(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
       /* $addquery = '';
        $sql = "select DISTINCT ue.userid from mdl_user_enrolments ue inner join mdl_enrol en on en.id=ue.enrolid and ue.userid NOT in (".$CFG->siteadmins.") inner join mdl_course co on co.id=en.courseid inner join mdl_context cn on co.id = cn.instanceid and cn.contextlevel = 50 JOIN {user_info_data} uid ON uid.userid=ue.userid WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution')";*/
           $sql = "select DISTINCT ue.userid from mdl_user_enrolments ue inner join mdl_enrol en on en.id=ue.enrolid  inner join mdl_course co on co.id=en.courseid inner join mdl_context cn on co.id = cn.instanceid  JOIN {user_info_data} uid ON uid.userid=ue.userid WHERE uid.fieldid=? AND uid.data  IN('Tutor','Institution') ";
        if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid'])) {
           /* $search_catid=$_GET['searchbycatid'];
              $getallcatid=array();
                $getallid=$DB->get_records("course_categories",array('parent'=>$search_catid));
                $getallcatid[]=$search_catid;
                foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                }
                $getids=implode(',', $getallcatid);

                if(!empty($getids)){
                    $catfilter = " inner join {user_enrolments} ue on ue.userid=uid.userid inner join {enrol} e on e.id=ue.enrolid inner join {course} c on c.id=e.courseid and c.category in(".$getids.")";
                }*/

            $sql = $sql . ' and co.category='.$_GET['searchbycatid'];
           
        }
        if (isset($_GET['sortby']) AND !empty($_GET['sortby'])) {
            if ($_GET['sortby'] =='popular') {
                $sql = $sql . ' order by ue.userid desc';
    
            }else{
                $sql = $sql . ' order by ue.userid DESC' ;
            }
        }
        
        $blogs = $DB->get_records_sql($sql,array(7));
           

        if (!empty($blogs)) {
          foreach ($blogs as $blog) {
            $picture_url = "";
            $user = $DB->get_record('user', array('id' => $blog->userid));
            $blog->picture_url = self::getUserProfile($user->picture,$user->id);
            $blog->userid = $user->id;
            $blog->author_name = $user->firstname.' '.$user->lastname;
            

            $bloginfo = $DB->get_record_sql("SELECT count(*) as total_blog FROM {blog_list} WHERE status=1 AND userid=".$user->id);

            $blog->total_blog = $bloginfo->total_blog;

            $tcourseid = $DB->get_fieldset_sql("SELECT DISTINCT c.id  FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid =".$blog->userid);
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

            $studentinfo = $DB->get_record_sql("SELECT count(u.id)as total_student FROM mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND u.id=?",array(7,$user->id));
            // echo "<pre>";
            // print_r($studentinfo);
            // echo "</pre>";
           // die;

            $blog->total_student =$tstudent; // $studentinfo->total_student;
            //echo 
            // echo "<pre>";
            //  print_r($blog->total_student);
            //  echo "</pre>";
            //  die();
                        
            $tcourse = $DB->get_record_sql("SELECT count(c.id)total_course FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                        WHERE ue.userid =".$user->id);

            $blog->total_course = $tcourse->total_course;
            // $blog->user_url = $CFG->wwwroot.'/user/profile.php?id='.$user->id;
            // $blog->summary = $this->extracom->limittext($blog->description, 15, $blog->id);
            // $blog->date = date('m/d/Y', $blog->timecreated);
            $blogArray[] = $blog;
          }
        
        }


        // echo "<pre>";print_r($blogArray);die;
   
      // $blogArray[] = $blog
        if (isset($_GET['sortby']) AND !empty($_GET['sortby'])) {
            if ($_GET['sortby'] =='popular') {
                usort($blogArray, array($this,'comparatorFunc'));
            }
        }
  
 //usort($blogArray, array($this,'comparatorFunc'));
    // echo "<pre>";
    // print_r($blogArray);
    // echo "</pre>"; 
    //  die();
        return $blogArray;
    }

 public static function comparatorFunc( $x, $y)
    {   
        // If $x is equal to $y it returns 0
        if ($x->total_student== $y->total_student)
            return 0;
      
        // if x is less than y then it returns -1
        // else it returns 1    
        if ($x->total_student > $y->total_student)
            return -1;
        else
            return 1;
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

    private  function get_user_picture($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $user = $DB->get_record('user', array('id' => $userid));
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        return $userimg->get_url($PAGE);
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
           $getallcourseid=array();
           foreach ($tcourse1 as $key) {
               $getallcourseid[]=$key->id;
           }
          $imd=implode(',', $getallcourseid);
           // echo "$imd";
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

    public function slideshow() {
        global $OUTPUT, $CFG;
        //require($CFG->wwwroot.'/local/courses/instructor.php');
        //$theme = theme_config::load('moove');

        /*$templatecontext['sliderenabled'] = $theme->settings->sliderenabled;

        if (empty($templatecontext['sliderenabled'])) {
            return $templatecontext;
        }

        $slidercount = $theme->settings->slidercount;

        for ($i = 1, $j = 0; $i <= $slidercount; $i++, $j++) {
            $sliderimage = "sliderimage{$i}";
            $slidertitle = "slidertitle{$i}";
            $slidercap = "slidercap{$i}";
            $sliderbutton = "sliderbutton{$i}";
            $sliderbuttonurl = "sliderbuttonurl{$i}";

            $templatecontext['slides'][$j]['key'] = $j;
            $templatecontext['slides'][$j]['active'] = false;

            $image = $theme->setting_file_url($sliderimage, $sliderimage);
            if (empty($image)) {
                $image = $OUTPUT->image_url('slide_default', 'theme');
            }
            $parts1 = (isset($theme->settings->$slidertitle) ? $theme->settings->$slidertitle : false);
            $parts2 = (isset($theme->settings->$slidercap) ? $theme->settings->$slidercap : false);
            
            $parts3 = (isset($theme->settings->$sliderbutton) ? $theme->settings->$sliderbutton : false);
            $parts4 = (isset($theme->settings->$sliderbuttonurl) ? $theme->settings->$sliderbuttonurl : false);

            $templatecontext['slides'][$j]['image'] = $image;
            $templatecontext['slides'][$j]['title'] = $parts1;
            $templatecontext['slides'][$j]['caption'] = $parts2;
            
            $templatecontext['slides'][$j]['button'] = $parts3;
            $templatecontext['slides'][$j]['buttonurl'] = $parts4;

            if ($i === 1) {
                $templatecontext['slides'][$j]['active'] = true;
            }
        }
        return $templatecontext;*/
    }
}