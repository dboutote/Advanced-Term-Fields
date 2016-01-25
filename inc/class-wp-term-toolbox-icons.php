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
		
		$this->load_edit_tags();

		$this->process_term_meta();
		
		
		
		
	}
	
	public function set_labels()
	{
		$this->labels = array(
			'singular'    => esc_html__( 'Icon',  'wp-term-toolbox' ),
			'plural'      => esc_html__( 'Icons', 'wp-term-toolbox' ),
			'description' => esc_html__( 'Select an icon to represent this term.', 'wp-term-toolbox' )
		);
	}
	
	
	public function process_term_meta()
	{
		add_action( 'create_term', array( $this, 'save_term_meta' ), 10, 2 );
		add_action( 'edit_term',   array( $this, 'save_term_meta' ), 10, 2 );
	}


	public function load_edit_tags()
	{
		add_action( 'load-edit-tags.php', array( $this, 'load_admin_hooks'  ) );
		add_action( 'load-edit-tags.php', array( $this, 'load_admin_scripts'  ) );
	}


	public function load_admin_hooks()
	{
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_form_field' ), 10, 3 );
	}


	public function load_admin_scripts()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_styles' ) );
	}


	public function enqueue_admin_scripts( $hook )
	{

		wp_enqueue_script( 'dashicons-picker', $this->url . 'js/dashicons-picker.min.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_style( 'dashicons-picker', $this->url . 'css/dashicons-picker.css', array( 'dashicons' ), '1.0', false );

		wp_enqueue_script(
			'wp-tt-term-icons',
			$this->url . 'js/admin.js',
			array( 'jquery', 'dashicons-picker' ),
			'',
			true
			);

		wp_localize_script( 'wp-tt-term-icons', 'i10n_WPTTIcons', array(
			'custom_column' => esc_html__( $this->custom_column ),
			'meta_key'      => esc_html__( $this->meta_key ),
			'data_type'     => esc_html__( $this->data_type ),
		) );

	}


	public function admin_head_styles()
	{
?>
		<style type="text/css">
			.column-<?php echo $this->custom_column; ?> {
				width: 40px;
			}
			.term-icon {
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				font-family: dashicons;
				font-size: 25px;
				font-weight: normal;
				height: 25px;
				line-height: 25px;
				margin-top: 2px;
				vertical-align: top;
				width: 25px;
			}
		</style>
<?php
	}



	public function save_term_meta( $term_id = 0, $taxonomy = '' )
	{
		$meta_value = ( ! empty( $_POST[$this->meta_key] ) ) ? $_POST[$this->meta_key] : '' ;

		$this->set_term_meta( $term_id, $taxonomy, $meta_value );
	}


	public function set_term_meta( $term_id = 0, $taxonomy = '', $meta_value = '', $clean_cache = false )
	{
		if ( empty( $meta_value ) ) {
			delete_term_meta( $term_id, $this->meta_key );
		} else {
			update_term_meta( $term_id, $this->meta_key, $meta_value );
		}

		if ( true === $clean_cache ) {
			clean_term_cache( $term_id, $taxonomy );
		}
	}




	/**
	 * Keep here
	 */
	public function format_column_output($meta_value)
	{

		$output = sprintf(
			'<i data-%1$s="%2$s" class="term-icon dashicons %2$s"></i>',
			$this->data_type,
			esc_attr( $meta_value )
			);

		return $output;
	}





public function edit_form_field( $term = false ) {
?>
<tr class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">
	<th scope="row" valign="top">
		<label for="<?php echo esc_attr( $this->meta_key ); ?>"><?php _ex( 'Icon', 'term icon' ); ?></label>
	</th>
	<td>
		<input name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" type="text" value="<?php echo $this->get_meta( $term->term_id ); ?>" size="20" />
		<input type="button" data-target="#<?php echo esc_attr( $this->meta_key ); ?>" class="button dashicons-picker" value="<?php esc_html_e( 'Choose Icon', 'wp-term-toolbox' ); ?>" />
		<p class="description">
			<?php esc_html_e( 'Assign terms a custom icon to visually separate them from each-other.', 'wp-term-icons' ); ?>
		</p>
	</td>
</tr>
<?php
}


public function add_form_field() {
?>
	<div class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">
		<label for="<?php echo esc_attr( $this->meta_key ); ?>"><?php _ex( 'Icon', 'term-icon' ); ?></label>
		<input class="regular-text" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" type="text" value="" size="20"  />
		<input type="button" data-target="#<?php echo esc_attr( $this->meta_key ); ?>" class="button dashicons-picker" value="<?php esc_html_e( 'Choose Icon', 'wp-term-toolbox' ); ?>" />
		<p><?php _e('Select an icon to represent this term.'); ?></p>
	</div>
<?php
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