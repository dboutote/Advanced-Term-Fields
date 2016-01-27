<?php

/**
 * Term Icon Class
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
 * Adds icons for taxonomy terms
 *
 * @version 1.0.0
 *
 * @since 0.1.0
 *
 */
class WP_Term_Toolbox_Icons extends WP_Term_Toolbox {

	public $version = '0.1.0';

	public $db_version = '2015.24.1315';

	public $meta_key = 'term_icon';

	public $data_type = 'icon';


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
			'singular'    => esc_html__( 'Icon',  'wp-term-toolbox' ),
			'plural'      => esc_html__( 'Icons', 'wp-term-toolbox' ),
			'description' => esc_html__( 'Select an icon to represent this term.', 'wp-term-toolbox' )
		);
	}


	/**
	 * Keep here
	 */
	public function format_column_output($meta_value)
	{
		$output = sprintf(
			'<i data-%1$s="%2$s" class="term-%1$s dashicons %2$s"></i>',
			$this->data_type,
			esc_attr( $meta_value )
			);

		return $output;
	}


	/**
	 * Keep here
	 */
	public function enqueue_admin_scripts( $hook )
	{
		wp_enqueue_style( 'dashicons-picker', $this->url . 'css/dashicons-picker.css', array( 'dashicons' ), '1.0', false );

		wp_enqueue_script( 'dashicons-picker', $this->url . 'js/dashicons-picker.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'wp-tt-icons', $this->url . 'js/icons.js', array( 'jquery', 'dashicons-picker' ), '', true );

		wp_localize_script( 'wp-tt-icons', 'i10n_WPTTIcons', array(
			'custom_column_name' => esc_html__( $this->custom_column_name ),
			'meta_key'      => esc_html__( $this->meta_key ),
			'data_type'     => esc_html__( $this->data_type ),
		) );

	}


	/**
	 * Keep here
	 */
	public function admin_head_styles()
	{
		ob_start();
		include dirname( $this->file ) . '/css/admin-head-icon.php';
		$css = ob_get_contents();
		ob_end_clean();

		echo $css;
	}


	/**
	 * Keep here
	 */
	public function add_form_field()
	{
		ob_start();
		include dirname( $this->file ) . '/views/add-form-field-icons.php';
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
		include dirname( $this->file ) . '/views/edit-form-field-icons.php';
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
		include dirname( $this->file ) . '/views/quick-form-field-icons.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}

}