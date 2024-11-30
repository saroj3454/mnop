<?php
namespace local_seodashboard;
error_reporting(E_ALL);
ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use theme_moove\util\theme_settings;

class State 
{
	public $message=array();
    function __construct()
    {
    	echo $this->css();
        echo $this->index();
        echo $this->js_script();
    }
     public function css(){
     	global $DB,$CFG,$OUTPUT,$PAGE; 
         $attribute=array('rel'=>'stylesheet','href'=>'https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css');
         
         $stylecss=array('rel'=>'stylesheet','href'=>$CFG->wwwroot.'/local/seodashboard/assest/style.css');
          $css=html_writer::tag("link",'',$attribute);
          $css.=html_writer::tag("link",'',$stylecss);
         return $css;

    }
    public function index(){
    	 global $DB,$CFG,$OUTPUT,$PAGE; 
    	$setting=array('adstae'=>'<div class="allstate"> <a href="'.$CFG->wwwroot.'/local/seodashboard/addstate.php" class="btn bg-primary"> Add State</a></div>');
    	 return $OUTPUT->render_from_template('local_seodashboard/city', $setting);
    }
       public function js_script(){
        global $CFG,$PAGE;
     $attribute=array('src'=>new moodle_url("https://code.jquery.com/jquery-3.3.1.js"),'type'=>"text/javascript");
     $js=html_writer::tag('script','',$attribute);

     $attribute=array('src'=>new moodle_url("https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"),'type'=>"text/javascript");
     $js.=html_writer::tag('script','',$attribute);

      $attribute=array('src'=>new moodle_url("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"),'type'=>"text/javascript");
      $js.=html_writer::tag('script','',$attribute);

       $attribute=array('src'=>new moodle_url("https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"),'type'=>"text/javascript");
      $js.=html_writer::tag('script','',$attribute);
    
    return $js;
    }
}