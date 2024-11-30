<?php
require_once('../../config.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("HTTP/1.0 200 Successfull operation");
$getpatameter=json_decode(file_get_contents('php://input',True),true);
$functionname = null;
$args = null;
if(is_array($getpatameter)){
    $functionname = $getpatameter['wsfunction'];
    $args = $getpatameter['wsargs'];
}
class APIManager {
    public $status = 0; 
    public $message = "Error";
    public $data = null;
    public $code = 404;
    public $error = array(
        "code"=> 404,
        "title"=> "Server Error.",
        "message"=> "Server under maintenance"
    );
    function __construct() {
        $this->code = 404;
        $this->error = array(
            "code"=> 404,
            "title"=> "Server Error..",
            "message"=> "Missing functionality"
        );
    }
    private function sendResponse($data) {
        $this->status = 1;
        $this->message = "Success";
        $this->data = $data;
        $this->code = 200;
        $this->error = null;
    }
    private function sendError($title, $message, $code=400) {
        $this->status = 0;
        $this->message = "Error";
        $this->data = null;
        $this->code = $code;
        $this->error = array(
            "code"=> $code,
            "title"=> $title,
            "message"=> $message
        );
    }
    public function validatetoken($args){
        global $CFG;
        $this->sesskey = sesskey();
        if($args->sesskey == sesskey()){
            return true;
        } else {
            $this->sendError("error","request not authenticated");
            return false;
        }
    }

    public function thirdcategory($data){
    	 global $DB,$CFG;
    	

    	$dataquery = $DB->get_records('searchda_third',array('subid'=>$data->catid));
        $r='';
        foreach ($dataquery as $value) {
            if(!empty($data->thirdid)){
                if($value->id==$data->thirdid){
                    $selected="selected";
                }else{
                    $selected="";
                }
            }

        		$r.="<option value='".$value->id."' ".$selected.">".$value->title."</option>";
        }

         $this->sendResponse($r);  

    }
public function loadState($data){
global $DB,$CFG;
 $dataquery = $DB->get_records_sql("SELECT * FROM {exam_state} where status='Active' and deleted='1'");
$rdata='<option value="" disabled selected hidden >Select State</option>';
foreach ($dataquery as $value) {
    $rdata.="<option value='".$value->id."'>".$value->state_title."</option>";
}


$this->sendResponse($rdata);
}
 public static function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
     }
public function loadCity($data){
    global $DB,$CFG;
    $state_id=$data->stateid;
    $action=$data->action;
    if($action=='city'){
         $dataquery = $DB->get_records_sql("SELECT ct.*,st.state_title FROM {exam_city}  as ct left join {exam_state} as st on ct.state_id=st.id  where ct.state_id='".$state_id."' and ct.deleted='1' ORDER BY ct.id DESC");
          $stateq = $DB->get_record_sql("SELECT * FROM {exam_state} where id='".$state_id."'");
         $rdata='<div class="cityv"> <a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=city&state_id='.$state_id.'" class="btn bg-primary" id="yui_3_17_2_1_1670917809522_409"> Add '.$stateq->state_title.' Other City</a></div>

         <div class="float-right">
<label>Search</label>
<input type="search" id="inputsearch">
</div>


<input type="hidden" value="city" id="action">
<div class="state"> '.$stateq->state_title.'  All City</div>
         <table id="examplesearch" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <td>Title</td>

  
         <th>Action</th>
      </tr>
   </thead>
   <tbody>';
         foreach ($dataquery as $key) {
             $rdata.='<tr id="row'.$key->id.'">
             <td>'.$key->name.'</td>
             <td>
                <ul class="mmenu">
                   <li><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=city&id='.$key->id.'"  class=\'edit editmodal\'  ><i class="fas fa-pen"></i></a></li>
                   <li><a href="javascript:void(0)" exam-name="'.$key->name.'" exam-city="'.$key->id.'"  class=\'examcitystatus\' ><i class="fas '.(($key->status=='Active')?'fa-eye':'fa-eye-slash').'" id="d'.$key->id.'"></i></a></li>
                   <li><a href="javascript:void(0)" class=\'citydelete\' exam-name-delete="'.$key->name.'" exam-city="'.$key->id.'" ><i class="fas fa-trash-alt" ></i></a></li>
                </ul>
             </td>
          </tr>';
         }
         $rdata.='<tr></tbody>
   <tfoot>
      <tr>
         <td>Title</td>
             <th>Action</th>
      </tr>
   </tfoot>
</table>';
    }else{
        $dataquery = $DB->get_records_sql("SELECT ds.*,st.state_title FROM {exam_district} as ds left join {exam_state} as st on ds.state_id=st.id  where ds.state_id='".$state_id."' and ds.deleted='1' ORDER BY ds.id DESC");
           $stateq = $DB->get_record_sql("SELECT * FROM {exam_state} where id='".$state_id."'");
         $rdata='<div class="districtv"><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&amp;state_id='.$state_id.'" class="btn btn-primary"> Add '.$stateq->state_title.' Other District</a></div>
          <div class="float-right">
<label>Search</label>
<input type="search" id="inputsearch">
</div>
         <input type="hidden" value="district" id="action">
         <div class="state"> '.$stateq->state_title.'  All District</div>
<table id="examplesearch" class="table table-striped table-bordered" style="width:100%">
   <thead>
      <tr>
         <td>Title</td>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>';
         foreach ($dataquery as $key) {
                  
                  
             $rdata.='<tr id="row'.$key->id.'">
             <td>'.$key->district_title.'</td>
             <td>
                <ul class="mmenu">
                   <li><a href="'.$CFG->wwwroot.'/local/seodashboard/state_edit.php?action=district&id='.$key->id.'"  class=\'edit editmodal\'  ><i class="fas fa-pen"></i></a></li>
                   <li><a href="javascript:void(0)" exam-district-name="'.$key->district_title.'" exam-district="'.$key->id.'"  class=\'districtstatus\'><i class="fas '.(($key->district_status=='Active')?'fa-eye':'fa-eye-slash').'" id="d'.$key->id.'"></i></a></li>
                   <li><a href="javascript:void(0)" class=\'citydelete\' exam-name-delete="'.$key->district_title.'" exam-city="'.$key->id.'" ><i class="fas fa-trash-alt" ></i></a></li>
                </ul>
             </td>
          </tr>';
         }
         $rdata.='<tr></tbody>
   <tfoot>
      <tr>
         <td>Title</td>
             <th>Action</th>
      </tr>
   </tfoot>
</table>';



    }
    // $dataquery = $DB->get_records_sql("SELECT * FROM `mo_exam_city`");
      $this->sendResponse($rdata); 
}
public function statusdelete($data){
	global $DB,$CFG;
	$dataid=$data->dataid;
	$action=$data->action;
	$rdata=array();
	$dataobject=new stdClass();
	$dataobject->id=$dataid;
	$dataobject->deleted=0;
	if($action=='city'){  	
	$DB->update_record('exam_city',$dataobject);
	}else{
	$DB->update_record('exam_district',$dataobject);
	}
	$rdata=array();
	 $rdata['status']='success';
    $this->sendResponse($rdata); 
} 
public function statusupdate($data){
    global $DB,$CFG;
    $dataid=$data->dataid;
    $action=$data->action;
     $rdata=array();
    if($action=='city'){
        $dataobject=new stdClass();
        $dataobject->id=$dataid;
        $sdata= $DB->get_record('exam_city',array('id'=>$dataid));
        if($sdata->status=='Active'){
          $sdata->status='not_active';  
          $rdata['icon']='fa-eye-slash';
          $rdata['iconremove']='fa-eye';
        }else{
        $sdata->status='Active';  
        $rdata['icon']='fa-eye';
        $rdata['iconremove']='fa-eye-slash';
        }
        //print_r($sdata);
         $DB->update_record('exam_city', $sdata);
    }else{
        $dataobject=new stdClass();
        $dataobject->id=$dataid;
        $sdata= $DB->get_record('exam_district',array('id'=>$dataid));
        if($sdata->district_status=='Active'){
          $sdata->district_status='not_active';
          $rdata['icon']='fa-eye-slash'; 
          $rdata['iconremove']='fa-eye'; 
        }else{
        $sdata->district_status='Active'; 
        $rdata['icon']='fa-eye'; 
        $rdata['iconremove']='fa-eye-slash';
        }
        
        $DB->update_record('exam_district',$sdata);
    }
   
    $rdata['status']='success';
    $this->sendResponse($rdata); 
}


public function secondcategorycslug($data){
        global $DB,$CFG;
        // if(!empty($data->dataseoid)){
        //     $select=" and scf.id='".$data->dataseoid."'";
        // }
        $dataquery = $DB->get_record_sql("SELECT ss.*,scs.id as seoid,scs.title as seotitle,scs.keywords as seokeywords,scs.author as seoauthor,scs.description as seodescription,scs.slug as seoslug,scs.status as seostatus FROM `mo_searchda_secondc` as ss LEFT JOIN mo_searchda_categories_secondseo as scs on ss.id=scs.second_id where ss.status='0' AND ss.id='".$data->catid."'");
        if(!empty($dataquery->seoslug)){
            $slug=$dataquery->seoslug;
        }else{
             $slug=$dataquery->title;
        }

        if(!empty($dataquery->seotitle)){
            $title=$dataquery->seotitle;
        }else{
             $title=$dataquery->title;
        }

        $data=array();
        $data['title']=$title;
        $data['slug']=self::urlslug($slug);
        $data['keywords']=$dataquery->seokeywords;
        $data['author']=$dataquery->seoauthor;
        $data['description']=$dataquery->seodescription;
        $data['seoid']=$dataquery->seoid;

         $this->sendResponse($data); 
    }
public function loadfirstslug($data){
        global $DB,$CFG;
        if(!empty($data->dataseoid)){
            $select=" and scf.id='".$data->dataseoid."'";
        }
        $dataquery = $DB->get_record_sql("SELECT sc.*,scf.id as seoid,scf.title as seotitle,scf.keywords as seokeywords,scf.author as seoauthor,scf.description as seodescription,scf.slug as seoslug,scf.status as seostatus FROM `mo_searchda_categories` as sc LEFT JOIN mo_searchda_categories_firstseo as scf on sc.id=scf.first_id  where sc.id='".$data->first_id."' and sc.status='0' ".$select."");
        if(!empty($dataquery->seoslug)){
            $slug=$dataquery->seoslug;
        }else{
             $slug=$dataquery->title;
        }

        if(!empty($dataquery->seotitle)){
            $title=$dataquery->seotitle;
        }else{
             $title=$dataquery->title;
        }

        $data=array();
        $data['title']=$title;
        $data['slug']=self::urlslug($slug);
        $data['keywords']=$dataquery->seokeywords;
        $data['author']=$dataquery->seoauthor;
        $data['description']=$dataquery->seodescription;
        $data['seoid']=$dataquery->seoid;

         $this->sendResponse($data); 
    }


    public function thirdcategoryslug($data){
        global $DB,$CFG;
        $dataquery = $DB->get_record_sql("SELECT sc.*,cu.slug,cu.title as seotitle,cu.keywords,cu.author,cu.description,cu.id as seoid FROM {searchda_third} as sc LEFT JOIN {searchda_categories_seo} as cu on sc.id=cu.th_id where sc.id='".$data->catid."'");
        if(!empty($dataquery->slug)){
            $slug=$dataquery->slug;
        }else{
             $slug=$dataquery->title;
        }

        if(!empty($dataquery->seotitle)){
            $title=$dataquery->seotitle;
        }else{
             $title=$dataquery->title;
        }

        $data=array();
        $data['title']=$title;
        $data['slug']=self::urlslug($slug);
        $data['keywords']=$dataquery->keywords;
        $data['author']=$dataquery->author;
        $data['description']=$dataquery->description;
        $data['seoid']=$dataquery->seoid;

         $this->sendResponse($data); 
    }
     

}
$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
        if(is_array($args)){$args = (object)$args;}
        if($baseobject->validatetoken($args)){
            $baseobject->$functionname($args);
        }
}
echo json_encode($baseobject);