<?php

function test_wp_term_toolbox_taxonomies($taxonomies){	
	return $taxonomies;
}
add_filter('wp_term_toolbox_taxonomies','test_wp_term_toolbox_taxonomies');