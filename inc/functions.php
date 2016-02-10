<?php

function atf_version_upgraded( $updated, $plugin_version, $db_version, $db_version_key ){

	if( $updated ) {
		add_action('admin_notices', 'atf_upgrade_notice');
	}

}
add_action( "atf_version_upgraded", 'atf_version_upgraded', 10, 4 );


function atf_upgrade_notice(){

	$db_version_key = Adv_Term_Fields_Utils::get_db_version_key( 'core' );	
	$db_version = get_option( $db_version_key );
	
	// displayed message
	printf(
		'<div class="updated notice is-dismissible"><p><b>%1$s</b> has been upgraded to version <b>%2$s</b></p></div>',
		Adv_Term_Fields_Utils::$plugin_name,
		$db_version
		);
}