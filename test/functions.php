<?php
/**
 * Utility function for debugging output
 *
 */


function test_wp_term_toolbox_taxonomies($taxonomies){	
	return $taxonomies;
}
//add_filter('wp_term_toolbox_taxonomies','test_wp_term_toolbox_taxonomies');


function wp_term_term_image_get_taxonomies_args($args){	
	
	$args['public'] = true;
	$args['_builtin'] = false;
	
	return $args;
}
//add_filter('wp_term_toolbox_term_icon_get_taxonomies_args','wp_term_term_image_get_taxonomies_args');




function sanitize_term_meta_term_icon( $meta_value, $meta_key ){	
	return $meta_value;
}

//add_filter( "sanitize_term_meta_term_icon", 'sanitize_term_meta_term_icon', 10, 2 );



function wp_tt_allowed_orderby_keys( $keys, $meta_key ){
	$keys[] = $meta_key . '_test';
	return $keys;
}
#add_filter( "wp_tt_allowed_orderby_keys", 'wp_tt_allowed_orderby_keys', 10, 2 );



function tax_bork($args){
	$args['_builtin'] = false;
	return $args;
}
#add_filter( "advanced_term_fields_get_taxonomies_args", 'tax_bork' );

function tax_img_bork($args){
	$args['_builtin'] = true;
	return $args;
}
#add_filter( "advanced_term_fields_thumbnail_id_get_taxonomies_args", 'tax_img_bork');