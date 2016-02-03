<?php

/**
 * Advanced Term Fields Utilities Class
 *
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Advanced_Term_Fields
 *
 * @since 0.1.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Adv_Term_Fields_Utils Class
 *
 * Group of utility methods for use by Advanced_Term_Fields
 *
 * @version 1.0.0
 *
 * @since 0.1.0
 */
class Adv_Term_Fields_Utils {

	/**
	 * Name of plugin
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $plugin_name = 'Advanced Term Fields';


	/**
	 * Minimum version required for this plugin
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $required_version = '4.4';


	/**
	 * Loads compatibility check
	 *
	 * @uses Adv_Term_Fields_Utils::compatible_version()
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	static function compatibility_check()
	{
		if ( ! self::compatible_version() ) {
			add_action( 'admin_init', array(__CLASS__, 'plugin_deactivate') );
			add_action( 'admin_notices', array(__CLASS__, 'plugin_admin_notice') );
		}
	}


	/**
	 * Deactivates plugin
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function plugin_deactivate()
	{
		deactivate_plugins( plugin_basename( ADV_TERM_FIELDS_FILE ) );
	}


	/**
	 * Displays deactivation notice
	 *
	 * @uses Adv_Term_Fields_Utils::$plugin_name
	 * @uses Adv_Term_Fields_Utils::$required_version
	 * 
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function plugin_admin_notice()
	{
		echo '<div class="error"><p>'
			. sprintf(
				__( '%1$s requires WordPress %2$s to function correctly. Unable to activate at this time.', 'wptt' ),
				'<strong>' . esc_html( self::$plugin_name ) . '</strong>',
				'<strong>' . esc_html( self::$required_version ) . '</strong>'
				)
			. '</p></div>';

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	/**
	 * Checks for compatibility with current version of WordPress
	 *
	 * @uses Adv_Term_Fields_Utils::$required_version
	 *
	 * @since 0.1.0
	 *
	 * @return bool True if current version of WP is greater than or equal to required version, 
	 *              false if not.
	 */
	private static function compatible_version()
	{
		if ( version_compare( $GLOBALS['wp_version'], self::$required_version, '>=' ) ) {
			return true;
		}

		return false;
	}

}