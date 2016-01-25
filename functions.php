<?php

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
	var_dump($meta_value);
	var_dump($meta_key);
	wp_die(__FUNCTION__);
}

//add_filter( "sanitize_term_meta_term_icon", 'sanitize_term_meta_term_icon', 10,3 );