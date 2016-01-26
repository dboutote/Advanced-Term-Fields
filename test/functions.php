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


function wp_tt_get_terms_orderby( $orderby, $args, $taxonomies ){
		// Ordering by meta key
		if ( ! empty( $_REQUEST['orderby'] ) && ( 'term_icon' === $_REQUEST['orderby'] ) ) {
			$orderby = 'meta_value';
		}

		return $orderby;
}
//add_filter( 'get_terms_orderby',  'wp_tt_get_terms_orderby', 10, 3 );


function wp_tt_terms_clauses( $clauses = array(), $taxonomies = array(), $args = array() ){
	//echo '<pre>'; print_r($clauses); echo '</pre> ';
	//echo '<pre>'; print_r($args); echo '</pre> ';
	$key_type = '';
	
		global $wpdb;

		// Default allowed keys & primary key
		$allowed_keys = array( 'term_icon' );

		// Set allowed keys
		$allowed_keys[] = 'meta_value';
		$allowed_keys[] = 'meta_value_num';

		// Tweak orderby
		$orderby = isset( $args[ 'orderby' ] )
			? $args[ 'orderby' ]
			: '';

		// Bail if no orderby or allowed_keys
		if ( ! in_array( $orderby, $allowed_keys, true ) ) {
			return $clauses;
		}

		// Join term meta data
		$clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS tm ON t.term_id = tm.term_id";
							  
		$clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS mt1 ON t.term_id = mt1.term_id AND mt1.meta_key = 'term_icon'";
		                  

		// Maybe order by term meta
		switch ( $args[ 'orderby' ] ) {
			case 'term_icon' :
			case 'meta_value' :
				if ( ! empty( $key_type ) ) {
					$clauses['orderby'] = "ORDER BY CAST(tm.meta_value AS tm)";
				} else {
					$clauses['orderby'] = "ORDER BY tm.meta_value";
				}
				$clauses['fields'] = 'tm.*, t.*, tt.*';
				$clauses['where']  .= " AND (
					tm.meta_key = 'term_icon'
					OR
					 mt1.term_id IS NULL 
					) ";
				break;
			case 'meta_value_num':
				$clauses['orderby'] = "ORDER BY tm.meta_value+0";
				$clauses['fields'] .= ', tm.*';
				$clauses['where']  .= " AND tm.meta_key = 'term_icon'";
				break;
		}

		// Return maybe modified clauses
		return $clauses;
	
}

add_filter( 'terms_clauses',  'wp_tt_terms_clauses', 10, 3 );


function wp_tt_get_terms( $terms, $taxonomies, $args ){
	echo '<pre>';
	print_r($terms);
	echo '</pre>';
	return $terms;
}
//add_filter( 'get_terms',  'wp_tt_get_terms', 10, 3 );
