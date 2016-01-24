<?php
/**
 * Plugin Name: WP Term Toolbox
 * Plugin URI:  
 * Author:      darrinb
 * Author URI:  https://profiles.wordpress.org/dbmartin/
 * Version:     0.1.0
 * Description: Easily manage all your term meta fields for categories, tags, and custom taxonomies
 * License:     GPL v2 or later
 */
 
// No direct access
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
 

if ( ! defined( 'WPTT_FILE' ) ) {
	define( 'WPTT_FILE', __FILE__ );
}


/**
 * Check if we can load/activate
 */
include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox-utils.php';
add_action( 'plugins_loaded', array( 'WP_Term_Toolbox_Utils', 'compatibility_check' ) );

include dirname( __FILE__ ) . '/functions.php';

include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox-icons.php';
new WP_Term_Toolbox_Icons( __FILE__ );