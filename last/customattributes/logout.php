  <?php require_once("../../../wp-config.php");
   
    session_destroy();
    wp_clear_auth_cookie();
    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_logout(); 
    wp_redirect(site_url('/user-login/?authlogin=login'));
    