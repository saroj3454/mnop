<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/Mauryasuraj
 * @since      1.0.0
 *
 * @package    Sso Sync
 * @subpackage Sso Sync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sso Sync
 * @subpackage Sso Sync/includes
 * @author     lds
 */
class Resources_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
      self::moodlealterneturlchange();
         // self::plugincreated_page();
	}
   private function moodlealterneturlchange(){
         global $wpdb;
          require_once plugin_dir_path( __FILE__ ) . 'class-ssoconfig-sync.php';
          $avlssodata=Syncssodata::using_avl_syncconfigdata();
        if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
              $data=Syncssodata::using_sso_syncauth(); 
              if($data=="secure"){ 
                  if(!empty($avlssodata['apiurl'])){
                    $apiurl=$avlssodata['apiurl'];
                    }
                    if(!empty($avlssodata['clientid'])){
                    $clientid=$avlssodata['clientid'];
                    }
                    if(!empty($avlssodata['secrtekey'])){
                    $secrtekey=$avlssodata['secrtekey'];
                    }
                     $curl = curl_init();
                     $senddata=json_encode(array('clientid'=>$clientid,'secretkey'=>$secrtekey,'action'=>'moodledeactiveloginurl','moodledeactive'=>'alternetloginurl'));
                     echo $apiurl.'local/ssosync/sync.php';
                     print_r($senddata);
                     curl_setopt_array($curl, array(
                     CURLOPT_URL => $apiurl.'local/ssosync/sync.php',
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => '',
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => 'POST',
                     CURLOPT_POSTFIELDS =>$senddata,
                     CURLOPT_HTTPHEADER => array(
                     'Content-Type: application/json'
                     ),
                     ));
                     $response = curl_exec($curl);
                     $result=json_decode($response);
                      if(!empty($result)){
                        self::plugincreated_page();
                     } 
              }
           }else{
            self::plugincreated_page();
           }
   
          

   }
    private static function plugincreated_page() {
        global $wpdb;
          $woocomment=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_key`='_wp_ssoconfig'");
          foreach ($woocomment as $value) {
             $datakey=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."posts WHERE `ID`='".$value->post_id."'");
             if(!empty($datakey)){
                $wpdb->query("DELETE FROM " . $wpdb->prefix . "posts WHERE `ID`='" .$datakey->ID."'");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . "postmeta WHERE `meta_id`='" .$value->meta_id."'");
             }

          }
         
    }



}
