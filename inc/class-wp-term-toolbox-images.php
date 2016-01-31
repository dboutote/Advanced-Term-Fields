<?php

/**
 * Term Featured Image Class
 *
 * @version 0.1.0
 *
 * @since 0.1.0
 *
 */

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

	public $data_type = 'thumbnail';


	public function __construct( $file = '' )
	{
		parent::__construct( $file );
	}


	public function init()
	{
		$this->show_custom_column( $this->taxonomies );
		$this->show_custom_fields( $this->taxonomies );
		$this->register_meta();
		$this->load_admin_functions();
		$this->process_term_meta();
		$this->filter_terms_query();
	}


	/**
	 * Keep here
	 */
	public function set_labels()
	{
		$this->labels = array(
			'singular'    => esc_html__( 'Image',  'wp-term-toolbox' ),
			'plural'      => esc_html__( 'Images', 'wp-term-toolbox' ),
			'description' => esc_html__( 'Set a featured image for this term.', 'wp-term-toolbox' )
		);
	}


	/**
	 * Keep here
	 */
	public function enqueue_admin_scripts( $hook )
	{
	
		// Enqueue media
		wp_enqueue_media();
		
		wp_enqueue_script( 'wp-tt-images', $this->url . 'js/feat-images.js', array( 'jquery' ), '', true );
		
		// Term ID
		$term_id = ! empty( $_GET['tag_ID'] )
			? (int) $_GET['tag_ID']
			: 0;		

		wp_localize_script( 'wp-tt-images', 'i10n_WPTTImages', array(
			'custom_column_name' => esc_html__( $this->custom_column_name ),
			'meta_key'      => esc_html__( $this->meta_key ),
			'data_type'     => esc_html__( $this->data_type ),
			'insertMediaTitle' => esc_html__( 'Choose an Image', 'wp-term-toolbox' ),
			'insertIntoPost'   => esc_html__( 'Set featured image', 'wp-term-toolbox' ),
			'removeFromPost'   => esc_html__( 'Set featured image', 'wp-term-toolbox' ),
			'term_id'          => $term_id,
			
			
		) );
	}


	/**
	 * Keep here
	 */
	public function admin_head_styles()
	{
		ob_start();
		include dirname( $this->file ) . '/css/admin-head-feat-image.php';
		$css = ob_get_contents();
		ob_end_clean();

		echo $css;
	}

	
	/**
	 * Keep here
	 */
	public function custom_column_output($meta_value)
	{
		$output = '';
			
		$image_attributes = wp_get_attachment_image_src( $meta_value );
		
		if( $image_attributes ) : 
		
			$output = sprintf(
				'<img data-%1$s="%2$s" data-id="%2$s" class="term-%1$s" src="%3$s" width="%4$s" height="%5$s" />',
				$this->data_type,
				esc_attr( $meta_value ),
				esc_attr( $image_attributes[0] ),				
				esc_attr ($image_attributes[1] ),
				esc_attr ($image_attributes[2] )
				);
			
		endif;


		return $output;
	}


	/**
	 * Keep here
	 */
	public function add_form_field()
	{
		ob_start();
		wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
		include dirname( $this->file ) . '/views/add-form-field-images.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}


	/**
	 * Keep here
	 */
	public function edit_form_field( $term = false )
	{
		ob_start();
		wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
		include dirname( $this->file ) . '/views/edit-form-field-images.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}


	/**
	 * Keep here
	 */
	public function quick_edit_form_field( $column_name = '', $screen = '', $name = '' )
	{
		ob_start();
		wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
		include dirname( $this->file ) . '/views/quick-form-field-images.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}

}