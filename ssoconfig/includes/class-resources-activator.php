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
class Resources_Activator {

	/**
	 * Add all tables 
	 *
	 * It will create all the tables at installation time.
     * 
	 * @since    1.0.0
	 */
	public static function activate() {
        self::plugincreate_page();
        self::moodlecreate_page();

	}
    private static function moodlecreate_page() {
       require_once plugin_dir_path( __FILE__ ).'class-ssoconfig-sync.php';
      Syncssodata::activator_loginregisterpageurlsync();   
    }
    private static function plugincreate_page() {
        global $wpdb;
        $page = get_pages();    
        $contact_page= array('slug' => 'login', 'title' =>'Login');
        foreach ($pages as $page) { 
            $apage = $page->post_name; 
             switch ( $apage ){ 
                case 'login' : $contact_found= '1';     
                break;          
                default:$no_page;           
            }       
        }
        if($contact_found != '1'){
                $page_id = wp_insert_post(array(
                    'post_title' => $contact_page['title'],
                    'post_type' =>'page',       
                    'post_name' => $contact_page['slug'],
                    'post_status' => 'publish',
                    'post_excerpt' => 'User Login  ! '  
                ));
            add_post_meta( $page_id, '_wp_page_template', 'login.php' );
            add_post_meta( $page_id, '_wp_ssoconfig', 'login.php' );
        }
        $register_page=array('slug' => 'register', 'title' =>'Register');
        foreach ($pages as $page) { 
                $apage = $page->post_name; 
                 switch ( $apage ){ 
                    case 'register' : $register_found= '1';     
                    break;          
                    default:$no_page;           
                }       
            }
        if($register_found !='1'){
                $page_id = wp_insert_post(array(
                    'post_title' => $register_page['title'],
                    'post_type' =>'page',       
                    'post_name' => $register_page['slug'],
                    'post_status' => 'publish',
                    'post_excerpt' => 'User Register  ! '  
                ));
            add_post_meta( $page_id, '_wp_page_template', 'register.php' );
            add_post_meta( $page_id, '_wp_ssoconfig', 'register.php' );
        }

 $loginto_moodle_page=array('slug' => 'log-into-moodle-page', 'title' =>'Login to Moodle');
        foreach ($pages as $page) { 
                $apage = $page->post_name; 
                 switch ( $apage ){ 
                    case 'log-into-moodle-page' : $loginto_moodle_found= '1';     
                    break;          
                    default:$no_page;           
                }       
            }
        if($loginto_moodle_found !='1'){
                $page_id = wp_insert_post(array(
                    'post_title' => $loginto_moodle_page['title'],
                    'post_type' =>'page',       
                    'post_name' => $loginto_moodle_page['slug'],
                    'post_status' => 'publish',
                    'post_excerpt' => 'Login to Moodle ! '  
                ));
            add_post_meta( $page_id, '_wp_page_template', 'logintomoodle.php');
            add_post_meta( $page_id, '_wp_ssoconfig', 'logintomoodle.php');
        }



        
    }
}
