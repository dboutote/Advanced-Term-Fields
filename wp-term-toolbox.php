<?php
/**
 * Plugin Name: WP Term Toolbox
 * Plugin URI:  https://darrinb.com/plugins/wp-term-toolbox
 * Author:      darrinb
 * Author URI:  https://profiles.wordpress.org/dbmartin/
 * Version:     0.1.0
 * Description: Easily manage all your term meta fields for categories, tags, and custom taxonomies.
 * License:     GPL v2 or later
 */
 
/**
 * Based on the WP Term Icons Plugin (https://wordpress.org/plugins/wp-term-icons/) 
 * by John James Jacoby (https://profiles.wordpress.org/johnjamesjacoby/)
 *
 */
 

// No direct access
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
 

if ( ! defined( 'WP_TT_FILE' ) ) {
	define( 'WP_TT_FILE', __FILE__ );
}


/**
 * Check if we can load/activate
 */
include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox-utils.php';
add_action( 'plugins_loaded', array( 'WP_Term_Toolbox_Utils', 'compatibility_check' ) );




// TESTING
include dirname( __FILE__ ) . '/test/sample_taxonomy.php';
include dirname( __FILE__ ) . '/test/functions.php';


/**
 * Include the primary WP_Term_Toolbox_Class
 * 
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox.php';


/**
 * Instantiate the main WP Term Toolbox Icons Class
 *
 * @since 0.1.0
 */
function _wp_tt_icons_init(){
	include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox-icons.php';

	$WP_Term_Toolbox_Icons = new WP_Term_Toolbox_Icons( __FILE__ );
	$WP_Term_Toolbox_Icons->init();
}
add_action( 'init', '_wp_tt_icons_init', 99 );


/**
 * Instantiate the main WP Term Toolbox Images Class
 *
 * @since 0.1.0
 */
function _wp_tt_images_init(){
	include dirname( __FILE__ ) . '/inc/class-wp-term-toolbox-images.php';

	$WP_Term_Toolbox_Images = new WP_Term_Toolbox_Images( __FILE__ );
	$WP_Term_Toolbox_Images->init();
}
add_action( 'init', '_wp_tt_images_init', 99 );
