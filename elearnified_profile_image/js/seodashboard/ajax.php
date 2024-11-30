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
     public function urlslug($string) {
        $slug=preg_replace('/[^a-z0-9-]+/','-', strtolower(trim($string)));
        return $slug;
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