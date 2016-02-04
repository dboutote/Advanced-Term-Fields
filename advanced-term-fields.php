<?php
/**
 * Advanced Term Fields
 *
 * @package Advanced_Term_Fields
 *
 * @license     http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @version     0.1.0
 *
 * Plugin Name: Advanced Term Fields
 * Plugin URI:  http://darrinb.com/plugins/advanced-term-fields
 * Description: Easily add/manage custom meta fields for categories, tags, and custom taxonomies.
 * Version:     0.1.0
 * Author:      Darrin Boutote
 * Author URI:  http://darrinb.com
 * Text Domain: adv-term-fields
 * Domain Path: /lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * @attribution Inspired by the WP Term Icons Plugin (https://wordpress.org/plugins/wp-term-icons/)
 *              by John James Jacoby (https://profiles.wordpress.org/johnjamesjacoby/)
 */


// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


if ( ! defined( 'ADV_TERM_FIELDS_FILE' ) ) {
	define( 'ADV_TERM_FIELDS_FILE', __FILE__ );
}


/**
 * Check if we can load/activate
 *
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-adv-term-fields-utils.php';
add_action( 'plugins_loaded', array( 'Adv_Term_Fields_Utils', 'compatibility_check' ) );


/**
 * Include the primary Advanced_Term_Fields class
 *
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-advanced-term-fields.php';
