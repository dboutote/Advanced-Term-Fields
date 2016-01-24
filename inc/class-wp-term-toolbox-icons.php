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
 * @since 0.1.0
 *
 */
class WP_Term_Toolbox_Icons {

	/**
	 * @var string $version Plugin version
	 *
	 * @since 0.1.0
	 */
	public $version = '0.1.0';


	/**
	 * @var int $db_version Database version
	 *
	 * @since 0.1.0
	 */
	public $db_version = 201601010001;


	/**
	 * @var string $db_version_key Database version option key
	 *
	 * @since 0.1.0
	 */
	public $db_version_key = 'wp_term_toolbox_icons_version';


	/**
	 * @var string $meta_key Metadata key being saved
	 *
	 * @since 0.1.0
	 */
	public $meta_key = 'term_icon';


	/**
	 * @var string $no_meta_value When there's no value set
	 *
	 * @since 0.1.0
	 */
	public $no_meta_value = '&#8212;';


	/**
	 * @var string $custom_column Column name for taxonomy WP_List_Table
	 *
	 * @since 0.1.0
	 */
	protected $custom_column = '';


	/**
	 * @var string $data_type Type of data being stored
	 * 
	 * Needs to be a unique identifier for wp_localize_script
	 *
	 * @since 0.1.0
	 */
	public $data_type = 'icon';


	/**
	 * @var string $file File path to main plugin file
	 *
	 * @since 0.1.0
	 */
	public $file = '';

	/**
	 * @var string $url Url to main plugin dir
	 *
	 * @since 0.1.0
	 */
	public $url = '';

	/**
	 * @var string $path File path to main plugin dir
	 *
	 * @since 0.1.0
	 */
	public $path = '';

	/**
	 * @var string $basename Base name for plugin
	 *
	 * @since 0.1.0
	 */
	public $basename = '';

	/**
	 * @var array $taxnomies Taxonomines used by plugin
	 *
	 * @since 0.1.0
	 */
	public $taxonomies = array();


/**
 * Constructor
 */
public function __construct( $file = '' ) {

	// Setup plugin
	$this->file     = $file;
	$this->url      = plugin_dir_url( $this->file );
	$this->path     = plugin_dir_path( $this->file );
	$this->basename = plugin_basename( $this->file );

	$this->custom_column = $this->get_custom_column_name();

	// Hook into the taxonomies
	$this->taxonomies = WP_Term_Toolbox_Utils::get_taxonomies();
	$this->hook_into_terms($this->taxonomies);

	// Load actions on the edit-tags.php page
	$this->load_edit_tags();

	// Process meta
	$this->process_term_meta();

}


public function load_edit_tags()
{
	add_action( 'load-edit-tags.php', array( $this, 'load_admin_hooks'  ) );
	add_action( 'load-edit-tags.php', array( $this, 'load_admin_scripts'  ) );
}


public function load_admin_hooks()
{
	// Quick edit form field
	add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_form_field' ), 10, 3 );
}


/**
 * Build column name for meta field
 *
 * Note: Relying on $meta_key alone throws an error with 'dashicons-picker' script
 */
public function get_custom_column_name()
{
	return $this->meta_key . '-col';
}


public function process_term_meta()
{
	add_action( 'create_term', array( $this, 'save_term_meta' ), 10, 2 );
	add_action( 'edit_term',   array( $this, 'save_term_meta' ), 10, 2 );
}


public function save_term_meta( $term_id = 0, $taxonomy = '' )
{
	$meta_value = ( ! empty( $_POST[$this->meta_key] ) ) ? $_POST[$this->meta_key] : '' ;
	$this->set_term_meta( $term_id, $taxonomy, $meta_value );
}


public function set_term_meta( $term_id = 0, $taxonomy = '', $meta_value = '', $clean_cache = false )
{
	// if meta value is empty, delete else update
	if ( empty( $meta_value ) ) {
		delete_term_meta( $term_id, $this->meta_key );
	} else {
		update_term_meta( $term_id, $this->meta_key, $meta_value );
	}

	// Maybe clean the term cache
	if ( true === $clean_cache ) {
		clean_term_cache( $term_id, $taxonomy );
	}
}


/**
 * Enqueue Styles and Scripts in the Admin
 *
 * @access public
 * @since 0.1.0
 *
 */
public function load_admin_scripts()
{	
	add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	add_action( 'admin_head', array( $this, 'admin_head_styles' ) );
}

/**
 * Style the term-icon column
 *
 * @since 0.1.0
 */
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



/**
 * Enqueue admin scripts
 *
 * @since 0.1.0
 */
public function enqueue_admin_scripts( $hook )
{

	wp_enqueue_script( 'term-icons-admin', $this->url . 'js/admin.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'dashicons-picker', $this->url . 'js/dashicons-picker.js', array( 'jquery' ), '1.1', true );
	wp_enqueue_style( 'dashicons-picker', $this->url . 'css/dashicons-picker.css', array( 'dashicons' ), '1.0', false );

	// Enqueue fancy icons; includes quick-edit
	wp_enqueue_script(
		'wp-tt-term-icons',
		$this->url . 'js/term-icons.js',
		array( 'dashicons-picker' ),
		'',
		true
	);

	// Localize
	wp_localize_script( 'wp-tt-term-icons', 'i10n_WPTTIcons', array(
		'custom_column' => esc_html__( $this->custom_column ),
		'meta_key'      => esc_html__( $this->meta_key ),
		'data_type'     => esc_html__( $this->data_type ),
	) );

}




public function hook_into_terms( $taxonomies )
{
	if ( ! empty($taxonomies) ) :

		foreach ( $taxonomies as $value ) {
			add_filter( "manage_edit-{$value}_columns",          array( $this, 'add_column_header' ) );
			add_filter( "manage_{$value}_custom_column",         array( $this, 'add_column_value'  ), 10, 3 );
			add_filter( "manage_edit-{$value}_sortable_columns", array( $this, 'sortable_columns'  ) );

			add_action( "{$value}_add_form_fields",  array( $this, 'add_form_field'  ) );
			add_action( "{$value}_edit_form_fields", array( $this, 'edit_form_field' ) );
		}
	endif;
}


public function format_column_output($meta_value)
{

	$output = sprintf(
		'<i data-%1$s="%2$s" class="term-icon dashicons %2$s"></i>',
		$this->data_type,
		esc_attr( $meta_value )
		);

	return $output;
}


public function add_column_header( $columns = array() )
{
	$columns[$this->custom_column] = __( 'Icon', 'term-icon' );

	return $columns;
}


public function add_column_value( $empty = '', $custom_column = '', $term_id = 0 )
{

	if ( empty( $_REQUEST['taxonomy'] ) || ( $this->custom_column !== $custom_column ) || ! empty( $empty ) ) {
		return;
	}

	// Get the meta value
	$meta_value = $this->get_meta( $term_id );
	$return_value = $this->no_meta_value;

	// Output HTML element if not empty
	if ( ! empty( $meta_value ) ) {
		$return_value = $this->format_column_output( $meta_value );
	}

	echo $return_value;
}


public function sortable_columns( $columns = array() ) {
	$columns[$this->meta_key] = $this->meta_key;
	return $columns;
}


/**
 * Return the `meta_key` value of a term
 *
 * @since 0.1.0
 *
 * @param int $term_id
 */
public function get_meta( $term_id = 0 ) {
	return get_term_meta( $term_id, $this->meta_key, true );
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

	// Bail if not the meta_key column on the `edit-tags` screen for a visible taxonomy
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