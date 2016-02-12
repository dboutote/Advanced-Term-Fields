<?php

/**
 * Advanced Term Fields Class
 *
 * This class establishes the base functionality for adding custom meta fields to taxonomy terms.
 * Provides methods for the following:
 * - Adding columns to list tables
 * - Adding fields to add/edit/quick-edit forms
 * - Sanitizing meta values
 *
 * Can be extended by other plugins or functions either through child classes or the provided
 * filters/action hooks.
 *
 * @package Advanced_Term_Fields
 *
 * @since 0.1.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Advanced_Term_Fields Class
 *
 * @version 0.1.1 Added @var $meta_slug for HTML/CSS classes.
 * @version 0.1.0 
 *
 * @since 0.1.0
 */
abstract class Advanced_Term_Fields
{

	/**
	 * Version number
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $version = '0.0.0';


	/**
	 * Database key
	 *
	 * For storing version number.
	 *
	 * @see Advanced_Term_Fields::get_db_version_key()
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $db_version_key = '';


	/**
	 * Metadata database key
	 *
	 * For storing/retrieving the meta value.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_key = '';


	/**
	 * Singular slug for meta key
	 *
	 * Used for:
	 * - localizing js files
	 * - form field views
	 *
	 * @see Advanced_Term_Fields::enqueue_admin_scripts()
	 * @see Advanced_Term_Fields\Views\(add|edit|qedit).php
	 *
	 * @since 0.1.1
	 *
	 * @var string
	 */
	protected $meta_slug = '';


	/**
	 * Default value to display in the terms list table
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $no_meta_value = '&#8212;';


	/**
	 * Name of column in the terms list table
	 *
	 * @see Advanced_Term_Fields::get_custom_column_name()
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $custom_column_name = '';



	/**
	 * Unique singular descriptor for meta type
	 *
	 * (e.g.) "icon", "color", "thumbnail", "image", "lock".
	 *
	 * Used in localizing js files.
	 *
	 * @see Advanced_Term_Fields::enqueue_admin_scripts()
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $data_type = '';


	/**
	 * Authorized taxonomies
	 *
	 * The taxonomies to hook into.
	 *
	 * @see Advanced_Term_Fields::get_taxonomies()
	 * @see Adv_Term_Fields_Utils::get_taxonomies()
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $allowed_taxonomies = array();


	/**
	 * Full file path to plugin file
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $file = '';


	/**
	 * URL to plugin
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $url = '';


	/**
	 * Filesystem directory path to plugin
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $path = '';


	/**
	 * Base name for plugin
	 *
	 * e.g. "advanced-term-fields/advanced-term-fields.php"
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $basename = '';


	/**
	 * Labels for form fields
	 *
	 * Used to build form fields.  Also used to output column header on the terms list table
	 *
	 * @see Advanced_Term_Fields::set_labels()
	 * @see Advanced_Term_Fields::add_column_header();
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $labels = array(
		'singular'	=> '',
		'plural'	  => '',
		'description' => ''
		);


	/**
	 * Flag to display custom column
	 *
	 * Determines whether or not to show the meta value in a custom column on the terms list table.
	 *
	 * @see Advanced_Term_Fields::show_custom_column()
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $show_custom_column = true;


	/**
	 * Flag to display custom column
	 *
	 * Determines whether or not to create custom fields for this meta value.
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $show_custom_fields = true;


	/**
	 * Class properties required to be set
	 *
	 * @see Advanced_Term_Fields::check_required_props()
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $_required_props = array(
		'meta_key',
		'meta_slug',
		'data_type',
		'labels',
		);


	/**
	 * Custom field type
	 *
	 * Used for filtering queries by meta value. Can be any value accepted by WP_Meta_Query.
	 * e.g. 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL'
	 *
	 * @see Advanced_Term_Fields::filter_terms_clauses()
	 * @see WP_Meta_Query wp-includes/class-wp-meta-query.php
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $meta_value_type = '';


	/**
	 * Allowed ORDERBY keys
	 *
	 * Used for filtering queries by meta value.
	 *
	 * @see Advanced_Term_Fields::get_allowed_orderby_keys()
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $allowed_orderby_keys = array();


	/**
	 * Constructor
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $file Full file path to calling plugin file
	 */
	public function __construct( $file = '' )
	{
		$this->file	    = $file;
		$this->url	    = plugin_dir_url( $this->file );
		$this->path	    = plugin_dir_path( $this->file );
		$this->basename = plugin_basename( $this->file );

		$this->set_labels();

		$this->allowed_orderby_keys = $this->get_allowed_orderby_keys();
		$this->custom_column_name	= $this->get_custom_column_name();
		$this->allowed_taxonomies	= $this->get_taxonomies();
		$this->db_version_key		= $this->get_db_version_key();

		// check to make sure everything is set
		$this->_check_required_props();
	}


	/**
	 * Checks required properties for Class
	 *
	 * @see Advanced_Term_Fields::$_required_props
	 *
	 * @uses Advanced_Term_Fields::_check_required()
	 *
	 * @access private
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function _check_required_props()
	{
		foreach ( $this->_required_props  as $prop ) {
			try {
				$this->_check_required( $prop );
			} catch (Exception $e) {

				// exception message
				$e_msg = $e->getMessage();

				// message referencing parent plugin
				$parent_msg = '<b>' . Adv_Term_Fields_Utils::$plugin_name . '</b> requires all inheriting classes set this property.';

				// Which child plugin is causing the issue?
				$child_plugin_file = $this->file;
				$child_plugin_data = get_file_data( $child_plugin_file, array( 'Name' => 'Plugin Name' ), 'plugin' );
				$child_plugin_name = ( ! empty($child_plugin_data['Name']) ) ? esc_html( $child_plugin_data['Name'] ) : '';
				$child_msg = 'Unable to activate <b>' . $child_plugin_name . '</b>.';

				// displayed message
				$display_msg = sprintf(
					'<div class="error"><p><b>Error:</b> %1$s %2$s %3$s</p></div>',
					$e_msg,
					$parent_msg,
					$child_msg
					);

				// deactivate the child plugin
				add_action('admin_init', function() use ( $child_plugin_file ) {
					deactivate_plugins( plugin_basename( $child_plugin_file ) );
				});

				// let the user know
				add_action('admin_notices', function() use ( $display_msg ) {
					echo $display_msg;
				});
			}
		}
	}


	/**
	 * Checks if a required class property is set
	 *
	 * Filters/trims property before checking
	 *
	 * @see Advanced_Term_Fields::$_required_props
	 * @see Advanced_Term_Fields::_check_required_props()
	 *
	 * @access private
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $prop The class property to check.
	 *
	 * @throws Exception if the property is not set.
	 *
	 * @return mixed bool|Exception
	 */
	private function _check_required( $prop )
	{
		$cleaned_prop = ( is_array( $this->$prop ) ) ? array_filter( $this->$prop ) : trim( $this->$prop );

		if( empty( $cleaned_prop ) || is_null( $cleaned_prop ) ){
			$output = sprintf(
				'No value set for <code>%1$s::$%2$s</code>',
				get_class($this),
				$prop
				);
			throw new Exception( $output );
		}

		return true;
	}


	/**
	 * Sets labels for form fields
	 *
	 * Requires child classes to set labels.
	 *
	 * @access protected
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	abstract protected function set_labels();


	/**
	 * Retrieve allowed ORDERBY keys
	 *
	 * Applies 'advanced_term_fields_allowed_orderby_keys' filter.
	 *
	 * @see Advanced_Term_Fields::filter_terms_clauses()
	 * @see Advanced_Term_Fields::filter_terms_args()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return array $keys Filtered array of allowed ORDERBY keys
	 */
	public function get_allowed_orderby_keys()
	{
		$keys = array(
			$this->meta_key,
			'meta_value',
			'meta_value_num'
			);

		return apply_filters ( 'advanced_term_fields_allowed_orderby_keys', $keys, $this->meta_key );
	}


	/**
	 * Registers term meta, key, and callbacks
	 *
	 * If the meta is protected, the key needs to be prefaced with an underscore before registering.
	 * This will ensure WP recognizes it as protected.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_meta
	 *
	 * @uses WordPress\Meta register_meta()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_meta()
	{
		register_meta(
			'term',
			$this->meta_key,
			array( $this, 'sanitize_callback' ),
			array( $this, 'auth_callback' )
		);
	}


	/**
	 * Sanitizes meta key value
	 *
	 * A function or method to call when sanitizing the value of a meta key.
	 * Used with "sanitize_{$meta_type}_meta_{$meta_key}" filter
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_meta
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $data The meta value being stored.
	 *
	 * @return mixed $data The sanitized meta value.
	 */
	public function sanitize_callback( $data = '' )
	{
		return $data;
	}


	/**
	 * Checks capability for meta process
	 *
	 * A function or method to call when performing edit_post_meta,
	 * add_post_meta, and delete_post_meta capability checks.
	 * Used with "auth_{$meta_type}_meta_{$meta_key}" filter.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_meta
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param bool   $allowed  Is the user allowed to make the change.
	 * @param string $meta_key The meta key.
	 * @param int	 $post_id  The post ojbect ID.
	 * @param int	 $user_id  The user ID.
	 * @param string $cap	   The meta capability.
	 * @param array  $caps	   An array of capabilities.
	 *
	 * @return boolean True if allowed to view the meta field by default, false if else.
	 */
	public function auth_callback( $allowed = false, $meta_key = '', $post_id = 0, $user_id = 0, $cap = '', $caps = array() )
	{
		if ( $meta_key !== $this->meta_key ) {
			return $allowed;
		}
		return $allowed;
	}


	/**
	 * Displays meta column in the terms list table
	 *
	 * Called by inheriting classes on init() to display column in the terms list table.
	 * Hooks onto:
	 * - "manage_{$this->screen->taxonomy}_columns" filter
	 * - "manage_{$this->screen->taxonomy}_custom_column" filter
	 * - "manage_{$this->screen->taxonomy}_sortable_columns" filter
	 *
	 * @see WP_Terms_List_Table::column_default()
	 * @see get_column_headers() in wp-admin/includes/template.php
	 * @see get_column_info() in wp-admin/includes/class-wp-list-table.php
	 *
	 * @uses Advanced_Term_Fields::$show_custom_column to check if column should be shown.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param array $allowed_taxonomies The taxonomies to display the column on.
	 *
	 * @return array $allowed_taxonomies The allowed taxonomies.
	 */
	public function show_custom_column( $allowed_taxonomies = array() )
	{
		if( ! $this->show_custom_column ) {
			return;
		}

		if ( ! empty( $allowed_taxonomies ) ) :
			foreach ( $allowed_taxonomies as $tax_name ) {
				add_filter( "manage_edit-{$tax_name}_columns", array( $this, 'add_column_header' ) );
				add_filter( "manage_{$tax_name}_custom_column", array( $this, 'add_column_value' ), 10, 3 );
				add_filter( "manage_edit-{$tax_name}_sortable_columns", array( $this, 'sortable_columns' ) );
			}
		endif;

		return $allowed_taxonomies;
	}


	/**
	 * Adds custom column to list table column array
	 *
	 * @see Advanced_Term_Fields::show_custom_column()
	 *
	 * @uses Advanced_Term_Fields::$custom_column_name
	 * @uses Advanced_Term_Fields::$labels
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param array $columns An array of column names to be displayed.
	 *
	 * @return array $columns The column names of the terms list table.
	 */
	public function add_column_header( $columns = array() )
	{
		$columns[ $this->custom_column_name ] = $this->labels['singular'];

		return $columns;
	}


	/**
	 * Displays custom meta value in the terms list table
	 *
	 * @see Advanced_Term_Fields::show_custom_column()
	 *
	 * @uses Advanced_Term_Fields::$custom_column_name
	 * @uses Advanced_Term_Fields::custom_column_output()
	 * @uses Advanced_Term_Fields::$no_meta_value
	 * @uses Advanced_Term_Fields::get_meta()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $empty	      Blank string.
	 * @param string $column_name Name of the column.
	 * @param int	 $term_id	  Term ID.
	 *
	 * @return array $columns The column names.
	 */
	public function add_column_value( $empty = '', $column_name = '', $term_id = 0 )
	{

		if ( empty( $_REQUEST['taxonomy'] ) || ( $this->custom_column_name !== $column_name ) || ! empty( $empty ) ) {
			return;
		}

		$return_value = $this->no_meta_value;
		$meta_value = $this->get_meta( $term_id );

		if ( ! empty( $meta_value ) ) {
			$return_value = $this->custom_column_output( $meta_value );
		}

		echo $return_value;
	}


	/**
	 * Displays meta value in custom column
	 *
	 * Called by inheriting classes on init() to display meta value in column in terms list table.
	 *
	 * @see Advanced_Term_Fields::add_column_value()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $meta_value The stored meta value to be displayed.
	 *
	 * @return string $var The displayed meta value.
	 */
	public function custom_column_output( $meta_value ) {}


	/**
	 * Sets custom column sortable
	 *
	 * @see Advanced_Term_Fields::show_custom_column()
	 * @see wp-admin/includes/class-wp-list-table.php
	 *
	 * @uses Advanced_Term_Fields::$custom_column_name
	 * @uses Advanced_Term_Fields::$meta_key
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param array $sortable_columns An array of sortable columns.
	 *
	 * @return array $columns The column names.
	 */
	public function sortable_columns( $columns = array() )
	{
		$columns[ $this->custom_column_name ] = $this->meta_key;

		return $columns;
	}


	/**
	 * Displays form fields on term admin pages
	 *
	 * Called by inheriting classes on init() to display form fields.
	 *
	 * @see wp-admin/edit-tags.php
	 * @see wp-admin/edit-tag-form.php
	 *
	 * @uses Advanced_Term_Fields::$show_custom_fields To check if field should be shown.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param array $allowed_taxonomies The taxonomies to display the field on.
	 *
	 * @return null
	 */
	public function show_custom_fields( $allowed_taxonomies = array() )
	{
		if( ! $this->show_custom_fields ) {
			return;
		}

		if ( ! empty( $allowed_taxonomies ) ) :
			foreach ( $allowed_taxonomies as $tax_name ) {
				add_action( "{$tax_name}_add_form_fields", array( $this, 'add_form_field' ) );
				add_action( "{$tax_name}_edit_form_fields", array( $this, 'edit_form_field'), 10, 2 );
				add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_form_field' ), 10, 3 );
			}
		endif;

		return;
	}


	/**
	 * Displays inner form fields on term admin pages
	 *
	 * Called by inheriting classes on init() to display form fields inside form field wrappers.
	 *
	 * @see Advanced_Term_Fields::add_form_field()
	 * @see Advanced_Term_Fields::edit_form_field()
	 * @see Advanced_Term_Fields::quick_edit_form_field()
	 *
	 * @uses Advanced_Term_Fields::$show_custom_fields To check if field should be shown.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function show_inner_fields()
	{
		if( ! $this->show_custom_fields ) {
			return;
		}

		add_action( "adv_term_fields_show_inner_field_add_{$this->meta_key}", array($this, 'show_inner_field_add') );
		add_action( "adv_term_fields_show_inner_field_edit_{$this->meta_key}", array($this, 'show_inner_field_edit'), 10, 2 );
		add_action( "adv_term_fields_show_inner_field_qedit_{$this->meta_key}", array($this, 'show_inner_field_qedit'), 10, 3 );
	}


	/**
	 * Displays wrapper for form fields on Add Term form
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 *
	 * @uses Advanced_Term_Fields::$basename  For nonce generation.
	 * @uses Advanced_Term_Fields::$meta_key  For nonce generation.
	 * @uses Advanced_Term_Fields::$file      To include view.
	 * @uses WordPress wp_nonce_field()       To build nonce for form field.
	 * @uses Advanced_Term_Fields::$meta_slug To populate CSS IDs, classes.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function add_form_field( $taxonomy )
	{
		ob_start();
		include dirname( ADV_TERM_FIELDS_FILE ) . '/views/add-form-field.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}


	/**
	 * Displays inner form field on Add Term form
	 *
	 * Called by inheriting classes on {$ClassName}->init() to display form fields inside form
	 * field wrappers.
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 * @see Advanced_Term_Fields::add_form_field()
	 *
	 * @uses Advanced_Term_Fields::$file To include view.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function show_inner_field_add( $taxonomy = '' ){}


	/**
	 * Displays wrapper for form fields on Edit Term form
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 *
	 * @uses Advanced_Term_Fields::$basename  For nonce generation.
	 * @uses Advanced_Term_Fields::$meta_key  For nonce generation.
	 * @uses Advanced_Term_Fields::$file      To include view.
	 * @uses WordPress wp_nonce_field()       To build nonce for form field.
	 * @uses Advanced_Term_Fields::$meta_slug To populate CSS IDs, classes.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param object $term Term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function edit_form_field( $term, $taxonomy )
	{
		ob_start();
		include dirname( ADV_TERM_FIELDS_FILE ) . '/views/edit-form-field.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}


	/**
	 * Displays inner form field on Edit Term form
	 *
	 * Called by inheriting classes on {$ClassName}->init() to display form fields inside form
	 * field wrappers.
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 * @see Advanced_Term_Fields::edit_form_field()
	 *
	 * @uses Advanced_Term_Fields::$file To include view.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param object $term Term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function show_inner_field_edit( $term = false, $taxonomy = '' ){}


	/**
	 * Displays wrapper for form fields on Quick Edit Term form
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 *
	 * @uses Advanced_Term_Fields::$custom_column_name
	 * @uses Advanced_Term_Fields::$basename  For nonce generation.
	 * @uses Advanced_Term_Fields::$meta_key  For nonce generation.
	 * @uses Advanced_Term_Fields::$file      To include view.
	 * @uses WordPress wp_nonce_field()       To build nonce for form field.
	 * @uses Advanced_Term_Fields::$meta_slug To populate CSS IDs, classes.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $column_name Name of the column to edit.
	 * @param string $screen	  The screen name.
	 * @param string $taxonomy	  Current taxonomy slug.
	 *
	 * @return void
	 */
	public function quick_edit_form_field( $column_name, $screen, $taxonomy )
	{
		if( $this->custom_column_name !== $column_name ) {
			return;
		}

		ob_start();
		include dirname( ADV_TERM_FIELDS_FILE ) . '/views/quick-form-field.php';
		$field = ob_get_contents();
		ob_end_clean();

		echo $field;
	}


	/**
	 * Displays inner form field on Quick Edit Term form
	 *
	 * Called by inheriting classes on {$ClassName}->init() to display form fields inside form
	 * field wrappers.
	 *
	 * @see Advanced_Term_Fields::show_custom_fields()
	 * @see Advanced_Term_Fields::quick_edit_form_field()
	 *
	 * @uses Advanced_Term_Fields::$file To include view.
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param string $column_name Name of the column to edit.
	 * @param string $screen	  The screen name.
	 * @param string $taxonomy	  Current taxonomy slug.
	 *
	 * @return void
	 */
	public function show_inner_field_qedit( $column_name = '' , $screen = '' , $taxonomy = '' ){}


	/**
	 * Retrieves the stored value
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_term_meta/
	 *
	 * @uses WordPress get_term_meta()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param int $term_id Term ID.
	 */
	public function get_meta( $term_id = 0 )
	{
		return get_term_meta( $term_id, $this->meta_key, true );
	}


	/**
	 * Returns the database key for the meta key version
	 *
	 * @uses Advanced_Term_Fields::$meta_key
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return string Version key.
	 */
	public function get_db_version_key()
	{
		return "atf_{$this->meta_key}_version";
	}


	/**
	 * Returns taxonomies used meta key
	 *
	 * @see https://codex.wordpress.org/Function_Reference/get_taxonomies
	 *
	 * @uses WordPress wp_parse_args()
	 * @uses WordPress get_taxonomies()
	 *
	 * @since 0.1.0
	 *
	 * @return array $allowed_taxonomies Filtered array of taxonomies.
	 */
	public function get_taxonomies()
	{
		$defaults = apply_filters( "advanced_term_fields_get_taxonomies_args", array( 'show_ui' => true ), $this->meta_key);
		$defaults = apply_filters( "advanced_term_fields_{$this->meta_key}_get_taxonomies_args", $defaults, $this->meta_key);

		$allowed_taxonomies = get_taxonomies( $defaults );

		return apply_filters('advanced_term_fields_allowed_taxonomies', $allowed_taxonomies, $this->meta_key);
	}


	/**
	 * Builds column name for meta field
	 *
	 * Note: Relying on $meta_key/$meta_slug alone throws an error with 'dashicons-picker' script.
	 *
	 * @uses Advanced_Term_Fields::$meta_slug
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return string Column name.
	 */
	public function get_custom_column_name()
	{
		return 'col-' . $this->meta_slug;
	}


	/**
	 * Loads various admin functions
	 *
	 * - Loads js/css scripts
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function load_admin_functions()
	{
		add_action( 'load-edit-tags.php', array( $this, 'load_admin_scripts'  ) );
	}


	/**
	 * Loads js/css admin scripts
	 *
	 * Note: Only loads js/css on edit-tags.php
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function load_admin_scripts()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_styles' ) );
	}


	/**
	 * Loads js admin scripts
	 *
	 * Note: Only loads on edit-tags.php
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts( $hook ){}


	/**
	 * Prints out css styles in admin head
	 *
	 * Note: Only loads on edit-tags.php
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function admin_head_styles(){}


	/**
	 * Loads term-managing methods
	 *
	 * - fires on 'create_term' filter
	 * - fires on 'edit_term' filter
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function process_term_meta()
	{
		add_action( 'create_term', array( $this, 'save_term_meta' ), 10, 2 );
		add_action( 'edit_term',   array( $this, 'save_term_meta' ), 10, 2 );
	}


	/**
	 * Checks meta value before processing
	 *
	 * Checks for nonce.
	 *
	 * @uses Advanced_Term_Fields::set_term_meta()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param int	 $term_id  Term ID.
	 * @param string $taxonomy The taxonomy for the term.
	 *
	 * @return void|null If nonce isn't set.
	 */
	public function save_term_meta( $term_id = 0, $taxonomy = '' )
	{
		if ( ! isset( $_POST["{$this->meta_key}_nonce"] ) || ! wp_verify_nonce( $_POST["{$this->meta_key}_nonce"], $this->basename ) ) {
			return;
		}

		$meta_value = ( ! empty( $_POST[$this->meta_key] ) ) ? $_POST[$this->meta_key] : '' ;

		$this->set_term_meta( $term_id, $taxonomy, $meta_value );
	}


	/**
	 * Updates meta value
	 *
	 * Note: If the key is private, an underscore "_" will be appended to the key before storing
	 *
	 * @see https://developer.wordpress.org/reference/functions/delete_term_meta/
	 * @see https://developer.wordpress.org/reference/functions/update_term_meta/
	 * @see https://developer.wordpress.org/reference/functions/clean_term_cache/
	 *
	 * @uses WordPress delete_term_meta()
	 * @uses WordPress update_term_meta()
	 * @uses WordPress clean_term_cache()
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @param int	 $term_id	  Term ID.
	 * @param string $taxonomy	  The taxonomy for the term.
	 * @param mixed  $meta_value  The value to be updated.
	 * @param bool   $clean_cache Whether to clean the term cache.
	 *
	 * @return void|null If nonce isn't set.
	 */
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
	 * Loads term query methods
	 *
	 * - fires on 'get_terms_args' filter
	 * - fires on 'terms_clauses' filter
	 *
	 * @access public
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function filter_terms_query()
	{
		add_filter( 'get_terms_args', array($this, 'filter_terms_args'), 10, 2 );
		add_filter( 'terms_clauses', array($this, 'filter_terms_clauses'), 10, 3 );
	}


	/**
	 * Filter the terms query SQL clauses.
	 *
	 * @see 'terms_clauses' filter in get_terms() wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 *
	 * @param array $pieces	    Terms query SQL clauses.
	 * @param array $taxonomies An array of taxonomies.
	 * @param array $args	    An array of terms query arguments.
	 *
	 * @return array $pieces The filtered SQL clauses
	 */
	public function filter_terms_clauses( $pieces = array(), $taxonomies = array(), $args = array() )
	{
		global $wpdb;

		// Bail if there's no meta query
		if( empty( $args['meta_query'] ) ) {
			return $pieces ;
		}

		// Bail if the meta_key in the args doesn't match the meta key for this term meta
		if( isset( $args['meta_key'] ) && ! empty( $args['meta_key'] ) ) {
			if( $this->meta_key !== $args['meta_key'] ) {
				return $pieces;
			}
		}

		 // If we're not ordering by any of the allowed keys, return
		$orderby = ( ! empty( $args['orderby'] ) ) ? $args['orderby'] : '' ;
		if ( ! in_array( $orderby, $this->allowed_orderby_keys, true ) ) {
			return $pieces ;
		}

		/**
		 * Someone could set meta_type in get_terms() at a later point if Core adopts meta querying
		 * for terms like post types.
		 */
		$meta_value_type = (  isset( $args['meta_type'] ) && ! empty( $args['meta_type'] )  ) ? esc_sql( $args['meta_type'] ) : $this->meta_value_type;

		switch ( $args[ 'orderby' ] ) {
			case $this->meta_key :
			case 'meta_value' :
				if ( ! empty( $meta_value_type ) ) {
					$pieces ['orderby'] = "ORDER BY CAST({$wpdb->termmeta}.meta_value AS {$meta_value_type})";
				} else {
					$pieces ['orderby'] = "ORDER BY {$wpdb->termmeta}.meta_value";
				}
				break;
			case 'meta_value_num':
				$pieces ['orderby'] = "ORDER BY {$wpdb->termmeta}.meta_value+0";
				break;
		}

		return $pieces ;
	}


	/**
	 * Filter terms listing in the terms list table table on edit-tags.php
	 *
	 * Adds the meta_query argument which tells WP to fire a new WP_Meta_Query() instance.
	 * This handles all the custom SQL queries needed to sort by meta value.
	 *
	 * We have to specifically call for terms that have the meta key set and those that don't, or
	 * else WP will only return terms with the meta_key.
	 *
	 * Note: WP_Terms_List_Table checks $_REQUEST['orderby'] and sets $args['orderby'] when
	 * displaying terms in wp-admin/edit-tags.php.
	 *
	 * @see 'get_terms_args' filter in get_terms() wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 *
	 * @param array  $args	     An array of terms query arguments.
	 * @param array  $taxonomies An array of taxonomies.
	 *
	 * @return array $args The filtered terms query arguments.
	 */
	function filter_terms_args( $args, $taxonomies )
	{
		global $pagenow;

		if( ! is_admin() || 'edit-tags.php' !== $pagenow ){
			return $args;
		}

		 // If we're not ordering by any of the allowed keys, return
		$orderby = ( ! empty( $args['orderby'] ) ) ? $args['orderby'] : '' ;
		if ( ! in_array( $orderby, $this->allowed_orderby_keys, true ) ) {
			return $args ;
		}

		// Set the meta query args
		$args['meta_key'] = $this->meta_key;
		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => $this->meta_key,
				'compare' => 'EXISTS'
			),
			array(
				'key'     => $this->meta_key,
				'compare' => 'NOT EXISTS'
			)
		);

		return $args;
	}


}