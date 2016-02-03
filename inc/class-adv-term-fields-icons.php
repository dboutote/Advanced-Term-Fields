<?php

/**
 * Adv_Term_Fields_Icons Class
 *
 * Adds icons for taxonomy terms.
 *
 * @package Advanced_Term_Fields
 * @subpackage Adv_Term_Fields_Icons
 *
 * @since 0.1.0
 *
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
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
final class Adv_Term_Fields_Icons extends Advanced_Term_Fields {


    /**
     * Version number
     *
     * @since 0.1.0
     *
     * @var string
     */
    public $version = '0.1.0';


    /**
     * Metadata database key
     *
     * For storing/retrieving the meta value.
     *
     * @since 0.1.0
     *
     * @var string
     */
    public $meta_key = 'term_icon';


    /**
     * Unique singular slug for meta type
     *
     * Used in localizing js files.
     *
     * @see Advanced_Term_Fields::enqueue_admin_scripts()
     *
     * @since 0.1.0
     *
     * @var string
     */
    public $data_type = 'icon';


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
        parent::__construct( $file );
    }


    /**
     * Loads the class
     *
     * @uses Advanced_Term_Fields::show_custom_column()
     * @uses Advanced_Term_Fields::show_custom_fields()
     * @uses Advanced_Term_Fields::register_meta()
     * @uses Advanced_Term_Fields::load_admin_functions()
     * @uses Advanced_Term_Fields::process_term_meta()
     * @uses Advanced_Term_Fields::filter_terms_query()
     * @uses Advanced_Term_Fields::$allowed_taxonomies
     *
     * @access public
     *
     * @since 0.1.0
     */
    public function init()
    {
        $this->show_custom_column( $this->allowed_taxonomies );
        $this->show_custom_fields( $this->allowed_taxonomies );
        $this->register_meta();
        $this->load_admin_functions();
        $this->process_term_meta();
        $this->filter_terms_query();
    }


    /**
     * Sets labels for form fields
     *
     * Requires child classes to set labels.
     *
     * @access public
     *
     * @since 0.1.0
     */
    public function set_labels()
    {
        $this->labels = array(
            'singular'    => esc_html__( 'Icon',  'adv-term-fields' ),
            'plural'      => esc_html__( 'Icons', 'adv-term-fields' ),
            'description' => esc_html__( 'Select an icon to represent this term.', 'adv-term-fields' )
        );
    }


    /**
     * Loads js admin scripts
     *
     * Note: Only loads on edit-tags.php
     *
     * @uses Advanced_Term_Fields::$custom_column_name
     * @uses Advanced_Term_Fields::$meta_key
     * @uses Advanced_Term_Fields::$data_type
     *
     * @access public
     *
     * @since 0.1.0
     *
     * @param string $hook The slug of the currently loaded page.
     *
     * @return void
     */
    public function enqueue_admin_scripts( $hook )
    {
        wp_enqueue_style( 'dashicons-picker', $this->url . 'css/dashicons-picker.css', array( 'dashicons' ), '1.0', false );

        wp_enqueue_script( 'dashicons-picker', $this->url . 'js/dashicons-picker.js', array( 'jquery' ), '1.1', true );
        wp_enqueue_script( 'wp-tt-icons', $this->url . 'js/icons.js', array( 'jquery', 'dashicons-picker' ), '', true );

        wp_localize_script( 'wp-tt-icons', 'l10n_ATF_Icons', array(
            'custom_column_name' => esc_html__( $this->custom_column_name ),
            'meta_key'      => esc_html__( $this->meta_key ),
            'data_type'     => esc_html__( $this->data_type ),
        ) );
    }


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
    public function admin_head_styles()
    {
        ob_start();
        include dirname( $this->file ) . "/css/admin-head-icon.php";
        $css = ob_get_contents();
        ob_end_clean();

        echo $css;
    }


    /**
     * Displays meta value in custom column
     *
     * @see Advanced_Term_Fields::add_column_value()
     *
     * @access protected
     *
     * @since 0.1.0
     *
     * @param string $meta_value The stored meta value to be displayed.
     */
    public function custom_column_output( $meta_value )
    {
        $output = sprintf(
            '<i data-icon="%1$s" class="term-icon dashicons %1$s"></i>',
            esc_attr( $meta_value )
            );

        return $output;
    }


    /**
     * Displays form field on Add Term form
     *
     * @see Advanced_Term_Fields::show_custom_fields()
     *
     * @uses Advanced_Term_Fields::$basename
     * @uses Advanced_Term_Fields::$meta_key
     * @uses Advanced_Term_Fields::$file
     * @uses WordPress wp_nonce_field() To build nonce for form field.
     *
     * @access public
     *
     * @since 0.1.0
     *
     * @param string $taxonomy Current taxonomy slug.
     *
     * @return null
     */
    public function add_form_field( $taxonomy )
    {
        ob_start();
        wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
        include dirname( $this->file ) . '/views/add-form-field-icons.php';
        $field = ob_get_contents();
        ob_end_clean();

        echo $field;
    }


    /**
     * Displays form field on Edit Term form
     *
     * @see Advanced_Term_Fields::show_custom_fields()
     *
     * @uses Advanced_Term_Fields::$basename
     * @uses Advanced_Term_Fields::$meta_key
     * @uses Advanced_Term_Fields::$file
     * @uses WordPress wp_nonce_field() To build nonce for form field.
     *
     * @access public
     *
     * @since 0.1.0
     *
     * @param string $taxonomy Current taxonomy slug.
     *
     * @return null
     */
    public function edit_form_field( $term = false, $taxonomy = '' )
    {
        ob_start();
        wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
        include dirname( $this->file ) . '/views/edit-form-field-icons.php';
        $field = ob_get_contents();
        ob_end_clean();

        echo $field;
    }


    /**
     * Displays form field on Quick Edit Term form
     *
     * @see Advanced_Term_Fields::show_custom_fields()
     *
     * @uses Advanced_Term_Fields::$custom_column_name
     * @uses Advanced_Term_Fields::$basename
     * @uses Advanced_Term_Fields::$meta_key
     * @uses Advanced_Term_Fields::$file
     * @uses WordPress wp_nonce_field() To build nonce for form field.
     *
     * @access public
     *
     * @since 0.1.0
     *
     * @param string $column_name Name of the column to edit.
     * @param string $screen      The screen name.
     * @param string $taxonomy    Current taxonomy slug.
     *
     * @return null
     */
    public function quick_edit_form_field( $column_name = '' , $screen = '' , $taxonomy = '' )
    {
        if( $this->custom_column_name !== $column_name ) {
            return;
        }

        ob_start();
        wp_nonce_field( $this->basename , "{$this->meta_key}_nonce");
        include dirname( $this->file ) . '/views/quick-form-field-icons.php';
        $field = ob_get_contents();
        ob_end_clean();

        echo $field;
    }

}