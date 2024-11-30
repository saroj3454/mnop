<?php require_once("../../../wp-config.php");

            global $current_user, $wpdb;
            get_currentuserinfo();
            if ($current_user->ID != 0) {
                $userid = $current_user->ID;
                $orderid = $_REQUEST['order'];
				//get user details
				$user_info = get_userdata($userid);
				$user_meta = get_user_meta($userid);			
				$moodleuserid=get_post_meta($orderid, 'userid', true );
               $admindetails=array();
                $admindetails['_payment_method']=get_post_meta($orderid, '_payment_method', true );
                $admindetails['_payment_method_title']=get_post_meta($orderid, '_payment_method_title', true );
                $admindetails['_customer_ip_address']=get_post_meta($orderid, '_customer_ip_address', true );
                $admindetails['_customer_user_agent']=get_post_meta($orderid, '_customer_user_agent', true );
                $admindetails['_billing_first_name']=get_post_meta($orderid, '_billing_first_name', true );
                $admindetails['_billing_last_name']=get_post_meta($orderid, '_billing_last_name', true );
                $admindetails['_billing_address_1']=get_post_meta($orderid, '_billing_address_1', true );
                $admindetails['_billing_city']=get_post_meta($orderid, '_billing_city', true );
                $admindetails['_billing_state']=get_post_meta($orderid, '_billing_state', true );
                $admindetails['_billing_postcode']=get_post_meta($orderid, '_billing_postcode', true );
                $admindetails['_billing_country']=get_post_meta($orderid, '_billing_country', true );
                $admindetails['_billing_email']=get_post_meta($orderid, '_billing_email', true );
                $admindetails['_billing_phone']=get_post_meta($orderid, '_billing_phone', true );
                $admindetails['_order_currency']=get_post_meta($orderid, '_order_currency', true );
                $admindetails['_order_total']=get_post_meta($orderid, '_order_total', true );
                $admindetails['_transaction_id']=get_post_meta($orderid, '_transaction_id', true );
                $admindetails['_order_key']=get_post_meta($orderid, '_order_key', true );
				
                $username = $user_info->user_login;
                $useremail = $user_info->user_email;
                $userfullname = $user_info->display_name;
				$fname = get_user_meta($userid, 'billing_first_name', true ); 
				$lname = get_user_meta($userid, 'billing_last_name', true ); 
				$complanyname = get_user_meta($userid, 'billing_company', true ); 
               } else {
                $orderid = $_REQUEST['order'];
                $order = new WC_Order($orderid);
                $useremail = $order->billing_email;
                $userfullname = $order->billing_first_name . ' ' . $order->billing_last_name;
                $username = $order->billing_first_name;
                $fname = $order->billing_first_name;
                $lname = $order->billing_last_name;
                $complanyname = $order->billing_company;
            }

            $results = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '" . $orderid . "'");
             $count = count($results);
            if ($count > 1) {
                $result = $wpdb->get_results("
				SELECT order_item_id FROM  " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '" . $orderid . "' ORDER BY order_item_id ASC");
            } else {
                $result = $wpdb->get_results("SELECT order_item_id FROM  " . $wpdb->prefix . "woocommerce_order_items WHERE order_id = '" . $orderid . "'");
            }
            $coursearray = array();
            foreach ($result as $data) {
                array_push($coursearray, $data->order_item_id);
            }
			//print_r($coursearray);
			//die;
            $implode = implode(",", $coursearray);
            $productids = $wpdb->get_results("
				SELECT meta_value 
				FROM  " . $wpdb->prefix . "woocommerce_order_itemmeta
					WHERE order_item_id IN($implode) AND meta_key='_product_id'
			 ");
            $productarr = array();

            foreach ($productids as $productid) {
                array_push($productarr, $productid->meta_value);
            }
             $implodepr = implode(",", $productarr);
            $mcourseid = getcourse($implodepr);


            function getcourse($implodepr) {
                global $wpdb;
                $courseids = $wpdb->get_results("
						SELECT courseid 
						FROM  " . $wpdb->prefix . "wootomoodle
							WHERE productid IN($implodepr)
					 ");
                //print_r($courseids);

                $coursearr = array();
                foreach ($courseids as $courseid) {
                    array_push($coursearr, $courseid->courseid);
                }
                $implodecr = implode(",", $coursearr);
                return $implodecr;
            }
            $userrdata=array('userid'=>$moodleuserid,'course'=>$mcourseid,'userconformation'=>$admindetails,'orderid'=>$orderid);
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => MoodleURL.'/local/oawa_auth/user_enrolled.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($userrdata),
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: MoodleSession=b7f592nvlf0cmiunfr3u7pkbrg'
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $datareturn=json_decode($response);
         
         
          
            if(!empty($datareturn->redirecturl)){

                         wp_redirect($datareturn->redirecturl);
                        exit();
            }else{
            	wp_redirect(site_url());
            }

         