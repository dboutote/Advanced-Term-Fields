<?php

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

	public $meta_key = 'term_icon';

	public $data_type = 'icon';

	public $db_version = 201601010001;
			
	public function __construct( $file = '' )
	{
		parent::__construct( $file );		
	}


	public function init()
	{
		$this->hook_into_terms($this->taxonomies);
		$this->register_meta();
		$this->load_admin_functions();
		$this->process_term_meta();		

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
			'custom_column' => esc_html__( $this->custom_column ),
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
		include dirname( $this->file ) . '/css/icon-admin-head.php';
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
	
	
	public function edit_form_field( $term = false )
	{
		ob_start();
		include dirname( $this->file ) . '/views/edit-form-field-icons.php';
		$field = ob_get_contents();
		ob_end_clean();
		
		echo $field;
	}

	


public function quick_edit_form_field( $column_name = '', $screen = '', $name = '' ) {

	if ( ( $this->custom_column !== $column_name ) || ( 'edit-tags' !== $screen ) || ! in_array( $name, $this->taxonomies ) ) {
		return false;
	} ?>

	<fieldset>
		<div class="inline-edit-col">
			<label>
				<span class="title"><?php esc_html_e( 'Icon', 'wp-term-icons' ); ?></span>
				<span class="input-text-wrap">
					<input id="inline-<?php echo esc_attr( $this->meta_key ); ?>" type="text" class="ptitle" name="<?php echo esc_attr( $this->meta_key ); ?>" value="" size="20" />
					<input type="button" data-target="#inline-<?php echo esc_attr( $this->meta_key ); ?>" class="button dashicons-picker" value="<?php esc_html_e( 'Choose Icon', 'wp-term-toolbox' ); ?>" />

				</span>
			</label>
		</div>
	</fieldset>

	<?php
}

}