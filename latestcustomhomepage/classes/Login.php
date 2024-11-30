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

class Login
{
	public $message=array();
    function __construct()
    {
    	 $this->sessiondata();
      echo $this->html();
      echo $this->css();
        echo $this->index();
        echo $this->js_script();
        $this->unsetsession();

    }
   public function unsetsession(){
    if(isset($_SESSION['messagedata'])){
        unset($_SESSION['messagedata']);
      }
   }

    public function sessiondata(){
      if(isset($_SESSION['messagedata'])){
        $data=$_SESSION['messagedata'];
      return $data;
      }
  
    }
     public function html(){
       global $DB, $OUTPUT, $PAGE, $USER,$CFG;
      if("/blocks/searchdashboard/login.php"==$_SERVER['REQUEST_URI']){
      redirect($CFG->wwwroot."/user-login/");
      exit();
      }
      $dataquery = $DB->get_record('other_seo_url',array('url'=>$_SERVER['REQUEST_URI']));
      $title="";
      $description="";
      $keywords="";
      if(!empty($dataquery->title)){
        $title=$dataquery->title;
      }
      if(!empty($dataquery->description)){
        $description=$dataquery->description;
      }
      if(!empty($dataquery->keywords)){
        $keywords=$dataquery->keywords;
      }
      
      return $rdata='<!DOCTYPE html><html><head><title>'.$title.'</title><meta name="description" content="'.$description.'"><meta name="keywords" content="'.$keywords.'"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    }
   public function css(){
   	global $DB,$CFG,$OUTPUT,$PAGE; 
       $attribute=array('rel'=>'stylesheet','href'=>'https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css');       
       $stylecss=array('rel'=>'stylesheet','href'=>$CFG->wwwroot.'/local/seodashboard/assest/login.css');
       $bootstarp=array('rel'=>'stylesheet','href'=>'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css');
        $css=html_writer::tag("link",'',$attribute);
        $fontawesome=array('rel'=>'stylesheet','href'=>'//use.fontawesome.com/releases/v5.0.7/css/all.css');
        $css.=html_writer::tag("link",'',$fontawesome);
        $css.=html_writer::tag("link",'',$bootstarp);
         $css.=html_writer::tag("link",'',$stylecss);
         $css.="</head><body auth>";
       return $css;

  }
//     public function allstatedata(){
//       global $DB,$CFG,$OUTPUT,$PAGE; 
//        $data=$DB->get_records_sql("SELECT * FROM {exam_categories} where deleted='1' ORDER BY name");
//        $rdata="";
//        foreach ($data as $value) {
//        	if($value->status=='Active')
//        	{
// $s="fa-eye";
//        	 }else{
// $s="fa-eye-slash";
//        	 }
//          $rdata.='<tr><td>'.$value->name.'</td><td><ul class="mmenu"><li><a href="'.$CFG->wwwroot.'/local/seodashboard/editexamcategory.php?id='.$value->id.'"><i class="fas fa-pen"></i></a></li><li><a href="'.$CFG->wwwroot.'/local/seodashboard/exam_status.php?id='.$value->id.'" onclick="return confirm(\'Are you sure you want to status update \')" state-name="'.$value->name.'" state-status="'.$value->id.'" class="updatestatestatus"><i class="fas '.$s.'" id="d873"></i></a></li><li><a href="'.$CFG->wwwroot.'/local/seodashboard/exam_status.php?id='.$value->id.'&delete=delete"  onclick="return confirm(\'Are you sure you want to delete? \')"><i class="fas fa-trash-alt"></i></a></li></ul></td></tr>';
//        	}
       
//        return $rdata; 
//     }
    public function index(){
    	 global $DB,$CFG,$OUTPUT,$PAGE; 
       // $allstatedata=self::allstatedata();
       $sessiondata=self::sessiondata();
       $sdata=json_decode(json_encode($sessiondata), true);
    	$setting=array('root'=>$CFG->wwwroot,'sesskey'=>sesskey(),'postdata'=>$sdata);
    	 return $OUTPUT->render_from_template('local_seodashboard/login', $setting);
    }
       public function js_script(){
        global $CFG,$PAGE;
        $js='';
     $attribute=array('src'=>new moodle_url("https://code.jquery.com/jquery-3.3.1.js"),'type'=>"text/javascript");
     $js.=html_writer::tag('script','',$attribute);

     $attribute=array('src'=>new moodle_url("https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"),'type'=>"text/javascript");
     $js.=html_writer::tag('script','',$attribute);

      $attribute=array('src'=>new moodle_url("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"),'type'=>"text/javascript");
      $js.=html_writer::tag('script','',$attribute);

      $attribute=array('src'=>new moodle_url("https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"),'type'=>"text/javascript");
      $js.=html_writer::tag('script','',$attribute);
      $attribute=array('src'=>new moodle_url($CFG->wwwroot.'/local/seodashboard/assest/js/login.js'),'type'=>"text/javascript");
      $js.=html_writer::tag('script','',$attribute);
      $js.='</body></html>';
    
    return $js;
    }
}