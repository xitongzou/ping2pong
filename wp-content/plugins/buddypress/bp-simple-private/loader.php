<?php
/*
Plugin Name: BP Simple Private
Description: Select whether posts, pages and BuddyPress sections can be viewed by non-logged-in users. See Settings > BP Simple Private
Version: 1.0
Author: shanebp
Author URI: http://philopress.com/
*/

if ( !defined( 'ABSPATH' ) ) exit;


function pp_private_bp_check() {
	if ( !class_exists('BuddyPress') ) {
		add_action( 'admin_notices', 'pp_private_install_buddypress_notice' );
	}
}
add_action('plugins_loaded', 'pp_private_bp_check', 999);

function pp_private_install_buddypress_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('<strong>BuddyPress Simple Private</strong></a> requires the BuddyPress plugin. Please <a href="http://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate BuddyPress Simple Private</a>.', 'bp-simple-private');
	echo '</p></div>';
}


function pp_private_load_admin() {

	if ( is_admin() ) {

		load_plugin_textdomain( 'bp-simple-private', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		require( dirname( __FILE__ ) . '/inc/pp-private-admin-meta-box.php' );
		require( dirname( __FILE__ ) . '/inc/pp-private-admin-settings.php' );
	}
	else
		require( dirname( __FILE__ ) . '/inc/pp-private-front.php' );
}
add_action( 'bp_include', 'pp_private_load_admin' );
