<?php  get_header();
	 global $wpdb;

wp_enqueue_style('custom-css', site_url().'/wp-content/plugins/ssoconfig/css/style.css',false, '1.0.0', 'all');
echo do_shortcode("[ssoconfig_login]");


?>


<?php
	 get_footer();