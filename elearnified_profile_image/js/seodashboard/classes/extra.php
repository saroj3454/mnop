<?php 
namespace local_seodashboard;
use html_writer;
use local_seodashboard\extra;
defined('MOODLE_INTERNAL') || die();
class extra
{

public function secondcategoryseodata($id=null){
		  global $DB;

			$data=$DB->get_records_sql("SELECT ss.*,scs.id as seoid,scs.title as seotitle,scs.keywords as seokeywords,scs.author as seoauthor,scs.description as seodescription,scs.slug as seoslug,scs.status as seostatus FROM `mo_searchda_secondc` as ss LEFT JOIN mo_searchda_categories_secondseo as scs on ss.id=scs.second_id where ss.status='0' AND ss.categoriesid='".$id."'");
			
			$rdata=array_values($data);
		
		return $rdata;
	}








public function firstcategoryseodata($id=null){
		  global $DB;
		if (is_null($id)) {
			$data=$DB->get_records_sql("SELECT sc.*,scf.id as seoid,scf.title as seotitle,scf.keywords as seokeywords,scf.author as seoauthor,scf.description as seodescription,scf.slug as seoslug,scf.status as seostatus FROM `mo_searchda_categories` as sc LEFT JOIN mo_searchda_categories_firstseo as scf on sc.id=scf.first_id where sc.status='0'");
			
			$rdata=array_values($data);
		}else{
			$rdata=$DB->get_record('searchda_categories',array('id'=>$id,'status'=>'0'));
		}
		return $rdata;
	}



	public function firstcategory($id=null){
		  global $DB;
		if (is_null($id)) {
			$data=$DB->get_records('searchda_categories',array('status'=>'0'));
			$rdata=array_values($data);
		}else{
			$rdata=$DB->get_record('searchda_categories',array('id'=>$id,'status'=>'0'));
		}
		return $rdata;
	}
	public function secondcategory($cid=null,$id=null){
		  global $DB;
		if (!is_null($cid)) {
			$data=$DB->get_records('searchda_secondc',array('categoriesid'=>$cid,'status'=>'0'));
		}else{
			$data=$DB->get_record('searchda_secondc',array('id'=>$id,'status'=>'0'));
		}
		return array_values($data);
	}
	public function thirdcategory($id=null,$cid=null){
		  global $DB;
		if (!is_null($id)) {
			$data=$DB->get_records('searchda_third',array('subid'=>$id,'status'=>'0'));
		}else{
			$data=$DB->get_record('searchda_third',array('id'=>$cid,'status'=>'0'));
		}
		return array_values($data);
	}
	public function firstcategory_option($id=null){
		  global $DB;
		 // $data=html_writer::tag('option','All Category',array('value'=>'all_categories','selected'=>true));
		  $data="";
		foreach (self::firstcategory() as  $value) {
			if(!is_null($id) && $value->id==$id){
				$attribute=array('value'=>$value->id,'selected'=>true);
			}else{
				$attribute=array('value'=>$value->id);
			}
			
			$data.=html_writer::tag('option',$value->title , $attribute);

		}
		return $data;
	}
	public function secondcategory_option($cid=null,$id=null){
		  global $DB;
		 $data="";
		foreach (self::secondcategory($cid) as  $value) {
			if(!is_null($id) && $value->id==$id){
				$attribute=array('value'=>$value->id,'selected'=>true);
			}else{
				$attribute=array('value'=>$value->id);
			}
			
			$data.=html_writer::tag('option',$value->title , $attribute);

		}
		return $data;
	}


	public function thirdcategory_option($id=null,$cid=null){
		  global $DB;
		 $data="";
		foreach (self::thirdcategory($id) as  $value) {
			if(!is_null($cid) && $value->id==$cid){
				$attribute=array('value'=>$value->id,'selected'=>true);
			}else{
				$attribute=array('value'=>$value->id);
			}
			
			$data.=html_writer::tag('option',$value->title , $attribute);

		}
		return $data;
	}

	
}