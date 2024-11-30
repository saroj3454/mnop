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
class Blog 
{
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
		$this->templateContext->logo = $this->extracom->getLogo();
         $this->templateContext->Footerlogo = $this->extracom->getFooterLogo();
		$this->templateContext->favicon = $this->extracom->favicon();
		$this->templateContext->sitename = $this->extracom->siteName();
		$this->templateContext->footerContent = $this->extracom->footerContentSocial();
		$this->templateContext->getintouchcontent = $this->extracom->footerContent();
		$this->templateContext->coursesmenu = $this->themesettings->coursecatedata();
        $this->templateContext->hasblogs = count($this->get_blogs()) > 0 ? true : false;
        $this->templateContext->blogs = $this->get_blogs();
        $this->templateContext->islogedin_user=islogedin_user();

		return $OUTPUT->render_from_template('local_courses/blogpage', $this->templateContext);
	}
    private function get_blogs(){
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
                        // Load defaults images.
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
    }


     function tutor_form_add_instance($data){
        global $DB,$CFG,$USER;    
         /*echo "<pre>";
         print_r($data);
         die;*/
         SELECT data FROM `mdl_user_info_data` where userid='2' and fieldid='7'

        $user = $DB->get_record_sql('SELECT data FROM {user_info_data} WHERE userid="'.$USER->id.'" and fieldid="7"');

        //$catequery = $DB->get_record('blog_category', array('id' => $data->categorycourse));
        $form_data = new stdClass;
        //$form_data->userid = $USER->id;
        //$form_data->blog_title = $data->blogtitle;
        //$form_data->coursecate = ($catequery->coursecat)?$catequery->coursecat:0;
        //$form_data->categoryid = $data->categorycourse;
        //$form_data->description = $data->description['text'];
        //$form_data->format = $data->description['format'];
        $form_data->tutordocument =  $data->tutor_document;
        $form_data->tutorvideo =  $data->tutor_video;
        $form_data->timecreated = time();
        
        if ($data->id) {
            $form_data->id = $data->id;
            $form_data->id = $DB->update_record("blog_list11", $form_data);
        }else{
            $form_data->id = $DB->insert_record("blog_list11", $form_data);
            $context = \context_system::instance();
            if ($data->local_blogimage) {
                file_save_draft_area_files($data->local_blogimage, $context->id, 'local_blogimage', 'local_blogimage', $form_data->id);           
            }
        }

        
        redirect($CFG->wwwroot.'/local/courses/tutor_form.php','Data updated successfully', 1);
    }
}