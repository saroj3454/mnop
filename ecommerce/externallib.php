<?php 
global $CFG;
require_once("$CFG->libdir/externallib.php");

class local_ecommerce_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */

    public static function cart_items_total_amount_parameters() {
          return new external_function_parameters(
            array()
        );
    }
    public static function cart_items_total_amount_returns() {
        return new external_single_structure(
            array(
                'cartsize' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'cartitems' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id'=>new external_value(PARAM_RAW, 'ID', VALUE_DEFAULT, ''),
                            'cartid'=>new external_value(PARAM_RAW, 'cartid', VALUE_DEFAULT, ''),
                            'producttype'=>new external_value(PARAM_RAW, 'producttype', VALUE_DEFAULT, ''),
                            'itemid'=>new external_value(PARAM_RAW, 'itemid', VALUE_DEFAULT, ''),
                            'quantity'=>new external_value(PARAM_RAW, 'quantity', VALUE_DEFAULT, ''),
                            'rating'=>new external_value(PARAM_RAW, 'rating', VALUE_DEFAULT, ''),
                            'appliedpromo'=>new external_value(PARAM_RAW, 'appliedpromo', VALUE_DEFAULT, ''),
                            'appliedpromodata'=>new external_value(PARAM_RAW, 'appliedpromodata', VALUE_DEFAULT, ''),
                            'addedby'=>new external_value(PARAM_RAW, 'addedby', VALUE_DEFAULT, ''),
                            'addeddate'=>new external_value(PARAM_RAW, 'addeddate', VALUE_DEFAULT, ''),
                            'modifieddate'=>new external_value(PARAM_RAW, 'modifieddate', VALUE_DEFAULT, ''),
                            'deleted'=>new external_value(PARAM_RAW, 'deleted', VALUE_DEFAULT, ''),
                            'updatequantity'=>new external_value(PARAM_RAW, 'updatequantity', VALUE_DEFAULT, ''),
                            'itemname'=>new external_value(PARAM_RAW, 'itemname', VALUE_DEFAULT, ''),
                            'itemtype'=>new external_value(PARAM_RAW, 'itemtype', VALUE_DEFAULT, ''),
                            'itemprice'=>new external_value(PARAM_RAW, 'itemprice', VALUE_DEFAULT, ''),
                            'finalprice'=>new external_value(PARAM_RAW, 'finalprice', VALUE_DEFAULT, ''),
                            'promocode'=>new external_value(PARAM_RAW, 'promocode', VALUE_DEFAULT, ''),
                            'mainimage'=>new external_single_structure(
                                array(
                                    'url'=>new external_value(PARAM_RAW, 'url', VALUE_DEFAULT, ''),
                                    'filename'=>new external_value(PARAM_RAW, 'filename', VALUE_DEFAULT, ''),
                                    'pathnamehash'=>new external_value(PARAM_RAW, 'pathnamehash', VALUE_DEFAULT, '')
                                )
                            ),
                            'disablenext'=>new external_value(PARAM_RAW, 'disablenext', VALUE_DEFAULT, 0),
                            'discount'=>new external_value(PARAM_RAW, 'discount', VALUE_DEFAULT, 0),
                            'nopromocoed'=>new external_value(PARAM_RAW, 'nopromocoed', VALUE_DEFAULT, ''),
                            'havepromocode'=>new external_value(PARAM_RAW, 'havepromocode', VALUE_DEFAULT, ''),
                            'itemfinalprice'=>new external_value(PARAM_RAW, 'itemfinalprice', VALUE_DEFAULT, ''),
                            'disableprev'=>new external_value(PARAM_RAW, 'disableprev', VALUE_DEFAULT, 0),
                        )
                    ), 'cart details',VALUE_DEFAULT, array()
                ),
                'cartid' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'subtotal' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'havevat' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'vatpercent' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'vat' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'shipping' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'discount' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, 0),
                'havediscount' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'displaycartfooter' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'total' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'checkouturl' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'paymenturl' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'backtocart' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
            )
    );
        
    }
    public static function cart_items_total_amount() {
    	 global $CFG, $PAGE, $OUTPUT;
         require_once($CFG->dirroot."/local/ecommerce/classes/models/cartModel.php");
        $CM = new cartModel();
        $subtotal = 0;
        $vat = 0;
        $vatpercent = 0;
        $shipping = 0;
        $discount = 0;
        $havediscount = false;
        $havevat = true;
        $displaycartfooter = false;
        $cartitems = $CM->getallitems();
        foreach ($cartitems as $key => $item) {
            $displaycartfooter = true;
            $subtotal+= $item->itemfinalprice;
            if($item->itemfinalprice > 0){$discount = $item->discount;}
        }

        $vat = ($subtotal * $vatpercent/100);
        $shipping = ($subtotal>0?$shipping:0);
        $total = ($subtotal + $vat + $shipping) - $discount;
        $newpdata = array(
            "cartsize"=>sizeof($cartitems),
            "cartid"=>$CM->cartid,
            "cartitems"=>$cartitems,
            "subtotal"=>$subtotal,
            "havevat"=>$havevat,
            "vatpercent"=>$vatpercent,
            "vat"=>$vat,
            "shipping"=>$shipping,
            "discount"=>$discount,
            "havediscount"=>$havediscount,
            "displaycartfooter"=>$displaycartfooter,
            "total"=>$total,
            "checkouturl"=>$CFG->wwwroot."/local/ecommerce/checkout/",
            "paymenturl"=>$CFG->wwwroot."/local/ecommerce/payment/",
            "backtocart"=>$CFG->wwwroot."/local/ecommerce/",
        );
        return $newpdata; 
    }

    public static function cart_items_remove_all_list_returns(){
          return new external_single_structure(
            array(
                'status' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                'message'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, '')
            )
        );

    }
    public static function cart_items_remove_all_list($cartid){
             global $CFG, $PAGE, $OUTPUT,$DB;
             $data=$DB->get_record('cart',array('id'=>$cartid));
                if(!empty($data)){
                    $data->status='1';
                    $DB->update_record('cart',$data);
                    $status="1";  
                    $message="Update sucessfully"; 
                }else{
                    $message="Cart id not available"; 
                    $status="0";

                }
        return array('status'=>$status,'message'=>$message);

    }

    public static function cart_items_remove_all_list_parameters() {
          return new external_function_parameters(
            array('cartid'=>new external_value(PARAM_RAW,'Payment sucess cart id', VALUE_DEFAULT, ''))
        );
    }


    public static function product_promocode_parameters() {
          return new external_function_parameters(
            array(
                'itemid'=>new external_value(PARAM_RAW,'Course id', VALUE_DEFAULT, ''),
                'promocode'=>new external_value(PARAM_RAW,'Promo code', VALUE_DEFAULT, ''),
                'cartid'=>new external_value(PARAM_RAW,'Cart id', VALUE_DEFAULT, '')
            )
        );
    }


     public static function product_promocode_returns(){
                 return new external_single_structure(
                array(
                    'status' => new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                    'message'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                    'originalprice'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                    'discount'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                    'discountprice'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, ''),
                    'promocodeid'=> new external_value(PARAM_RAW, '', VALUE_DEFAULT, '')
                )
            );
     }

     public static function product_promocode($itemid,$promocode,$cartid){
        global $DB,$CFG,$USER;
        require_once($CFG->dirroot."/local/ecommerce/classes/models/cartModel.php");
        $args=new stdClass();
        $args->itemid=$itemid;
        $args->promocode=$promocode;
        $args->itemtype=1;

           $data=cartModel::applyPromoCodeonProduct($args);
            
              // print_r($data);
           if($data['status']=='1' && !empty($cartid)){
               $updatedata=$DB->get_record_sql("SELECT ct.* FROM {cartitem} as ct INNER join {cart} as c on ct.cartid=c.id and c.status!=1 and c.id='".$cartid."' and ct.itemid='".$itemid."' and ct.producttype='1'");
               	if(!empty($updatedata)){
                $current_time=time();
                $coupondata=$DB->get_record_sql("select * from {promocode} where `id`='".$data['promocodeid']."'");
                 $updatedata->appliedpromo=$coupondata->id;
                 $updatedata->appliedpromodata=json_encode($coupondata);
                 $DB->update_record('cartitem',$updatedata);


               	}



               

           }

         $fmt = new \NumberFormatter('en_PH', \NumberFormatter::CURRENCY);
        return array('status' => $data['status'],
                    'message'=> $data['message'],
                    'discount'=>$data['discount'],
                    'originalprice'=>$data['originalprice'],
                    'discountprice'=>$fmt->formatCurrency($data['finalprice'], 'PHP'),
                    'promocodeid'=>$data['promocodeid'],


                );
     }


  
 
}