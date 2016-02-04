<?php
/**
 * Add form view
 *
 * Displays the wrapper for the form field for adding terms.
 *
 * @uses 'do_action' "adv_term_fields_show_inner_field_add_{$this->meta_key}" filter for 
 * inheriting classes to output fields.
 *
 * @package Advanced_Term_Fields
 * @subpackage Views
 *
 * @since 0.1.0
 */
?>
<div class="form-field term-<?php echo esc_attr( $this->meta_key ); ?>-wrap" id="term-<?php echo esc_attr( $this->meta_key ); ?>-div">

	<label for="<?php echo esc_attr( $this->meta_key ); ?>">
		<?php esc_html_e( $this->labels['singular'] ); ?>
	</label>

	<?php wp_nonce_field( $this->basename, "{$this->meta_key}_nonce"); ?>

	<?php do_action( "adv_term_fields_show_inner_field_add_{$this->meta_key}", $taxonomy ); ?>

	<?php if ( ! empty( $this->labels['description'] ) ) : ?>
		<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
	<?php endif; ?>

</div>