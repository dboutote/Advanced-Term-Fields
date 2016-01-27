<?php

/**
 * Term Toolbox Class
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
 * @since 0.1.0
 *
 * @version 0.1.0
 *
 */

// No direct access
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Main WP Term Toolbox Class
 *
 * @version 1.0.0
 *
 * @since 0.1.0
 */
abstract class WP_Term_Toolbox {

	protected $version = '0.0.0';

	protected $db_version = '2015.20.2020';

	protected $db_version_key = '';

	protected $meta_key = '';

	protected $no_meta_value = '&#8212;';

	protected $custom_column_name = '';

	protected $data_type = '';

	protected $taxonomies = array();

	protected $file = '';

	protected $url = '';

	protected $path = '';

	protected $basename = '';

	protected $labels = array(
		'singular'    => '',
		'plural'      => '',
		'description' => ''
	);

	protected $show_custom_column = true;

	protected $show_fields = true;

	private $required_props = array(
		'meta_key',
		'data_type',
		'labels',
		);

	/**
	 * Used for filtering queries
	 *
	 * @var string $meta_type Custom field type. Can be any value accepted by WP_Meta_Query 
	 *
	 * @see WP_Meta_Query wp-includes/class-wp-meta-query.php
	 */
	protected $meta_type = '';

	protected $allowed_orderby_keys = array();


	abstract protected function set_labels();
	#abstract protected function set_meta_key();
	#abstract protected function set_data_type();


	public function __construct( $file = '' )
	{		
		
		$this->file           = $file;
		$this->url            = plugin_dir_url( $this->file );
		$this->path           = plugin_dir_path( $this->file );
		$this->basename       = plugin_basename( $this->file );

		$this->set_labels();
		
		$this->allowed_orderby_keys = $this->get_allowed_orderby_keys();
		$this->custom_column_name   = $this->get_custom_column_name();
		$this->taxonomies           = $this->get_taxonomies();
		$this->db_version_key       = $this->get_db_version_key();
		
		// check to make sure everything is set
		$this->check_required_props();
		
	}


	public function get_allowed_orderby_keys()
	{
		$keys = array(
			$this->meta_key,
			'meta_value',
			'meta_value_num'
			);
		return apply_filters ( 'wp_tt_allowed_orderby_keys', $keys, $this->meta_key );
	}


	private function check_required_props()
	{
		foreach ( $this->required_props  as $prop ) {
			$this->check_required( $prop );
		}

	}

	private function check_required( $prop )
	{
		// clean arrays, check for empty values
		if ( is_array( $this->$prop ) ) {
			$this->$prop = array_filter( $this->$prop );
		};

		if( empty( $this->$prop ) ){

			$output = sprintf(
				'No value set for %1$s::$%2$s',
				get_class($this),
				$prop
				);
			throw new Exception( $output );
		}
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


	public function show_custom_column( $taxonomies = array() )
	{
		if( ! $this->show_custom_column ) {
			return;
		}

		if ( ! empty( $taxonomies ) ) :
			foreach ( $taxonomies as $tax_name ) {
				add_filter( "manage_edit-{$tax_name}_columns", array( $this, 'add_column_header' ) );
				add_filter( "manage_{$tax_name}_custom_column", array( $this, 'add_column_value' ), 10, 3 );
				add_filter( "manage_edit-{$tax_name}_sortable_columns", array( $this, 'sortable_columns' ) );
			}
		endif;

		return $taxonomies;
	}


	public function show_custom_fields( $taxonomies = array() )
	{
		if( ! $this->show_fields ) {
			return;
		}

		if ( ! empty( $taxonomies ) ) :
			foreach ( $taxonomies as $tax_name ) {
				add_action( "{$tax_name}_add_form_fields", array( $this, 'add_form_field' ) );
				add_action( "{$tax_name}_edit_form_fields", array( $this, 'edit_form_field' ) );
			}
		endif;

		return $taxonomies;
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
			$return_value = $this->format_column_output( $meta_value );
		}

		echo $return_value;
	}


	public function format_column_output( $meta_value ){}


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
		return "wp_term_toolbox_{$this->data_type}s_version";
	}


	public function get_taxonomies( $args = array(), $meta_key = '' )
	{
		return WP_Term_Toolbox_Utils::get_taxonomies( $args = array(), $this->meta_key );
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


	public function maybe_upgrade_database() {
		$stored_version = get_option( $this->db_version_key );

		if ( version_compare( $stored_version, $this->db_version, '<' ) ) {
			$this->upgrade_database( $stored_version, $this->db_version );
		}
	}


	public function upgrade_database( $stored_version = 0, $db_version  ) {
		update_option( $this->db_version_key, $this->db_version );
	}


	public function upgrade_check() {
		$this->maybe_upgrade_database();
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
		add_filter( 'terms_clauses', array($this, 'filter_terms_clauses'), 10, 3 );
		add_filter( 'get_terms_args', array($this, 'filter_terms_table_args'), 10, 2 );
	}



	/**
	 * Filter the terms query SQL clauses.
	 *
	 * @see 'terms_clauses' filter in get_terms() wp-includes/taxonomy.php
	 *
	 * @since 0.1.0
	 *
	 * @todo add filter for $allowed_orderby_keys
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

		// Bail if there's no meta query
		if( empty( $args['meta_query'] ) ) {
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
	function filter_terms_table_args( $args, $taxonomies )
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