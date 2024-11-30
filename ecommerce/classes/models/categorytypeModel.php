<?php
class categorytypeModel
{
    public function __construct($formdata = [])
    {
    }
      /**
     * @param string $typedata 
     * @return array */
    public function saveProductCategory($typedata){
    	global $DB, $USER;
        if(!empty($typedata->id)){
            $typedata->modifiedby = $USER->id;
            $typedata->modifieddate = time();
            return $DB->update_record("categorytype", $typedata);
        } else {
            $typedata->createdby = $USER->id;
            $typedata->createddate = time();
        	return $DB->insert_record("categorytype", $typedata);
        }
    }
  /**
     * @return array */
    public function getall(){
    	global $DB;
    	return $DB->get_records("categorytype", array(), 'name', "*");
    }
       /**
     * @param string $id \
     * @return string $product 
     */
    public function getbyID($id=null){
        global $DB;
        $product = $DB->get_record("categorytype", array("id"=>$id));
        return $product;
    }
 /**
 * @param string $cattypeids 
 * @return bool */
    public function delete($cattypeids=null){
         global $DB;  
        if(!empty ($cattypeids)){
            try {
                $allcattype = explode(",", $cattypeids);
                foreach ($allcattype as $key => $p) {
                    $DB->delete_records("categorytype", array("id"=>$p)); 
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

}

