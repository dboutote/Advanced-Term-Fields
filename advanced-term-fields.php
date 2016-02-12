<?php
/**
 * Advanced Term Fields
 *
 * @package Advanced_Term_Fields
 *
 * @license     http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @version     0.1.1
 *
 * Plugin Name: Advanced Term Fields
 * Plugin URI:  http://darrinb.com/plugins/advanced-term-fields
 * Description: A framework for managing custom term meta for categories, tags, and custom taxonomies.
 * Version:     0.1.1
 * Author:      Darrin Boutote
 * Author URI:  http://darrinb.com
 * Text Domain: adv-term-fields
 * Domain Path: /lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * @internal Nobody should be able to overrule the real version number as this can cause serious 
 * issues, so no if ( ! defined() )
 *
 * @since 0.1.1
 */
define( 'ADV_TERM_FIELDS_VERSION', '0.1.1' );


if ( ! defined( 'ADV_TERM_FIELDS_FILE' ) ) {
	define( 'ADV_TERM_FIELDS_FILE', __FILE__ );
}


/**
 * Load Utilities
 *
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-adv-term-fields-utils.php';
include dirname( __FILE__ ) . '/inc/functions.php';


/**
 * Check if we can activate
 *
 * @since 0.1.0
 */
add_action( 'plugins_loaded', array( 'Adv_Term_Fields_Utils', 'compatibility_check' ) );


/**
 * Include the primary Advanced_Term_Fields class
 *
 * @since 0.1.0
 */
include dirname( __FILE__ ) . '/inc/class-advanced-term-fields.php';


/**
 * Check if we need to upgrade
 *
 * @since 0.1.1
 */
add_action( 'admin_init', array( 'Adv_Term_Fields_Utils', 'check_for_update' ) );
