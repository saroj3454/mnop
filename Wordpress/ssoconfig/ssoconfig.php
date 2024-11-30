<?php 
/*
Plugin Name:  Sso Sync
Description:  User data sync and other data sync. 
Version:      1.1
Author:       Lds 
Author URI:   https://www.ldsengineers.com/
*/
ob_start();
// If this file is called directly, abort. 
if ( ! defined( 'WPINC' ) ) {
	die;
}
require plugin_dir_path( __FILE__ ) . 'includes/class-ssoconfig-sync.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ssoconfig-userpassword.php';
register_activation_hook( __FILE__, 'roytuts_on_activation' );
function roytuts_on_activation(){
	Syncssodata::roytuts_on_activation();
}
roytuts_on_activation();
function ssosyncconfigmenu(){
	Syncssodata::ssosyncconfigmenu();
}
add_action('admin_menu', 'ssosyncconfigmenu');
function sso_syncauthretrive(){
	Syncssodata::sso_syncauthretrive();
}
function add_syncconfig(){
sso_syncauthretrive();
Syncssodata::add_syncconfig();
}


/**
 * Attaches the specified template to the page identified by the specified name.
 *
 * @params    $page_name        The name of the page to attach the template.
 * @params    $template_path    The template's filename (assumes .php' is specified)
 *
 * @returns   -1 if the page does not exist; otherwise, the ID of the page.
 */
// function attach_template_to_page( $page_name, $template_file_name ) {

//     // Look for the page by the specified title. Set the ID to -1 if it doesn't exist.
//     // Otherwise, set it to the page's ID.
//     $page = get_page_by_title( $page_name, OBJECT, 'page' );
//     $page_id = null == $page ? -1 : $page->ID;

//     // Only attach the template if the page exists
//     if( -1 != $page_id ) {
//         update_post_meta( $page_id, '_wp_page_template', $template_file_name );
//     } // end if

//     return $page_id;

// } // en

// attach_template_to_page( 'sitemap', 'login.php' );


// function web_create_page() {
//  global $wpdb;

// }
// add_action('admin_init', 'web_create_page');

function activate_resources() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-resources-activator.php';
	Resources_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_resources' );



// create thanku page
class PageTemplater {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class. 
     */
    public static function get_instance() {

        if (null == self::$instance) {
            self::$instance = new PageTemplater();
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {

            // 4.6 and older
            add_filter(
                    'page_attributes_dropdown_pages_args', array($this, 'register_project_templates')
            );
        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                    'theme_page_templates', array($this, 'add_new_template')
            );
        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
                'wp_insert_post_data', array($this, 'register_project_templates')
        );


        // Add a filter to the template include to determine if the page has our 
        // template assigned and return it's path
        add_filter(
                'template_include', array($this, 'view_project_template')
        );


        // Add your templates to this array.
        $this->templates = array(
            'login.php' => 'Login',
            'register.php' => 'Register',
            'logintomoodle.php' => 'Login to Moodle',
            // 'ssopasswordsyncvalidate.php' => 'Password Validate',
        );
    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template($posts_templates) {
        $posts_templates = array_merge($posts_templates, $this->templates);
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates($atts) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template($template) {

        // Get global post
        global $post;

        // Return template if post is empty
        if (!$post) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if (!isset($this->templates[get_post_meta(
                                $post->ID, '_wp_page_template', true
                )])) {
            return $template;
        }

        $file = plugin_dir_path(__FILE__) . get_post_meta(
                        $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;
    }

}

add_action('plugins_loaded', array('PageTemplater', 'get_instance'));
function deactivate_resources() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-resources-deactivator.php';
    Resources_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_resources' );
function frontweblogin(){

if(empty($_GET['authlogin'])){
  $avlssodata=Syncssodata::avl_syncconfigdata();
 if(!empty($avlssodata['apiurl']) && !empty($avlssodata['clientid']) && !empty($avlssodata['secrtekey'])){
     wp_redirect($avlssodata['apiurl'].'local/ssosync/loginauth.php'); 
 }  
}


Syncssodata::frontweblogin();
}
function frontgpage_login(){
   add_shortcode('ssoconfig_login', 'frontweblogin'); 
}
add_action('init', 'frontgpage_login');
add_action('init', 'login_css');
function login_css(){
    add_action( 'wp_enqueue_scripts', 'login_custom_styles');
}
function login_custom_styles(){
 Syncssodata::userdatasync();
}
function webpage_register(){
   Syncssodata::webpage_register(); 
}
function frontgpage_register(){
   add_shortcode('ssoconfig_register', 'webpage_register');  
}
add_action('init', 'frontgpage_register');
function register_page_style() {
    
    if ( is_page_template( 'register.php' ) || is_page_template( 'login.php' )) {
    wp_enqueue_style('custom-css-rgister', site_url().'/wp-content/plugins/ssoconfig/css/style.css',false, '1.0.0', 'all');
    wp_enqueue_style('bootstrap-css-1', '//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css',false, '1.0.0', 'all');
    wp_enqueue_style('bootstrap-css-2', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css',false, '1.0.0', 'all');
    wp_enqueue_script('bootstrap-js-1', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js',false, '1.0.0', 'all');
     wp_enqueue_script('bootstrap-js-2', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',false, '1.0.0', 'all');
    wp_enqueue_script('jquery-validator', site_url().'/wp-content/plugins/ssoconfig/js/jquery.validate.min.js',false, '1.0.0', 'all');
    wp_enqueue_script('additional-file', site_url().'/wp-content/plugins/ssoconfig/js/additional-methods.min.js',false, '1.0.0', 'all');
    wp_enqueue_script('custom-js', site_url().'/wp-content/plugins/ssoconfig/js/style.js',false, '1.0.0', 'all');

   wp_localize_script('custom-js', 'plugin_ajax_object',array('ajax_url' =>site_url().'/wp-json/centres/v1/list-registration-centres'));
    }
}
add_action( 'wp_enqueue_scripts', 'register_page_style');


function login_page_style() {
    if ( is_page_template( 'login.php' ) || is_page_template( 'login.php' )) {
    wp_enqueue_script('custom-js-sso', site_url().'/wp-content/plugins/ssoconfig/js/loginstyle.js',false, '1.0.0', 'all');
    }
}
add_action( 'wp_enqueue_scripts', 'login_page_style' );

add_action('init', 'frontgpage_logintomoodle');

function frontgpage_logintomoodle(){
  add_shortcode( 'ssoconfigtokenauth', 'wpshout_sample_shortcode' );  
}
function wpshout_sample_shortcode() {
  Syncssodata::validatelogintomoodle_tokenauth();
}
function save_extra_user_profile_fields( $user_id ) {
 global $wpdb;
  
$useriddata = get_user_by('id',$user_id );
$image_url="";
$attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$user_id."' and `meta_key`='profile_image'");
if(!empty($attechment->meta_value)){
$image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
if(!empty($image[0])){
   $image_url = $image[0]; 
}

}


$userdata = array('user_login'    =>   $useriddata->user_login,
                        'user_email'    =>   $_POST['email'],
                        'user_pass'     =>   '',
                        'first_name'    =>   $_POST['first_name'],
                        'last_name'     =>   $_POST['last_name'],
                        'user_image'     =>  $image_url,
                        );

$returndata=Syncssodata::moodle_api_register($userdata);

// echo"<pre>";
//  print_r($_POST);
//  print_r($userdata);
//  print_r($returndata);

//  die();

}
add_action( 'profile_update', 'save_extra_user_profile_fields', 10, 2 );
// add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
// add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );


function wordpresslogout(){
    Syncssodata::wordpresslogout();
}

add_shortcode('ssoconfiglogout', 'wordpresslogout'); 
add_action( 'wp_logout', 'redirect_after_logout');
function redirect_after_logout(){
    Syncssodata::redirect_after_logout();
}


function force_pretty_displaynames($user_login, $user) {
    $outcome = trim(get_user_meta($user->ID, 'first_name', true) . " " . get_user_meta($user->ID, 'last_name', true));
    if (!empty($outcome) && ($user->data->display_name!=$outcome)) {
        wp_update_user( array ('ID' => $user->ID, 'display_name' => $outcome));    
    }
}
add_action('wp_login','force_pretty_displaynames',10,2); 


add_action ('admin_head','make_display_name_f_name_last_name');
function make_display_name_f_name_last_name(){
       $user_ID= get_current_user_id();
       if(!empty($user_ID)){
        $user = get_userdata($user_ID);    
        $display_name = $user->first_name . " " . $user->last_name;
        if($display_name!=' ') wp_update_user( array ('ID' => $user->ID, 'display_name' => $display_name) );
            else wp_update_user( array ('ID' => $user->ID, 'display_name' => $user->display_login) );
        if($user->display_name == '') 
            wp_update_user( array ('ID' => $user->ID, 'display_name' => $user->display_login) );
   }
}

define('SSOCONFIG_SYNC',plugin_dir_path( __FILE__ ) . 'includes/class-ssoconfig-sync.php');

// add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
// function wpb_new_gravatar ($avatar_defaults) {
// $myavatar = 'http://pngimages.net/sites/default/files/user-png-image-15189.png';
// $avatar_defaults[$myavatar] = "Default Gravatar";
// return $avatar_defaults;
// }

// add_filter('wp_authenticate_user', 'my_auth_login',10,2);
// function my_auth_login ($user, $password) {
//      global $wpdb;

//     $moodledata=get_user_meta( $user->ID, 'moodle_user_id', true );
//     if(!empty($moodledata)){
//     delete_user_meta($user->ID, 'moodle_user_id' );
//     }
//     $image_url="";
//     $attechment=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."usermeta WHERE `user_id` = '".$user->ID."' and `meta_key`='profile_image'");
//     if(!empty($attechment->meta_value)){
//         $image = wp_get_attachment_image_src($attechment->meta_value, 'small' );
//         if(!empty($image[0])){
//         $image_url = $image[0]; 
//         }
//     }
//     $useriddata = get_user_by( 'id',$user->ID);
//     $userdata = array('user_login'=>$useriddata->user_login,
//     'user_email'    =>   $useriddata->user_email,
//     'user_pass'     =>   $useriddata->user_pass,
//     'first_name'    =>   get_user_meta( $user->ID, 'first_name', true ),
//     'last_name'     =>   get_user_meta( $user->ID, 'last_name', true ),
//     'user_image'     =>  $image_url,
//     );
//     $returnstatus=Syncssodata::moodle_api_register($userdata);
//     if(!empty($returnstatus['userid'])){
//     return $user;
//     }          
// }











