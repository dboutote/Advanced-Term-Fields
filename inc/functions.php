<?php

/**
 * Displays upgrade notice
 *
 * @since 0.1.1
 *
 * @param bool   $updated        True|False flag for option being updated.
 * @param string $db_version_key The database key for the plugin version.
 * @param string $plugin_version The most recent plugin version.
 * @param string $db_version     The plugin version stored in the database pre upgrade.
 * @param string $meta_key       The meta field key.
 *
 * @return void
 */
function _atf_core_version_upgraded( $updated, $db_version_key, $plugin_version, $db_version, $meta_key ){

	if( $updated ) {
		
		$display_msg = sprintf(
			'<div class="updated notice is-dismissible"><p><b>%1$s</b> has been upgraded to version <b>%2$s</b></p></div>',
			__( Adv_Term_Fields_Utils::$plugin_name, 'adv-term-fields' ),
			$plugin_version
		);
		
		add_action('admin_notices', function() use ( $display_msg ) {
			echo $display_msg;
		});
		
	}
	
}
add_action( "atf_core_version_upgraded", '_atf_core_version_upgraded', 10, 5 );