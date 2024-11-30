<?php
class producttypeModel
{
    public function __construct($formdata = [])
    {
    }
       /**
     * @param string $typedata 
     * @return array */
    public function save($typedata){
    	global $DB, $USER;
        if(!empty($typedata->id)){
            $typedata->modifiedby = $USER->id;
            $typedata->modifieddate = time();
            return $DB->update_record("producttype", $typedata);
        } else {
            $typedata->createdby = $USER->id;
            $typedata->createddate = time();
        	return $DB->insert_record("producttype", $typedata);
        }
    }
 
     public function delproducttype($id){
        // global $DB;  
        // if(!empty ($productid)){
        //     return $DB->delete_records("producttype", array("id"=>id)); 
        // }
        // return false;
    }
       /**
     * @return array 
     */
    public function getall(){
    	global $DB;
    	return $DB->get_records("producttype", array(), 'name', "*");
    }
     /**
     * @param string $id 
     * @return array 
     */
    public function getbyID($id=null){
        global $DB;
        $product = $DB->get_record("producttype", array("id"=>$id));
        return $product;
    }
/**
 * @param string $producttpeids 
 * @return bool 
 * @return bool 
 */

    public function delete($producttpeids=null){
        global $DB;  
        if(!empty ($producttpeids)){
            try {
                $allproducttypeids = explode(",", $producttpeids);
                foreach ($allproducttypeids as $key => $p) {
                    $DB->delete_records("producttype", array("id"=>$p)); 
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
