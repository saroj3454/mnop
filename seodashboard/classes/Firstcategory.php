<?php

namespace local_seodashboard;
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
use stdClass;
use html_writer;
use core_course_list_element;
use moodle_url;
use context_course;
use context_coursecat;
use local_seodashboard\extra;
defined('MOODLE_INTERNAL') || die();
class Firstcategory 
{
	public $message=array();
    function __construct()
    {

        $this->templateContext = new stdClass;

        $this->seodashboardfirstcategory();
        $this->seodashboardfirstcategorystatus();
        echo $this->css();
        echo $this->index();
        echo $this->js_script();

    }
    public static function seodashboardfirstcategorystatus(){
    	 global $DB,$CFG,$OUTPUT,$PAGE; 
    	if(!empty($_GET['status'])){
    		$data=$DB->get_record("searchda_categories_firstseo",array('id'=>$_GET['status']));
    		$data->modifiedtime=time();

    		if($data->status=='1'){
    			$data->status="0";
    		}else{
    			$data->status="1";
    		}
    		// print_r($data);
    		// die();
    		$DB->update_record('searchda_categories_firstseo',$data);
    		redirect(new moodle_url("/local/seodashboard/firstcategory.php"), 'Sucessfully data updated', null, \core\output\notification::NOTIFY_SUCCESS);

    	}

    	if(!empty($_GET['delete'])){
    		

			 	 $sqlquerydataa = "DELETE FROM {searchda_categories_firstseo} where id='".$_GET['delete']."'";
		         $DB->execute($sqlquerydataa);

    		redirect(new moodle_url("/local/seodashboard/firstcategory.php"), 'Sucessfully data updated', null, \core\output\notification::NOTIFY_SUCCESS);

    	}





    }
    public function css(){
         $attribute=array('rel'=>'stylesheet','href'=>'https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css');
         return $css=html_writer::tag("link",'',$attribute);

    }
    // public static function searchda_categories_seo(){
    // 	 global $DB,$CFG,$OUTPUT,$PAGE; 
    // 	 $sec="";
    // 	 if(!empty($_GET['cid'])){
    // 	 	$sec="and ss.id=".$_GET['cid'];
    // 	 }
    // 	 if(!empty($_GET['thid'])){
    //     	$sec.=" and st.id=".$_GET['thid'];
    //     }

    // 	 if(!empty($_GET['id'])){
    // 	 	 $sql="SELECT seo.*,ss.id as currentsecondid  FROM {searchda_secondc} as ss left join {searchda_third} as st on ss.id=subid INNER join {searchda_categories_seo} as seo on st.id=seo.th_id WHERE ss.`categoriesid` ='".$_GET['id']."' ".$sec." order by ss.id desc";

    // 	 }
    // 	 if(!empty($_GET['id'])){ 
    // 	   $rdata=$DB->get_records_sql($sql);
    // 	   return array_values($rdata);
    // 	}
    	   
    // }
    public static function seodashboardfirstcategory(){
    	 global $DB,$CFG,$OUTPUT,$PAGE; 
    	if(!empty($_POST['submit'])){
    		$data=new stdClass();
    		// $data->second_id=$_POST['secondid'];
    		$data->first_id=$_POST['first_id'];
    		$data->title=$_POST['title'];
    		$data->keywords=$_POST['keywords'];
    		$data->author=$_POST['author'];
    		$data->description=$_POST['description'];
    		$data->slug=$_POST['url'];
    		
    		if(empty($_POST['seoid'])){
    			$data->createdtime=time();
    			$DB->insert_record('searchda_categories_firstseo',$data);
    		}else{
    			$data->modifiedtime=time();
    			$data->id=$_POST['seoid'];
    			$DB->update_record('searchda_categories_firstseo',$data);
    		}
    		 redirect(new moodle_url("/local/seodashboard/firstcategory.php"), 'Sucessfully data updated', null, \core\output\notification::NOTIFY_SUCCESS);
    		

    	}
    	
    }
    public function index(){
        global $CFG,$OUTPUT,$PAGE; 
        $this->extra = new extra;  
          $currenturlid="";
        $id=optional_param('id','null', PARAM_TEXT);
        if(!empty($id) && $id!='null'){
             $data=$this->extra->firstcategory($id);
            $title=$data->title;
            $currenturlid=$id;
            $addbtton='<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><i  class="fa fa-plus d_plus"></i>  Add</button>';
        }else{
            $title="";
            $addbtton="";
        }
        
 
        $attribute=array('rel'=>'stylesheet','href'=>$CFG->wwwroot.'/local/seodashboard/assest/style.css');
        $css=html_writer::tag("link",'',$attribute);




        $setting=array('filterdata'=>$this->extra->firstcategoryseodata(),'menurecord'=>$this->recorddispay(),'currenturlid'=>$currenturlid,'addbutton'=>$addbtton,"style"=>$css,'title'=>$title);

       return $OUTPUT->render_from_template('local_seodashboard/seofirstcategory', $setting);
    }
    public function menu_nav(){
    	 global $DB,$CFG,$PAGE;
    	 $ldata=array();
    	 if(!empty($_GET['id'])){
    	 	$rdata=$DB->get_record('searchda_categories',array('id'=>$_GET['id'],'status'=>'0'));
    	 	$ldata['menu1']=$rdata->title;
    	 }
    	 
    	 return $ldata;

    }
    public static function recorddispay(){
    	global $CFG,$PAGE;
    	$menu="";
    	$i = 0;
    	$array=self::menu_nav();
		$len = count($array);
    	foreach($array as $value){
				$menu.=html_writer::tag('li',$value,array('class'=>'menu_record'));
    	}
    	$span=html_writer::tag('span','Records Display',array('class'=>'record_data'));
				$menu.=html_writer::tag('li',$span,array('class'=>'menu_record'));

    	
    	return $menu;
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