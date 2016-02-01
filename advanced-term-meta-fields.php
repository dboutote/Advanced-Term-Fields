<?php
/**
 * Plugin Name: Advanced Term Meta Fields
 * Plugin URI:  https://darrinb.com/plugins/advanced-term-meta-fields
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
 

if ( ! defined( 'ATMF_FILE' ) ) {
	define( 'ATMF_FILE', __FILE__ );
}


/**
 * Check if we can load/activate
 * 
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-atmf-utils.php';
add_action( 'plugins_loaded', array( 'ATMF_Utils', 'compatibility_check' ) );




// TESTING
include dirname( __FILE__ ) . '/test/sample_taxonomy.php';
include dirname( __FILE__ ) . '/test/functions.php';


/**
 * Include the primary WP_Term_Toolbox_Class
 * 
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-advanced-term-meta-fields.php';


/**
 * Instantiate the main Term Icons Class
 *
 * @since 0.1.0
 */
function _atmf_icons_init(){
	include dirname( __FILE__ ) . '/inc/class-atmf-icons.php';
	$ATMF_Icons = new ATMF_Icons( __FILE__ );
	$ATMF_Icons->init();
}
add_action( 'init', '_atmf_icons_init', 99 );


/**
 * Instantiate the main Term Images Class
 *
 * @since 0.1.0
 */
function _atmf_images_init(){
	include dirname( __FILE__ ) . '/inc/class-atmf-images.php';
	$ATMF_Images = new ATMF_Images( __FILE__ );
	$ATMF_Images->init();
}
add_action( 'init', '_atmf_images_init', 99 );
