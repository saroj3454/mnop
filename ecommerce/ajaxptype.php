<?php
require_once('../../config.php');
require_once($CFG->dirroot."/local/ecommerce/classes/models/productModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/producttypeModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/categorytypeModel.php");
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
   
   
    public function openAddProduct($args){
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        $PTM = new producttypeModel();
        $CTM = new categorytypeModel();
        $allproducts = array_values($PM->getall());
        $allproducttype = array_values($PTM->getall());
        $allcategorytype = array_values($CTM->getall());
        if($args->id){
            $id = $args->id;
            $product = $PM->getproduct($id);
        }
        $allcompany = array_values($DB->get_records("company", array(), 'name', 'id, name'));
        foreach ($allcompany as $key => $cmp) {
            $allcompany[$key]->selected = (($cmp->id == $product->companyid)?"selected":'');
        }
        foreach ($allcategorytype as $key => $ctype) {
            $allcategorytype[$key]->selected = (($ctype->id == $product->category)?"selected":'');
        }
        foreach ($allproducttype as $key => $ptype) {
            $allproducttype[$key]->selected = (($ptype->id == $product->type)?"selected":'');
        }
        $newpdata = array(
            "prodtype" => $allproducttype,
            "categorytype" =>$allcategorytype,
            "allcompany" =>$allcompany,
            "promocode" =>array(
            	array(
            		"id"=>"code1",
            		"name"=>"code1",
                    "selected"=>($product->promocode == "code1"?"Selected":''),
            	),
            	array(
            		"id"=>"code2",
            		"name"=>"code2",
                    "selected"=>($product->promocode == "code2"?"Selected":''),
            	),
            	array(
            		"id"=>"code3",
            		"name"=>"code3",
                    "selected"=>($product->promocode == "code3"?"Selected":''),
            	),
            ),
            "allprod" =>$allproducts, 
            "id" =>($product->id?$product->id:0), 
            "popular" =>($product->popular?$product->popular:0), 
            "productid" =>($product->productid?$product->productid:""), 
            "name" =>($product->name?$product->name:""), 
            "type" =>($product->type?$product->type:""), 
            "category" =>($product->category?$product->category:""), 
            "description" =>($product->description?$product->description:""), 
            "relatedproducts" =>($product->relatedproducts?$product->relatedproducts:""), 
            "price" =>($product->price?$product->price:""), 
            "images" =>($product->images?$product->images:array()), 
        );
        $this->allcompany = $allcompany;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/new_product', $newpdata); 
        $this->sendResponse($data);
    }
   
     public function openAddProductType($args){
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PTM = new producttypeModel();
        if($args->id){
            $id = $args->id;
            $producttype = $PTM->getbyID($id);
        }

        $newpdata = array(
            "id" =>($producttype->id?$producttype->id:0), 
            "name" =>($producttype->name?$producttype->name:""), 
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/producttype/editproducttype', $newpdata); 
        $this->sendResponse($data);
    }
    //                                                                                                                ///var/www/html/elearnified1/local/ecommerce/templates/productcategory
    public function openAddProductcat($args){
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PTM = new categorytypeModel();
        if($args->id){
            $id = $args->id;
            $producttype = $PTM->getbyID($id);
        }

        $newpdata = array(
            "id" =>($producttype->id?$producttype->id:0), 
            "name" =>($producttype->name?$producttype->name:""), 
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/productcategory/editproductcategory', $newpdata); 
        $this->sendResponse($data);
    }
   
   
    public function openViewProduct($args){
        global $CFG, $PAGE, $OUTPUT, $DB;
        $PM = new productModel();
        if($args->id){
            $id = $args->id;
            $product = $PM->viewproduct($id);
        }
        $newpdata = array(
            "id" =>($product->id?$product->id:0), 
            "productid" =>($product->productid?$product->productid:""), 
            "name" =>($product->name?$product->name:""), 
            "type" =>($product->type?$product->type:""), 
            "ptname" =>($product->ptname?$product->ptname:""), 
            "companyname" =>($product->companyname?$product->companyname:""), 
            "categoryname" =>($product->categoryname?$product->categoryname:""), 
            "category" =>($product->category?$product->category:""), 
            "description" =>($product->description?$product->description:""), 
            "relatedproducts" =>($product->relatedproducts?$product->relatedproducts:""), 
            "price" =>($product->price?$product->price:""), 
            "images" =>($product->images?$product->images:array()), 
        );
        $this->product = $product;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/view_product', $newpdata); 
        $this->sendResponse($data);
    }
   
   
    public function confrmationbox($args){
        global $CFG, $PAGE, $OUTPUT;
        $newpdata = array(
            "itemid"=>($args->itemid?$args->itemid:0),
            "heading"=>($args->heading?$args->heading:"Confirm"),
            "subheading"=>($args->subheading?$args->subheading:""),
            "description"=>($args->description?$args->description:""),
            "action"=>($args->action?$args->action:""),
            "data"=>json_encode($args)
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/confirmation-box', $newpdata); 
        $this->sendResponse($data);
    }


    public function confrmationbox1($args){
        global $CFG, $PAGE, $OUTPUT;
        $newpdata = array(
            "itemid"=>($args->itemid?$args->itemid:0),
            "heading"=>($args->heading?$args->heading:"Confirm"),
            "subheading"=>($args->subheading?$args->subheading:""),
            "description"=>($args->description?$args->description:""),
            "action"=>($args->action?$args->action:""),
            "data"=>json_encode($args)
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/confirmation-box', $newpdata); 
        $this->sendResponse($data);
    }
        // $PTM = new producttypeModel();
        // if($args->id){
        //     $id = $args->id;
        //     $producttype = $PTM->getbyID($id);
        //     if($PTM->delproducttype($id)){
        //         $this->sendResponse("Deleted Successfully");
        //     } else {
        //         $this->sendError("Operation Failed", "Failed to delete product");
        //     }
        // }   
        //  else {
        //     $this->sendError("Operation Failed", "Missing paramters");
        // }
    // }
   
   
    public function openstatusmessage($args){
        global $CFG, $PAGE, $OUTPUT;
        $template = $args->template?$args->template:"eventstatus";
        $status = $args->status?$args->status:"success";
        $message = $args->message?$args->message:"default message";
        $templatedata = array(
            "status"=>$status,
            "message"=>$message,
        );
        $data = $OUTPUT->render_from_template('local_ecommerce/'.$template, $templatedata); 
        $this->sendResponse($data);
    }
    public function addmorfile($args){
        global $CFG, $PAGE, $OUTPUT;
        $data = $OUTPUT->render_from_template('local_ecommerce/admin/fileselector', array()); 
        $this->sendResponse($data);
    }
    public function saveNewProduct($args){
        global $CFG, $PAGE, $OUTPUT;
        $PM = new productModel();
        if($PM->saveProduct($args)){
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function saveNewProducttype($args){
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new producttypeModel();
        if($PTM->save($args)){
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function deleteProductType($args){
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new producttypeModel();
        if($PTM->delete($args->id)){
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    public function deleteProductType1($args){
        global $CFG, $PAGE, $OUTPUT;
        $PCM = new categorytypeModel();
        if($PCM->delete($args->id)){
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    
    public function saveNewProductcategory($args){
        global $CFG, $PAGE, $OUTPUT;
        $PTM = new categorytypeModel();
        if($PTM->saveProductCategory($args)){
            $this->sendResponse("Updated successfully");
        } else {
            $this->sendError("Operation Failed", "Failed to save product");
        }
    }
    
    public function filterProducts($args){
        global $OUTPUT;
        try {
            $PM = new productModel();
            $data = $PM->filterall($args);
            $settings = array(
                "products"=>$data['data'],
                "total"=>$data['total']
            );
            $this->query = $data['query'];
            $this->filtersdata = $data['filtersdata'];
            $maincontaine = $OUTPUT->render_from_template('block_elearnified_catalogue/filtereddata', $settings);
            $this->sendResponse(array("maincontaine"=>$maincontaine));
        } catch (Exception $e) {
            $this->sendError("Operation Failed", "Failed to save product");
            
        }
    }
}
$baseobject = new APIManager();
if (method_exists($baseobject, $functionname)) {
        if(is_array($args)){$args = (object)$args;}
        $baseobject->$functionname($args);
}
echo json_encode($baseobject);