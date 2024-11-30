<?php
$services=array(
	'mypluginservice'=>array(
		"functions"=>array('local_ecommerce_cart_items_total_amount','local_ecommerce_cart_items_remove','local_ecommerce_cart_items_remove','local_ecommerce_product_promocode'),
		'requiredcapability'=>'',
		'restrictedusers'=>'',
		'enabled'=>1, 
		'shortname'=>"",
		'downloadfiles'=>0,
		'uploadfiles'=>0 
	)
);

$functions=array(
	'local_ecommerce_cart_items_total_amount' => array(         //web service function name
    'classname'   => 'local_ecommerce_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
    'methodname'  => 'cart_items_total_amount',           //external function name
    'classpath'   => 'local/ecommerce/externallib.php',  //file containing the class/external function - not required if using namespaced auto-loading classes.
                                                   // defaults to the service's externalib.php
    'description' => 'Cart Items Data',    //human readable description of the web service function
    'type'        => 'write',                  //database rights of the web service function (read, write)
    'ajax' => true,        // is the service available to 'internal' ajax calls. 
    'capabilities' => array(), 
	),'local_ecommerce_cart_items_remove' => array(         //web service function name
    'classname'   => 'local_ecommerce_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
    'methodname'  => 'cart_items_remove_all_list',           //external function name
    'classpath'   => 'local/ecommerce/externallib.php',  //file containing the class/external function - not required if using namespaced auto-loading classes.
                                                   // defaults to the service's externalib.php
    'description' => 'Cart Items delete after payment sucessfully',    //human readable description of the web service function
    'type'        => 'write',                  //database rights of the web service function (read, write)
    'ajax' => true,        // is the service available to 'internal' ajax calls. 
    'capabilities' => array(), 
	),'local_ecommerce_product_promocode' => array(        
    'classname'   => 'local_ecommerce_external',  
    'methodname'  => 'product_promocode',           
    'classpath'   => 'local/ecommerce/externallib.php',                                             
    'description' => 'Product Promo Code',   
    'type'        => 'read',
    'ajax' => true,
        'loginrequired' => false,
    'capabilities' => array(), 
	)
);