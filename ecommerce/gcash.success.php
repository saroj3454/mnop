<?php

use local_portal\util\secured\UnsafeCrypto;
use theme_elearnified\util\constants\Crypt;
use theme_elearnified\util\elearnifiedhelper;

require_once('../../config.php');
require_login();

$params = array_merge($_GET, $_POST);

if( confirm_sesskey() ){
    $error = 0;
    $userdetails = json_decode(Crypt::decrypt($params['sesskey'], $params['sesskey3']),true);
    $cartitems = elearnifiedhelper::get_payment_details_thru_api();
    if( $cartitems['cartid'] && $userdetails && count($userdetails) ) {
        $eventother = [
            'team' => $userdetails['team'],
            'brgy' => $userdetails['brgy'],
            'address_2' => $userdetails['address_2'],
            'address_1' => $userdetails['address_1'],
            'phonenumber' => $userdetails['phonenumber'],
            'region' => $userdetails['region'],
            'zip' => $userdetails['zip'],
            'province' => $userdetails['province'],
            'city' => $userdetails['city'],
            'status' => 1,
            'payment_method' => 2,
            'amount' => Crypt::decrypt($params['sesskey'], $params['sesskey2']) ?: 0,
            'transaction_date' => $params['transaction_date'] ?: time(),
            'userid' => $USER->id,
            'sesskey' => sesskey(),
            'cartid' => $cartitems['cartid'],
        ];
        if (!is_number($eventother['amount']) || $eventother['amount'] == 0)
            $error = 1;
        else {
            $eventother['paymentid'] = elearnifiedhelper::save_paymongo_payment($eventother);
            $event = \block_elearnified_paymongo\event\paymongo_user_paid::create(array('context' => context_system::instance(),
                'userid' => $eventother['userid'],
                'objectid' => $eventother['paymentid'],
                'relateduserid' => $eventother['userid'],
                'other' => $eventother));
            $event->trigger();
            $Pass =  UnsafeCrypto::AUTH_KEY;
            $encryptedid = UnsafeCrypto::encrypt($eventother['paymentid'],$Pass, true);
            echo elearnifiedhelper::notice('Your Check Out is Complete!', 'fa fa-check', 'Congratulations! We have successfully process your payment.', ['class' => 'success'], 0, new moodle_url('/local/ecommerce/historydetails/index.php',['id'=>$encryptedid]));
        }
    }else{
        $error = 1;
    }
}else{

    $error = 1;
}

if( $error ){
    echo elearnifiedhelper::notice('Error Processing Payment!', 'fa fa-times', 'We have encountered an error processing your payment. <br>Please reach out to <a target="_blank" href="mailto:contact@elearnified.com">contact@elearnified.com</a> <br>if you need further help.', ['class'=>'danger'],0,new moodle_url('/'));
    exit;
}
