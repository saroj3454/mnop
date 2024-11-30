<?php
class Stateinsert 
{
	public $message=array();
    function __construct()
    {
    	echo $this->css();
        echo $this->index();
        echo $this->js_script();
    }
    public function headerdata(){

    }
     public function css(){
     	global $DB,$CFG,$OUTPUT,$PAGE; 
         $attribute=array('rel'=>'stylesheet','href'=>'https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css');
         
         $stylecss=array('rel'=>'stylesheet','href'=>$CFG->wwwroot.'/local/seodashboard/assest/style.css');
          $css=html_writer::tag("link",'',$attribute);
          $css.=html_writer::tag("link",'',$stylecss);
         return $css;

    }
    public static function allstate(){
       global $DB,$CFG,$OUTPUT,$PAGE; 
       return $data=$DB->get_records_sql("SELECT * FROM `mo_exam_state` where status='Active' and deleted='1' ORDER BY state_title");
    }
    public static function allcity(){
       global $DB,$CFG,$OUTPUT,$PAGE; 
       return $data=$DB->get_records_sql("SELECT * FROM `mo_exam_city` where status='Active' and deleted='1' ORDER BY state_title");
    }
    public static function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
     }
    public function index(){
    	 global $DB,$CFG,$OUTPUT,$PAGE; 
    	require_once($CFG->dirroot.'/local/seodashboard/classes/cityform.php');
      if(!empty($_GET['action'])){
        $action=$_GET['action'];
        if($action=='city'){
          if(!empty($_GET['id'])){
            $dataquery = $DB->get_record_sql("SELECT c.*,s.state_title,s.id as state_id FROM {exam_city} as c left join {exam_state} as s on c.state_id=s.id where s.deleted='1' and s.status='Active' and c.id='".$_GET['id']."'");
            $statename=$dataquery->state_title;
            $setdata = new stdClass();
            $setdata->id=$dataquery->id;
            $setdata->actionid=$dataquery->districtid;
            $setdata->action='city';
            $setdata->name=$dataquery->name;
            $setdata->description=unserialize($dataquery->description);
            $setdata->image=$dataquery->image;
            $setdata->slug=$dataquery->slug;
            $setdata->state_id=$dataquery->state_id;


          }
          if(!empty($_GET['district_id'])){
          	 $dataquery=$DB->get_record_sql("SELECT * FROM `mo_exam_district` where id='".$_GET['district_id']."' ");
          	$setdata = new stdClass();
          	$setdata->actionid=$dataquery->id;
          	$setdata->action='city';
            $setdata->state_id=$dataquery->state_id;

          	// $setdata->name=$dataquery->district_title;
            // $setdata->description=unserialize($dataquery->description);
            // $setdata->image=$dataquery->image;
          }
          if(!empty($_GET['state_id'])){
            $dataquery = $DB->get_record_sql("SELECT * FROM {exam_state} where id='".$_GET['state_id']."'");
            $statename=$dataquery->state_title;
            $setdata = new stdClass();
            $setdata->action='city';
            $setdata->state_id=$dataquery->id;
            $setdata->stateadd='stateadd';
          }
        }else{
          //state action.
        	if(!empty($_GET['state_id'])){

        		$setdata = new stdClass();
	          	$setdata->actionid=$_GET['state_id'];
	          	$setdata->action='district';
        	}
        	if(!empty($_GET['id'])){
        	$dataquery=$DB->get_record_sql("SELECT * FROM `mo_exam_district` where id='".$_GET['id']."'");
          	$setdata = new stdClass();
          	$setdata->actionid=$dataquery->state_id;
          	$setdata->action='district';
          	$setdata->name=$dataquery->district_title;
            $setdata->description=unserialize($dataquery->description);
            $setdata->image=$dataquery->image;
            $setdata->id=$dataquery->id;
            $setdata->slug=$dataquery->slug;
        	}



        }
      }
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $mform = new cityform($actual_link); 
      $mform->set_data($setdata);
      
        if ($mform->is_cancelled()) {
        redirect($CFG->wwwroot."/local/seodashboard/state.php");
        } else if ($fromform = $mform->get_data()) {
        	
           if($fromform->action=='city'){
                // if(!empty($fromform->stateadd)){

                // }else{
                  $insertdata=new stdClass();
                  $insertdata->name=$fromform->name;
                  $insertdata->districtid=$fromform->actionid;
                  $insertdata->description=serialize($fromform->description);
                  $insertdata->image=$fromform->image;
                  $insertdata->state_id=$fromform->state_id;
                  $insertdata->id=$fromform->id;
                  $insertdata->slug=self::urlslug($fromform->name);

		                if(!empty($fromform->id)){
                      // echo"<pre>";
                      // print_r($insertdata);
                      // die();
                       $insertdata->slug=self::urlslug($fromform->slug);
		                  $DB->update_record('exam_city',$insertdata,true);
		                  redirect($CFG->wwwroot."/local/seodashboard/state.php?sucess=updated&action=city", 'Successfully Updated...', null, \core\output\notification::NOTIFY_SUCCESS);
		                }else{

                       $insertdata->status='Active';
		                  $DB->insert_record('exam_city',$insertdata,true);
		                  redirect($CFG->wwwroot."/local/seodashboard/state.php?sucess=inserted", 'Successfully Inserted...', null, \core\output\notification::NOTIFY_SUCCESS);
		                }
            		// }

           }
           if($fromform->action=='district'){
           	      $insertdata=new stdClass();
                  $insertdata->district_title=$fromform->name;
                  $insertdata->state_id=$fromform->actionid;
                  $insertdata->district_description=serialize($fromform->description);
                  $insertdata->image=$fromform->image;
                  $insertdata->id=$fromform->id;
                  $insertdata->slug=self::urlslug($fromform->slug);

                    if(!empty($fromform->id)){
                      $insertdata->slug=self::urlslug($fromform->slug);
		                  $DB->update_record('exam_district',$insertdata,true);
		                  redirect($CFG->wwwroot."/local/seodashboard/state.php?sucess=updated&action=district", 'Successfully Updated...', null, \core\output\notification::NOTIFY_SUCCESS);
		                }else{
                      $insertdata->status='Active';
		                  $DB->insert_record('exam_district',$insertdata,true);
		                  redirect($CFG->wwwroot."/local/seodashboard/state.php?sucess=district", 'Successfully Inserted...', null, \core\output\notification::NOTIFY_SUCCESS);
		                }
           }
         


        }


        $mform->display();

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