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
	return $meta_value;
}

//add_filter( "sanitize_term_meta_term_icon", 'sanitize_term_meta_term_icon', 10, 2 );



function auth_term_meta_term_icon($val){
	var_dump($val);
	wp_die(__FUNCTION__);
}
//add_filter( "auth_term_meta_term_icon", 'auth_term_meta_term_icon', 10, 6 );