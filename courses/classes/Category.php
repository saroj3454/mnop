<?php

namespace local_courses;
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
 * @class Deals with Category.
 */
class Category 
{
	
	protected $categoryid;
	protected $slidercategoryimages;
	protected $templateContext;
	protected $extracom;
	protected $themesettings;

	function __construct($category)
	{	
		$this->categoryid = $category;
		$this->slidercategoryimages = array();
		$this->templateContext = new stdClass;
		$this->extracom = new extra;
		$this->themesettings = new theme_settings;
		echo $this->index();
	}

	public function index(){
		global $OUTPUT;

		$this->templateContext->logo = $this->extracom->getLogo();
         $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
		$this->templateContext->favicon = $this->extracom->favicon();
        $this->templateContext->user_menu=$this->extracom->vrtuz_menu1();
		$this->templateContext->sitename = $this->extracom->siteName();
		$this->templateContext->footerContent = $this->extracom->footerContentSocial();
		$this->templateContext->getintouchcontent = $this->extracom->footerContent();
		$this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
		$this->templateContext->catagoryname = strtoupper($this->catagoryname($this->categoryid));
		$this->templateContext->description = $this->getCategoryDetails()->description;
		$this->templateContext->showsubcat = count($this->getSubCategory()) > 0 ? true : false;
		$this->templateContext->subcat = count($this->getSubCategory()) > 0 ? $this->getSubCategory() : array();
		$this->templateContext->iscategory = 1;
		$this->templateContext->LatestCourses = $this->LatestCourses($this->categoryid);
		$this->templateContext->showteachers = count($this->teachersInThisCategory($this->categoryid)) > 0 ? true : false;
         
		$this->templateContext->teachersInThisCategory = $this->teachersInThisCategory($this->categoryid);
        // echo "<pre>";
        //  print_r($this->templateContext->teachersInThisCategory);
        //  echo "</pre>";
        //  die();
		$this->templateContext->get_blogs = $this->get_blogs();
		$this->templateContext->showtrend = count($this->recentTrendsInCategory($this->categoryid)) > 0 ? true : false;
		$this->templateContext->recenttrends = $this->recentTrendsInCategory($this->categoryid);
		$this->templateContext->showslider = count($this->categorySliderImages($this->categoryid)) > 0 ? true : false;
		$this->templateContext->sliders = $this->categorySliderImages($this->categoryid); 
        // echo "<pre>"; print_r($this->templateContext->LatestCourses);die;
        $this->templateContext->islogedin_user=islogedin_user();
		return $OUTPUT->render_from_template('local_courses/coursehome', $this->templateContext);

	}

	private function checkCategory(){
		global $DB;
		return $DB->count_records('course_categories', array('visible' => 1, 'id' => $this->categoryid));
	}

	private function getCategoryDetails(){
		global $DB;
		if ($this->checkCategory()) {
			return $DB->get_record('course_categories', array('id'=> $this->categoryid, 'visible' => 1));
		}
	}

	private function getSubCategory(){
		global $DB;
		$testarray = array();
		if ($this->checkCategory()) {
			$data = $DB->get_records('course_categories', array( 'parent' => $this->categoryid, 'visible' => 1));
			foreach ($data as $value) {
				$testarray2 = array();
				foreach ($this->getcoursesWithCategory($value->id) as $value2) {
					$testarray2[] = $value2;
				}
				$value->getcoursesWithCategory = $testarray2;
				$value->showcat = count($testarray2) > 0 ? true : false;
				$testarray[] = $value;
			}
		}
		return $testarray;
	}
    private function getcoursesWithCategory($categoryid){
        global $DB, $CFG;
        $allcourses = array();
        if (!is_null($categoryid)) {
            $categoryid = $categoryid;
            $allcourses = $DB->get_records_sql('SELECT * FROM {course} WHERE category = ? AND visible = 1', [$categoryid]);        
	        foreach ($allcourses as $course) {
	            $context = context_course::instance($course->id); 
	            $students = get_role_users(5 , $context);
	            $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
				$teachers = get_role_users($role->id, $context);
                $teacherArray = array();
               if(!empty($teachers)){
                    foreach ($teachers as $teacher) {
                     $teacher->userimage = $this->get_user_picture($teacher->id);
                     $teacherArray[] = $teacher;
                     break;
                    }
                }else{$teacherArray['userimage']='https://www.vrtuz.com/pluginfile.php/83/user/icon/moove/f3?rev=1368';}

                
                

                $enrol_details = $DB->get_records_sql("SELECT enrol,cost,currency FROM {enrol} WHERE courseid=$course->id AND status=0");
                $enrol_price = 'Free';
                foreach ($enrol_details as $key => $value) {
                    if (!empty($value->cost)) {
                        $enrol_price = $value->cost.' '.$value->currency;
                    }
                }
                $course->course_price = $enrol_price;
                if(!empty($teacher->id)){$course->user_url1 = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$teacher->id;}
                
                $course->summary = $this->limittext($course->summary, 30, $course->id);
	            $course->courseimage = $this->course_image($course->id);
	            $course->catagoryname = $this->catagoryname($course->category);
                // echo $course->catagoryname;
                
	           $totalstudent=$course->enrollStudent = count($students);
            
               
	            $course->teachers = $teacherArray;
                // echo "<pre>";
                //   print_r($course->teachers);
                //   echo "</pre>";
                //   die();
                $course->enroll=$CFG->wwwroot.'/enrol/index.php?id='.$course->id;
	            $course->coursedetails = new moodle_url('/local/courses/index.php', array('courseid' => $course->id));
        	}
    	}
        	return $allcourses;
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
   	private function catagoryname($catagoryid){
        global $DB;
        $catagories = $DB->get_record("course_categories", array('id' =>$catagoryid));
        return $catagories->name;
    } 
    
    private function LatestCourses($categoryid){
        global $DB, $CFG;
        $teacherArray = array();
        $courseReturn = array();

        /*$get_cat_id=$DB->get_record("course_categories",array('id'=>$categoryid));
        $parent_id=$get_cat_id->parent;*/

        $getallcatid=array();
         $getallid=$DB->get_records("course_categories",array('parent'=>$categoryid));
             $getallcatid[]=$categoryid;
            foreach ($getallid as $value) {
                $getallcatid[]=$value->id;

            }
        $getids=implode(',', $getallcatid);
        //print_r($getallcatid);
        


        if (!is_null($categoryid)) {
            $categoryid = $categoryid;
             $allcourses = $DB->get_records_sql("SELECT * FROM {course} WHERE category in ($getids) AND visible = 1 ORDER BY id desc");   
           /* $allcourses = $DB->get_records_sql("SELECT * FROM {course} WHERE category = $categoryid AND visible = 1 ORDER BY id desc");   */     
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

	            $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
				$teachers = get_role_users($role->id, $context);
                $teacherArray = array();
                foreach ($teachers as $teacher) {
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
	            $courseReturn[] = $course;
        	}
    	}
        // echo "<pre>";print_r($courseReturn);die;
        	return $courseReturn;
	}

	private function teachersInThisCategory($categoryid){
        // echo $categoryid;
        // die();
        global $DB, $CFG,$PAGE;
        $teacherArray = array();
        if (!is_null($categoryid)) {
            $allcourses = $DB->get_records_sql("SELECT * FROM {course} WHERE category = $categoryid AND visible = 1");        
	        foreach ($allcourses as $course) {

	            $context = context_course::instance($course->id); 
	            $students = get_role_users(5 , $context);

	            $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
				$teachers = get_role_users($role->id, $context);

                foreach ($teachers as $teacher) {
                    $skip = 0;
                    foreach ($teacherArray as $value) {
                        if ($value->id == $teacher->id) {
                            $skip = 1;
                        }
                    }
                    if ($skip == 1) {
                        continue;
                    }
		        	$profileurl = $CFG->wwwroot."/theme/image.php/".$PAGE->theme->name."/core/1588856393/u/f1";
             
	               $tcourseid = $DB->get_fieldset_sql("SELECT DISTINCT c.id  FROM mdl_course c JOIN mdl_enrol en ON en.courseid = c.id JOIN mdl_user_enrolments ue ON ue.enrolid = en.id 
                            WHERE ue.userid  AND userid=?", array($teacher->id));
                    $totalstudent=0;
                    if(!empty($tcourseid )){
                       $totalstudent = $DB->get_field_sql("SELECT  count(u.id)  FROM  mdl_user u INNER JOIN mdl_role_assignments ra ON ra.userid = u.id INNER JOIN mdl_context ct ON ct.id = ra.contextid inner join mdl_course c on c.id = ct.instanceid JOIN {user_info_data} uid ON uid.userid=u.id WHERE  uid.fieldid=? AND uid.data NOT IN('Tutor','Institution') AND c.id in(".implode(',',$tcourseid).")",array(7));
                    }
                    $user = $DB->get_record('user', array('id' => $teacher->id));
					$user_piture = $DB->get_record('files', array('id' => $user->picture));
					if ($user_piture) {
						$profileurl = $this->get_user_picture($user->id, 200);
					} 

                    $teacher->user_url = $CFG->wwwroot.'/user/profile.php?id='.$teacher->id;
                    $teacher->user_url1 = $CFG->wwwroot.'/local/courses/index.php?tutorid='.$teacher->id;
                    $teacher->description = $this->limittext($user->description, 20, 2);
                    $teacher->profileurl = $profileurl;
                    $teacher->totalstudent = $totalstudent;
                    $teacher->totalCourses = sizeof($tcourseid);
                    
                	$teacherArray[] = $teacher;
                }
        	}
    	}
            // echo "<pre>";print_r($teacherArray);die;
        	return $teacherArray;
	}
	private function get_blogs(){
        global $DB, $CFG,$PAGE;
        $blogArray = array();
        $addquery = '';
        $blogs = '';
        if (isset($_GET['categoryid']) AND !empty($_GET['categoryid'])) {
            $blog_category = $DB->get_record_sql("SELECT * FROM {blog_category} WHERE status=? AND categorycourse=?",array(1,$_GET['categoryid']));
            if ($blog_category) {
                // $addquery = ' AND categoryid='.$blog_category->id;
                $blogs = $DB->get_records_sql("SELECT * FROM {blog_list} WHERE status=? AND categoryid=?",array(1,$blog_category->id));
            }
        }
        if (!empty($blogs)) {
          foreach ($blogs as $blog) {
            $picture_url = "";
            $user = $DB->get_record('user', array('id' => $blog->userid));
            $category = $DB->get_record_sql("SELECT * FROM {blog_category} WHERE id=$blog->categoryid");

            $blog->categoryname = $category->categoryname;
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
        // echo "<pre>";print_r($blogArray);die;
        return $blogArray;
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


	private  function get_user_picture($userid = null, $imgsize = 100) {
        global $USER, $PAGE, $DB;
        $user = $DB->get_record('user', array('id' => $userid));
        $userimg = new \user_picture($user);
        $userimg->size = $imgsize;
        return $userimg->get_url($PAGE);
    }

/*
* Recent Trends in Category
*/
    public function recentTrendsInCategory($categoryid = null){
    	global $DB;
    	$dataarray = array();
    	if (!is_null($categoryid)) {
    		
    		$courses = $DB->get_records_sql('SELECT * FROM {course} WHERE visible = 1 AND category = :categoryid ORDER BY {course}.timecreated DESC LIMIT 2',
    			['categoryid'=> $categoryid]);
    		
    		$courses = array();

    		if (!empty($courses)) {
    			foreach ($courses as $value) {
    				$data = new stdClass;
    				$data->id = $value->id;
    				$data->fullname = $value->fullname;
    				$data->summary = $this->limittext($value->summary, 30, 0);
    				$data->image = $this->course_image($value->id);
    				$data->url = '/local/courses/index.php?courseid='.$value->id;
    				array_push($dataarray, $data);
    			}
    		}

    		$course_categories = $DB->get_records_sql('SELECT * FROM {course_categories} WHERE visible = 1 AND parent = :categoryid ORDER BY {course_categories}.id ASC LIMIT 4',
    			['categoryid'=> $categoryid]);

    		if (!empty($course_categories)) {
    			foreach ($course_categories as $value) {
					$data = new stdClass;
    				$data->id = $value->id;
    				$data->fullname = $value->name;
    				$data->summary = $this->limittext($value->description, 30, 0);

    				$images = isset($this->categorySliderImages($value->id)[0]->image) ? $this->categorySliderImages($value->id)[0]->image : "" ;
    				if (!$images) {
    					$images = "/local/courses/pix/noimg.jpg";
    				}
    				$data->image = $images;
    				$data->url = '/local/courses/index.php?categoryid='.$value->id;
    				array_push($dataarray, $data);
    			}
    		}
    		
    	}

    	return $dataarray;
    }

    public function categorySliderImages($categoryid){
    	global $DB, $CFG;
    	$slidersimages = array();

    	if (!is_null($categoryid)) {
    	   $catslders = $DB->get_records_sql("SELECT * FROM {files} WHERE component = 'customcategory' AND filearea = 'categoryimage' AND itemid = ".$categoryid);
                
                if (!empty($catslders)) {
                    foreach ($catslders as $key => $value) {
                         if ($value->filename !== ".") {
                        	$iuiur = $CFG->wwwroot.'/local/courses/file.php/'.$value->pathnamehash.'/0/'.$value->filename;
                        	$retndata = new stdClass;
                        	$retndata->key = $value->id;
                        	$retndata->image = $iuiur;
                        	$retndata->title = $this->getCategoryDetails()->name;
                        	$retndata->description = $this->limittext($this->getCategoryDetails()->description, 30, 0);
                        	array_push($slidersimages, $retndata);
                        }   
                    }
                }
    	}
    	return $slidersimages;
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
}