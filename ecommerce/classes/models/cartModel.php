<?php
require_once($CFG->dirroot."/local/ecommerce/classes/models/courseModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/productModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/producttypeModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/categorytypeModel.php");
require_once($CFG->dirroot."/local/ecommerce/classes/models/promocodeModel.php");
class cartModel
{
    public $cartid = 0;
    public $cart = null;
    public $allitems = array();
    public function __construct($cartid = null)
    {
        global $DB, $USER;
        if(!empty($USER->id)){
            $_SESSION['cartid'] = self::getCart($cartid);
        } else if(!empty($cartid)){
            $this->cartid = $cartid;
            $_SESSION['cartid'] = $cartid;
        } else if(isset($_SESSION['cartid']) && !empty($_SESSION['cartid'])){
            $this->cartid = $_SESSION['cartid'];
        } else {
            $_SESSION['cartid'] = self::createCart();
        }
    }

    /**
     * @param string $cartitem
     * @param mixed $DB
     * @return array
     * @return bool
     */
    public function add($args)
    {
        global $DB, $USER;
        if(empty($args->quantity)){ $args->quantity = 1; }
        $cartitem = $DB->get_record("cartitem", array("cartid"=>$this->cartid, "itemid"=>$args->itemid, "producttype"=>$args->producttype));
        $promocode = $DB->get_record("promocode", array("id"=>$args->promocode));
        if(!empty($cartitem)){
            if($cartitem->deleted){
                $cartitem->quantity = 0;
                $cartitem->deleted = 0;
            }
            if($promocode){
                $cartitem->appliedpromo = $promocode->id;
                $cartitem->appliedpromodata = json_encode($promocode);
            }
            $cartitem->quantity += $args->quantity;
            $cartitem->modifieddate = time();
            return $DB->update_record("cartitem", $cartitem);
        } else {
            $cartitem = new stdClass();
            $cartitem->cartid = $this->cartid;
            $cartitem->producttype = $args->producttype;
            $cartitem->itemid = $args->itemid;
            $cartitem->quantity = $args->quantity;
            $cartitem->addedby = (!empty($this->cart->createdby)?$this->cart->createdby:0);
            $cartitem->addeddate = time();
            $cartitem->deleted = 0;
            if($promocode){
                $cartitem->appliedpromo = $promocode->id;
                $cartitem->appliedpromodata = json_encode($promocode);
            }
            return $DB->insert_record("cartitem", $cartitem);
        }
        return false;
    }
    /**
     * @param string $DB
     * @return array
     */
    public function remove($args) {
        global $DB, $USER;
        return $DB->delete_records("cartitem", array("id"=>$args->id));
    }

      /**
     * @param string $cart
     * @return bool
     */
    public function getcartdetails($cartid){
        global $DB, $USER;
        if($cart = $DB->get_record("cart", array("id"=>$cartid))){
            $cart->allitems = self::getallitems($cart->id);
            return $cart;
        }
        return null;
    }

    /**
     * @param string $item
     * @param string $cartid
     * @return array $allitems
     * */
  
    public function getallitems($cartid = 0){
        global $DB, $USER;
        if(empty($cartid)){
            $cartid = $this->cartid;
        }

        $allitems = $DB->get_records_sql("select * from mdl_cartitem where cartid='".$cartid."' and deleted='0' ", array("cartid"=>$cartid, "deleted"=>0));
        //$allitems = $DB->get_records("cartitem", array("cartid"=>$cartid, "deleted"=>0));
        foreach ($allitems as $key => $item) {

            $item->updatequantity =false;
            $item->itemname ="";
            $item->itemtype =""; 
            $item->itemprice =0; 
            $item->finalprice =0; 
            $item->promocode =0; 
            $item->mainimage = [
                'url' => '',
                'filename' => '',
                'pathnamehash' => '',
            ];
            $item->disablenext = 0;
            $item->discount = 0;
            if($item->producttype == 0){
                $item->updatequantity = true;
                $PM = new productModel();
                $product = $PM->viewproduct($item->itemid);
                $item->itemname =($product->name?$product->name:"");
                $item->itemtype ="Mechandise"; 
                $item->itemprice =($product->price?$product->price:0); 
                $item->promocode =($product->promocode?$product->promocode:""); 
                $item->mainimage =($product->images?$product->images[0]:null); 
            } else {
                $item->disablenext = 1;
                $coursedata = $DB->get_record("course", array("id"=>$item->itemid));
                try {
                    $data = new stdClass();
                    $CM = new courseModel($coursedata);
                    $format = course_get_format($coursedata);
                    $formatoption = $format->get_format_options();
                    $item->itemname = $coursedata->fullname;
                    $item->itemtype = "Course";
                    $item->itemprice = $formatoption['courseprice'];
                    $item->promocode = $formatoption['coursepromocode'];
                    $item->mainimage['url'] = $CM->getImage();
                    $current_time = time();
                    $ensql = "SELECT c.* FROM {user_enrolments}  ue  JOIN {enrol} as e on ue.enrolid=e.id  JOIN {course}  c on e.courseid=c.id WHERE c.id=?  AND ue.userid=? AND e.roleid = ? AND ue.status=? AND (ue.timeend >= ?  OR  ue.timeend = ? )  ";
                   $enroldata = $DB->get_record_sql($ensql, array($coursedata->id,$USER->id, 5, 0, $current_time, 0));
                    if(!empty($enroldata)){
                        $item->purchasedata='disable';
                    }

                } catch (Exception $e) {
                    continue;
                }
                $item->quantity = 1;
            }
            $item->finalprice = $item->itemprice;
            $item->nopromocoed = ($item->promocode?true:false);
            $item->havepromocode = !$item->nopromocoed;
            // $item->appliedpromo = false;
            if(!empty($item->appliedpromo)){
                $PCM = new promocodeModel();
                if($pcodedata = $PCM->getbyID($item->appliedpromo)){
                    switch ($pcodedata->type) {
                        case 0:
                            if($item->itemprice > $pcodedata->discount){
                                $item->discount = $pcodedata->discount;
                                $item->finalprice = $item->itemprice - $item->discount;
                            } else {
                                $item->discount = $item->itemprice;
                                $item->finalprice = $item->itemprice - $item->discount;
                            }
                            break;
                        case 1:
                            $item->discount = ($item->itemprice * $pcodedata->discount)/100;
                            $item->finalprice = $item->itemprice - $item->discount;
                            break;
                    }
                    $item->appliedpromo = $pcodedata->promoid;
                } else {
                    $item->appliedpromo = null;
                }
            }
            $item->itemfinalprice = $item->finalprice * $item->quantity;
            if($item->quantity <= 1){$item->disableprev = 1; } else {$item->disableprev = 0;}
            
            // $item->price=number_format($item->price,2,".",",");
            // $item->finalprice=number_format($item->finalprice,2,".",",");
            // $item->itemfinalprice=number_format($item->itemfinalprice,2,".",",");
            // $item->itemprice=number_format($item->itemprice,2,".",",");

            $allitems[$key] = $item;
            


             if(empty($item->mainimage) || $item->mainimage=="null"){
                $item->mainimage= new stdClass();
            }
        }
        // echo "<pre>";
        // print_r($allitems);
        // die;
        return array_values($allitems);
    }
    /**
     * @param string $cartid
     * @return array
     *  
     * */
    private function getCart($cartid = null){
        global $DB, $USER;
        if($oldcart  = $DB->get_record_sql("SELECT * FROM {cart} where id=:id and status=0", array("id"=>$cartid))){
            if(!empty($USER->id)){
                $mycart = $DB->get_record_sql("select * from {cart} where userid=? and status = 0", array($USER->id));
                $this->cartid = $mycart->id;
                $this->cart = $mycart;
                if($mycart->id != $oldcart->id){
                    self::mergecart($mycart, $oldcart);
                }
            } else {
                $this->cartid = $oldcart->id;
                $this->cart = $oldcart;
            }
            return $this->cartid;
        } else if($oldcart  = $DB->get_record_sql("select * from {cart} where userid=? and status = 0", array($USER->id))){
            $this->cartid = $oldcart->id;
            $this->cart = $oldcart;
            return $this->cartid;
        } else {
            $_SESSION['cartid'] = self::createCart();
        }
    }
    /**
     * @param string $mycart 
     * @param string $newcart
     * @return string
     */
    private function mergecart($mycart, $newcart){
        global $DB, $USER;
        $allnewitems = $DB->get_records("cartitem", array("cartid"=>$newcart->id, "deleted"=>0));
        foreach ($allnewitems as $key => $item) {
            self::add($item);
        }

    }

    /**
     * @param string $cartid
     * @return array
     */
    private function createCart(){
        global $DB, $USER;
        $newcart = new stdClass();
        $newcart->userid = (isset($USER->id)?$USER->id:0);
        $newcart->status = 0;
        $newcart->createdby = (isset($USER->id)?$USER->id:0);
        $newcart->createdtime = time();
        $newcart->id  = $DB->insert_record("cart", $newcart);
        $this->cartid = $newcart->id;
        $this->cart = $newcart;
        return $this->cartid;
    }
    /**
     * @param mixed $args
     * @return string $returndata 
     */
    public function applyPromoCode($args){
        global $DB, $USER;
        $returndata = array("status"=>0, "message"=>"Failed to apply promocode");
        switch ($args->itemtype) {
            case 0:
                if($promo = $DB->get_record_sql("SELECT pc.* FROM {cartitem} ci INNER JOIN mdl_product p on p.id=ci.itemid INNER JOIN mdl_promocode pc on pc.id=p.promocode WHERE ci.producttype=0 and ci.id=? AND pc.promoid=?", array($args->itemid, $args->promocode))){
                    $itemdata = new stdClass();
                    $itemdata->id = $args->itemid;
                    $itemdata->appliedpromo = $promo->id;
                    $itemdata->appliedpromodata = json_encode($promo);
                    if($DB->update_record("cartitem", $itemdata)){
                        $returndata['status'] = 1;
                        $returndata['message'] = "Promocode applied";
                    } else {
                        $returndata['message'] = "Failed to apply Promocode";
                    }
                } else {
                    $returndata['message'] = "Invalid Promocode";
                }
                break;
             case 1:
                if($cartitem = $DB->get_record_sql("SELECT ci.* FROM {cartitem} ci INNER JOIN mdl_course c on c.id=ci.itemid WHERE ci.producttype=1 and ci.id=?", array($args->itemid))){
                    $coursedata=self::coursedetails($cartitem->itemid);
                    if(!empty($coursedata['coursepromocode']) && $coursedata['coursepromocode']==$args->promocode){
                        $promo = $coursedata['promocode'];
                        $itemdata = new stdClass();
                        $itemdata->id = $cartitem->id;
                        $itemdata->appliedpromo = $promo->id;
                        $itemdata->appliedpromodata = json_encode($promo);
                        if($DB->update_record("cartitem", $itemdata)){
                            $returndata['status'] = 1;
                            $returndata['message'] = "Promocode applied";
                        } else {
                            $returndata['message'] = "Failed to apply Promocode";
                        }
                    } else {
                        $returndata['message'] = "Invalid Promoid";
                    }
                } else {
                    $returndata['message'] = "Invalid Promocode";
                }
                break;
            default:
                break;
        }
        return $returndata;
    }

    /**
     * @param string $product
     * @return string $returndata \
     * @return array
     */

    public function applyPromoCodeonProduct($args){
        global $DB, $USER;
        $returndata = array("status"=>0, "message"=>"Failed to apply promocode");
        switch ($args->itemtype) {
            case 0:
                $PM = new productModel();
                $product = $PM->viewproduct($args->itemid);
                if($product){
                    if($pcodedata = $DB->get_record_sql("SELECT pc.* FROM mdl_promocode pc WHERE pc.promoid=?", array($args->promocode))){
                        if($pcodedata->id == $product->promocode){
                            switch ($pcodedata->type) {
                                case 0:
                                    if($product->price > $pcodedata->discount){
                                        $product->discount = $pcodedata->discount;
                                        $product->finalprice = $product->price - $product->discount;
                                    } else {
                                        $product->discount = $product->price;
                                        $product->finalprice = $product->price - $product->discount;
                                    }
                                    break;
                                case 1:
                                    $product->discount = ($product->price * $pcodedata->discount)/100;
                                    $product->finalprice = $product->price - $product->discount;
                                    break;
                            }
                            $returndata['promocodeid'] = $pcodedata->id;
                            $product->appliedid =  $pcodedata->id;
                            $returndata['status'] = 1;
                            $returndata['message'] = "Promocode applied";
                        } else {
                            $returndata['message'] = "Invalid Promocode";
                        }
                    } else {
                        $returndata['message'] = "Invalid Promocode";
                    }
                    $returndata['product'] = $product;
                } else {
                    $returndata['message'] = "Invalid Promocode";
                }
                break;
                case 1:  
                    $coursedata=self::coursedetails($args->itemid);
                        if(!empty($coursedata['coursepromocode'])){

                               if($coursedata['coursepromocode']==$args->promocode){
                                        if($coursedata['promocodestatus']=="1"){
                                            if($pcodedata = $DB->get_record_sql("SELECT pc.* FROM {promocode} as pc WHERE pc.promoid=?", array($args->promocode))){
                                                                        switch ($pcodedata->type) {
                                                                            case 0:
                                                                                if($coursedata['courseprice'] > $pcodedata->discount){
                                                                                    $returndata['discount'] = $pcodedata->discount;
                                                                                    $returndata['finalprice'] = $coursedata['courseprice'] - $returndata['discount'];
                                                                                } else {
                                                                                    $returndata['discount'] = $coursedata['courseprice'];
                                                                                    $returndata['finalprice'] = $coursedata['courseprice'] - $returndata['discount'];
                                                                                }
                                                                                break;
                                                                            case 1:
                                                                                $returndata['discount'] = ($coursedata['courseprice'] * $pcodedata->discount)/100;
                                                                                $product->finalprice = $coursedata['courseprice'] -  $returndata['discount'];
                                                                                break;
                                                                        }
                                                                        
                                                                        $returndata['promocodeid'] = $pcodedata->id;
                                                                        $returndata['originalprice'] = $coursedata['courseprice'];
                                                                        $returndata['status'] = 1;
                                                                        $returndata['message'] = "Promocode applied";
                                                                 
                                            }

                                            
                                        }else{
                                            $returndata['status'] = 0;
                                           $returndata['message'] = "Promocode Expired"; 
                                        }
                                    
                                }else{
                                       $returndata['status'] = 0;
                                       $returndata['message'] = "Invalid Promocode";  
                                }
                                

                        }else{

                            $returndata['status'] = 0;
                            $returndata['message'] = "Promocode empty"; 

                        }
                     

                break;


            
            default:
                break;
        }
        return $returndata;
    }

     public static function coursedetails($courseid){
            global $DB;
          $coursedata=$DB->get_records('course_format_options',array('format'=>'elearnified','courseid'=>$courseid));
          $courseEcdata=array();
          foreach ($coursedata as $value) {
            if($value->name=="coursepromocode"){
                $promocode=promocodeModel::getbyID($value->value);
                $courseEcdata["$value->name"]=$promocode->promoid;
                $current_time=time();
                $couponexpire=$DB->get_record_sql("select * from {promocode} where `id`='".$value->value."' AND (startdate  <=? AND enddate >= ?)  ",array($current_time,$current_time));
                if(!empty($couponexpire)){
                    $courseEcdata['promocodestatus']="1";
                    $courseEcdata['promocode']=$couponexpire;
                }else{
                    $courseEcdata['promocodestatus']="0";
                }

            }else{
               $courseEcdata["$value->name"]=$value->value; 
            }   
         }
         return $courseEcdata;

     }





/**
 * @return array*/
    public function addCartItemRating($args){
        global $DB;
        $args->modifieddate=time();
        if($cartitem=$DB->get_record('cartitem',array('id'=>$args->id))){
            $DB->update_record("cartitem", $args);
            $avgrating=$DB->get_field_sql("SELECT avg(rating) FROM {cartitem} WHERE producttype=? AND itemid=? AND rating IS NOT NULL ",array($cartitem->producttype,$cartitem->itemid));
            if($avgrating){
                if($cartitem->producttype==0){
                    try {
                        $product=new stdClass();
                        $product->id=$cartitem->itemid;
                        $product->rating=intval($avgrating);
                        $DB->update_record('product',$product);
                    } catch (Exception $e) {
                        return false;
                    }
                } else {
                    /*Need to update course ratings*/
                }
            }
        }
        return true;
    }
}


