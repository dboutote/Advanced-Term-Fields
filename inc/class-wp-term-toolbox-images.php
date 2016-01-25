<?php

// No direct access
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Adds featured images for taxonomy terms
 *
 * @version 1.0.0
 * 
 * @since 0.1.0
 *
 */
final class WP_Term_Toolbox_Images extends WP_Term_Toolbox {

	public $version = '0.1.0';
	
	public $meta_key = 'thumbnail_id';
	
	public $data_type = 'featimage';

	public $db_version = 201601010001;
	
	public function __construct( $file = '' )
	{
		parent::__construct( $file );
	}
	
	public function init()
	{	
		#$this->hook_into_terms($this->taxonomies);
		#$this->register_meta();
		
		#$this->load_admin_functions();

		#$this->process_term_meta();
	
	}
	
	public function set_labels()
	{
		$this->labels = array(
			'singular'    => esc_html__( 'Featured Image',  'wp-term-toolbox' ),
			'plural'      => esc_html__( 'Featured Images', 'wp-term-toolbox' ),
			'description' => esc_html__( 'Set featured image.', 'wp-term-toolbox' )
		);
	}
	
}