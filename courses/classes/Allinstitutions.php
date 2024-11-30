<?php

namespace local_courses;

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use local_courses\extra;
use theme_moove\util\theme_settings;

defined('MOODLE_INTERNAL') || die();

/**
 * @course class 
 */
class Allinstitutions 
{



    function __construct()
    {

        $this->templateContext = new stdClass;
        $this->extracom = new extra;
        $this->themesettings = new theme_settings;
        echo $this->index();
    }

    public function index(){
        global $OUTPUT,$CFG;

        $this->templateContext->logo = $this->extracom->getLogo();
        $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
        $this->templateContext->favicon = $this->extracom->favicon();
        $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
        $this->templateContext->sitename = $this->extracom->siteName();
        $this->templateContext->footerContent = $this->extracom->footerContentSocial();
        $this->templateContext->getintouchcontent = $this->extracom->footerContent();
        $this->templateContext->coursesmenu = $this->themesettings->coursecatedata();

        $this->templateContext->hascategory = count($this->getAllCategory()) > 0 ? true:false;
        $this->templateContext->category = $this->getAllCategory();
        $this->templateContext->hasinstitute = count($this->getinstitute()) > 0 ? true:false;

        $this->templateContext->institute = $this->getinstitute();
        
        $this->templateContext->previous=null;
        $this->templateContext->next=null;


        if (isset($_GET['sortby']) AND !empty($_GET['sortby'])) {
          $this->templateContext->coursesort = $_GET['sortby'];
            }
            
        if (isset($_GET['searchbycatid']) AND !empty($_GET['searchbycatid'])) {
          $this->templateContext->currentCategory = $_GET['searchbycatid'];
         
        }

        
        /*Pagination starts here*/
$start = optional_param('start', 0, PARAM_INT);
$perpage = optional_param('perpage', 5, PARAM_INT);
if ($start >= sizeof($this->templateContext->institute)) {
    $start = 0;
}
$allparam = $_GET;
if($start != 0){
  $allparam['start'] = $start-$perpage;
  if($allparam['start'] < 0){ $allparam['start'] = 0; }
  $allparam['perpage'] = $perpage;
  $this->templateContext->previous=$CFG->wwwroot.'/local/courses/institutions.php?'.$this->mapparams($allparam);
}
if(sizeof($this->templateContext->institute)>$start){
  if($start+$perpage <=sizeof($this->templateContext->institute)){
    $allparam['start'] = $start+$perpage;
    $allparam['perpage'] = $perpage;
    $this->templateContext->next=$CFG->wwwroot.'/local/courses/institutions.php?'.$this->mapparams($allparam);
  }
  $this->templateContext->institute = array_values(array_slice($this->templateContext->institute, $start, $perpage, true));
}
$this->templateContext->hasinstitute = count($this->templateContext->institute) > 0 ? true:false;
/*Pagination ends here*/
  
        return $OUTPUT->render_from_template('local_courses/all-institutions', $this->templateContext);

       
    }

    private function getinstitute(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
        $addquery = '';
        // $blog->picture_url = self::getUserProfile($user->picture,$user->id);
        // $blog->userid = $user->id;
        // $blog->author_name = $user->firstname.' '.$user->lastname;
        $catfilter="";
        $search_catid = $_GET['searchbycatid'];
        /*echo "<pre>";

             echo $search_catid;
        echo "</pre>";*/
       
            if(!empty(intval($search_catid))){

                $getallcatid=array();
                $getallid=$DB->get_records("course_categories",array('parent'=>$search_catid));
                $getallcatid[]=$search_catid;
                foreach ($getallid as $value) {
                    $getallcatid[]=$value->id;
                }
                $getids=implode(',', $getallcatid);

                if(!empty($getids)){
                    $catfilter = " inner join {user_enrolments} ue on ue.userid=uid.userid inner join {enrol} e on e.id=ue.enrolid inner join {course} c on c.id=e.courseid and c.category in(".$getids.")";
                }
            }
        $sql = "SELECT DISTINCT uid.userid FROM {user_info_data} uid inner join {user} us on us.id=uid.userid ".$catfilter;
        if (isset($_GET['sortby']) AND !empty($_GET['sortby'])) {
            if ($_GET['sortby'] =='popular') {
                $sql = $sql . ' order by userid';
            }else{
                $sql = $sql . ' order by userid desc';
            }
        }
        
        $institute = $DB->get_records_sql($sql);
      

        $inccheck = 1;
        if (!empty($institute)) {
          foreach ($institute as $blog) {
            $picture_url = "";
         $imageofinstitution="SELECT institution_img as data FROM {tutor_document} ud WHERE ud.user_id = ".$blog->userid;
            $imageofinstitution1 = $DB->get_record_sql($imageofinstitution);

            if(isset($imageofinstitution1->data)){
                $iuiur = $CFG->wwwroot.'/theme/moove/'.$imageofinstitution1->data;
                $blog->imageofinstitution = $iuiur;
                $user = $DB->get_record('user', array('id' => $blog->userid));
                $blog->picture_url = self::getUserProfile($user->picture,$user->id);
                $blog->userid = $user->id;
                $Nameofinstitution="SELECT data FROM {user_info_data} uid INNER JOIN {user_info_field} uif ON uid.fieldid = uif.id WHERE uif.id = 9 AND uid.userid = ".$blog->userid;
                $Nameofinstitution1 = $DB->get_record_sql($Nameofinstitution);

                if(isset($Nameofinstitution1->data)){
                    $blog->Nameofinstitution = $Nameofinstitution1->data;
            
                }
            

                $bloginfo = $DB->get_record_sql("SELECT count(*) as total_blog FROM {blog_list} WHERE status=1 AND userid=".$blog->userid);

                if(isset($bloginfo->total_blog)){
                    $blog->total_blog = $bloginfo->total_blog;
                }
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
    //$tteacher = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
     $allteacher = $DB->get_records_sql("select  u.*  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id  INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
     $tteacher=count($allteacher);

}

            
        $blog->total_student =$tstudent;
        $blog->total_tutor = $tteacher;
        $tcourse = $DB->get_record_sql("SELECT count(c.id)total_course FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid =".$blog->userid);
                if(isset($tcourse->total_course)){
                    $blog->total_course = $tcourse->total_course;
                }

                if($inccheck%2==0){
                    $blog->inccheck = false;
                }else{
                    $blog->inccheck = true;
                }
                $inccheck = $inccheck + 1;
                $blogArray[] = $blog;
            }
            
          }
        }
        if(isset($_GET['sortby']) && $_GET['sortby'] =='popular') {
                usort($blogArray, shortinstituteby('total_student'));
            }
            if(!isset($_GET['sortby'])){
                 usort($blogArray, shortinstituteby('total_student'));
            }
         
        
        return $blogArray;
    }

    public function getUserProfile($picture,$userid){
        global $DB, $CFG, $USER,$PAGE;
        $profileurl = $CFG->wwwroot."/local/courses/images/head.png";
        $user_piture = $DB->get_record('files', array('id' => $picture));
        if($user_piture) {
          $profileurl = $this->extracom->get_user_picture($userid, 200);
        } 
        return $profileurl;
    }
   private function mapparams($params){
      $returndata = array();
      foreach ($params as $key => $param) {
        array_push($returndata, $key."=".$param);
      }
      return implode("&", $returndata);
    } 
    private  function get_user_picture($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $user = $DB->get_record('user', array('id' => $userid));
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        return $userimg->get_url($PAGE);
    }

    private function getAllCategory(){
        global $DB;
        $coursecategoriesss = array();
       $coursecategories = $DB->get_records("course_categories", array("parent" => 0, "visible"=>1));
       if (!empty($coursecategories)) {
           foreach ($coursecategories as $value) {
               //value->image = $this->getCategoryImage($value->id);
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



}

function shortinstituteby($key) {
    return function ($a, $b) use ($key) {
        if ($a->$key==$b->$key) return 0;
return ($a->$key>$b->$key)?-1:1;
        // return strnatcmp($b->$key, $a->$key);
    };
}