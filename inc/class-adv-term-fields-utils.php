<?php

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Group of utility methods for use by Advanced_Term_Fields
 *
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @since 0.1.0
 *
 */
class Adv_Term_Fields_Utils {

	/**
	 * @var string $plugin_name name of the plugin
	 * @static
	 */
	public static $plugin_name = 'Advanced Term Fields';


	/**
	 * @var string $required_version minimum version required for this plugin
	 * @static
	 */
	public static $required_version = '4.4';



	static function compatibility_check()
	{
		if ( ! self::compatible_version() ) {
			add_action( 'admin_init', array(__CLASS__, '_plugin_deactivate') );
			add_action( 'admin_notices', array(__CLASS__, '_plugin_admin_notice') );
		}
	}


	static function _plugin_deactivate() 
	{
		deactivate_plugins( plugin_basename( ADV_TERM_FIELDS_FILE ) );
	}
	

	static function _plugin_admin_notice()
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


	static function compatible_version()
	{
		if ( version_compare( $GLOBALS['wp_version'], self::$required_version, '>=' ) ) {
			return true;
		}

		return false;
	}

}