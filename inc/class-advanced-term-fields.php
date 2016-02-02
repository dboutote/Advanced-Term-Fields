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
 * @version 1.0.0
 *
 * @since 0.1.0
 */
abstract class Advanced_Term_Fields {

	/**
	 * Version number
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $version = '0.1.0';


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
	 * Default value to display in list table
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $no_meta_value = '&#8212;';


	/**
	 * Name of column in list table
	 *
	 * @see Advanced_Term_Fields::get_custom_column_name()
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $custom_column_name = '';


	/**
	 * Unique singular slug for meta type
	 *
	 * Used to create db version key. Also used in localizing js files.
	 *
	 * @see Advanced_Term_Fields::get_db_version_key()
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
	protected $hooked_taxonomies = array();


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
	 * Used to build form fields.  Also used to output column header on list table
	 *
	 * @see Advanced_Term_Fields::set_labels()
	 * @see Advanced_Term_Fields::add_column_header();
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $labels = array(
		'singular'    => '',
		'plural'      => '',
		'description' => ''
	);


	/**
	 * Flag to display custom column
	 *
	 * Determines whether or not to show the meta value in a custom column on list table.
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
	protected $show_fields = true;


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
	protected $meta_type = '';


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
		$this->file           = $file;
		$this->url            = plugin_dir_url( $this->file );
		$this->path           = plugin_dir_path( $this->file );
		$this->basename       = plugin_basename( $this->file );

		$this->set_labels();

		$this->allowed_orderby_keys = $this->get_allowed_orderby_keys();
		$this->custom_column_name   = $this->get_custom_column_name();
		$this->hooked_taxonomies    = $this->get_taxonomies();
		$this->db_version_key       = $this->get_db_version_key();

		// check to make sure everything is set
		$this->_check_required_props();
	}

	
	/**
	 * Checks all required properties
	 *
	 * @see Advanced_Term_Fields::$_required_props
	 *
	 * @uses Advanced_Term_Fields::_check_required()
	 *
	 * @access private
	 *
	 * @since 0.1.0
	 */
	private function _check_required_props()
	{
		foreach ( $this->_required_props  as $prop ) {
			try {
				$this->_check_required( $prop );
			} catch (Exception $e) {
				$msg = $e->getMessage();
				$msg2 = ' property. <b>' . Adv_Term_Fields_Utils::$plugin_name . '</b> requires all sub classes set this field.';
				
				
				$child_plugin = $this->file;
				add_action('admin_init', function() use ( $child_plugin ) {
					deactivate_plugins( plugin_basename( $child_plugin ) );
				});
				add_action('admin_notices', function() use ($msg, $msg2) {
					echo '<div class="error"><p><b>Error:</b> ' , esc_html($msg) , $msg2 , '</p></div>';
				});
			}
		}
	}


	/**
	 * Checks if a required class property is set
	 *
	 * @see Advanced_Term_Fields::$_required_props
	 * @see Advanced_Term_Fields::_check_required_props()
	 *
	 * @access private
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $prop The class property to check
	 * @throws
	 */
	private function _check_required( $prop )
	{
		// clean arrays, check for empty values
		if ( is_array( $this->$prop ) ) {
			$cleaned_prop = array_filter( $this->$prop );
		} else {
			$cleaned_prop = trim( $this->$prop );
		};

		if( empty( $cleaned_prop ) || is_null( $cleaned_prop ) ){
			$output = sprintf(
				'No value set for %1$s::$%2$s',
				get_class($this),
				$prop
				);
			throw new Exception( $output );
		}

		return true;
	}


	/**
	 * Set labels for form fields
	 *
	 * Requires child classes to set labels.
	 *
	 * @access protected
	 *
	 * @since 0.1.0
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
	 * @return array $keys Filtered array of allowed orderby keys
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


	public function register_meta()
	{
		register_meta(
			'term',
			$this->meta_key,
			array( $this, 'sanitize_callback' ),
			array( $this, 'auth_callback' )
		);
	}


	public function sanitize_callback( $data = '' )
	{
		return $data;
	}


	public function auth_callback( $allowed = false, $meta_key = '', $post_id = 0, $user_id = 0, $cap = '', $caps = array() )
	{
		if ( $meta_key !== $this->meta_key ) {
			return $allowed;
		}

		return $allowed;
	}


	public function show_custom_column( $hooked_taxonomies = array() )
	{
		if( ! $this->show_custom_column ) {
			return;
		}

		if ( ! empty( $hooked_taxonomies ) ) :
			foreach ( $hooked_taxonomies as $tax_name ) {
				add_filter( "manage_edit-{$tax_name}_columns", array( $this, 'add_column_header' ) );
				add_filter( "manage_{$tax_name}_custom_column", array( $this, 'add_column_value' ), 10, 3 );
				add_filter( "manage_edit-{$tax_name}_sortable_columns", array( $this, 'sortable_columns' ) );
			}
		endif;

		return $hooked_taxonomies;
	}


	public function show_custom_fields( $hooked_taxonomies = array() )
	{
		if( ! $this->show_fields ) {
			return;
		}

		if ( ! empty( $hooked_taxonomies ) ) :
			foreach ( $hooked_taxonomies as $tax_name ) {
				add_action( "{$tax_name}_add_form_fields", array( $this, 'add_form_field' ) );
				add_action( "{$tax_name}_edit_form_fields", array( $this, 'edit_form_field' ) );
			}
		endif;

		return $hooked_taxonomies;
	}


	public function add_column_header( $columns = array() )
	{
		$columns[$this->custom_column_name] = $this->labels['singular'];

		return $columns;
	}


	public function add_column_value( $empty = '', $custom_column = '', $term_id = 0 )
	{
		if ( empty( $_REQUEST['taxonomy'] ) || ( $this->custom_column_name !== $custom_column ) || ! empty( $empty ) ) {
			return;
		}

		$return_value = $this->no_meta_value;
		$meta_value = $this->get_meta( $term_id );

		if ( ! empty( $meta_value ) ) {
			$return_value = $this->custom_column_output( $meta_value );
		}

		echo $return_value;
	}


	public function custom_column_output( $meta_value ){}


	public function sortable_columns( $columns = array() )
	{
		$columns[$this->custom_column_name] = $this->meta_key;

		return $columns;
	}


	public function add_form_field(){}


	public function edit_form_field(){}


	public function get_meta( $term_id = 0 )
	{
		return get_term_meta( $term_id, $this->meta_key, true );
	}


	public function get_db_version_key()
	{
		return "advanced_term_meta_fields_{$this->data_type}s_version";
	}


	public function get_taxonomies( $args = array(), $meta_key = '' )
	{
		return Adv_Term_Fields_Utils::get_taxonomies( $args = array(), $this->meta_key );
	}


	/**
	 * Build column name for meta field
	 *
	 * Note: Relying on $meta_key alone throws an error with 'dashicons-picker' script
	 */
	public function get_custom_column_name()
	{
		return 'col-' . $this->meta_key;
	}


	public function load_admin_functions()
	{
		add_action( 'admin_init', array( $this, 'upgrade_check' ) );
		add_action( 'load-edit-tags.php', array( $this, 'load_admin_hooks'  ) );
		add_action( 'load-edit-tags.php', array( $this, 'load_admin_scripts'  ) );
	}


	public function maybe_upgrade_version()
	{
		$stored_version = get_option( $this->db_version_key );

		if ( version_compare( $stored_version, $this->version, '<' ) ) {
			update_option( $this->db_version_key, $this->version );
		}
	}


	public function upgrade_check()
	{
		$this->maybe_upgrade_version();
	}


	public function load_admin_hooks()
	{
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_form_field' ), 10, 3 );
	}


	public function quick_edit_form_field(){}


	public function load_admin_scripts()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_styles' ) );
	}


	public function enqueue_admin_scripts( $hook ){}


	public function admin_head_styles(){}


	public function process_term_meta()
	{
		add_action( 'create_term', array( $this, 'save_term_meta' ), 10, 2 );
		add_action( 'edit_term',   array( $this, 'save_term_meta' ), 10, 2 );
	}


	public function save_term_meta( $term_id = 0, $taxonomy = '' )
	{
		if ( ! isset( $_POST["{$this->meta_key}_nonce"] ) || ! wp_verify_nonce( $_POST["{$this->meta_key}_nonce"], $this->basename ) ) {
			return;
		}

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
	 * @param array $pieces     Terms query SQL clauses.
	 * @param array $taxonomies An array of taxonomies.
	 * @param array $args       An array of terms query arguments.
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
		$meta_type = (  isset( $args['meta_type'] ) && ! empty( $args['meta_type'] )  ) ? esc_sql( $args['meta_type'] ) : $this->meta_type;

		switch ( $args[ 'orderby' ] ) {
			case $this->meta_key :
			case 'meta_value' :
				if ( ! empty( $meta_type ) ) {
					$pieces ['orderby'] = "ORDER BY CAST({$wpdb->termmeta}.meta_value AS {$meta_type})";
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
	 * Filter terms listing in WP_Terms_List_Table table on edit-tags.php
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
	 * @param array  $args       An array of terms query arguments.
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
				'key'=>$this->meta_key,
				'compare' => 'EXISTS'
			),
			array(
				'key'=>$this->meta_key,
				'compare' => 'NOT EXISTS'
			)
		);

		return $args;
	}


}