<?php
class promocodeModel
{
    public function __construct(){

    }
        /**
     * @return array */
    public function getall(){
        global $DB, $CFG;
        $allpromocode = $DB->get_records("promocode", array(), 'promoid', "*");
        foreach ($allpromocode as $key => $promocode) {
            $allpromocode[$key] = $promocode;
        }
        return $allpromocode;
    }
    /**
     * @param string $id 
     * @return array */
    public function getbyID($id=null){
        global $DB;
        $promocode = $DB->get_record("promocode", array("id"=>$id));
        return $promocode;
    }

       /**
     * @param string $promocode 
     * @return array */
    public function save($promocode){
    	global $DB, $USER;
        $promocode->startdate = strtotime($promocode->startdate);
        $promocode->enddate = strtotime($promocode->enddate);
    	if(!empty($promocode->id)){
    		$promocode->modifiedby = $USER->id;
    		$promocode->modifieddate = time();
    		return $DB->update_record("promocode",$promocode);
    	} else {
    		$promocode->createdby = $USER->id;
    		$promocode->createddate = time();
    		return $DB->insert_record("promocode",$promocode);
    	}
    }
    /**
     * @param string $promocodeids
     * @return bool
     * @return bool */
     public function delete($promocodeids=null){
        global $DB;  
        if(!empty ($promocodeids)){
            try {
                $allpromocode = explode(",", $promocodeids);
                foreach ($allpromocode as $key => $p) {
                    $DB->delete_records("promocode", array("id"=>$p)); 
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
       
    }
    /**
     * @param string $promocodeids
     * @return bool
     * @return bool */
    public function deleteproduct($productids){
        global $DB;  
        if(!empty ($productids)){
            try {
                $allproduct = explode(",", $productids);
                foreach ($allproduct as $key => $p) {
                    $DB->delete_records("product", array("id"=>$p)); 
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}

