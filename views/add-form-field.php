<?php
/**
 * Add form view
 *
 * Displays the form field for adding terms.
 *
 * @package Advanced_Term_Fields
 * @subpackage Views
 *
 * @since 0.1.0
 *
 */
?>
<div class="form-field term-<?php echo esc_attr( $this->meta_key ); ?>-wrap" id="term-<?php echo esc_attr( $this->meta_key ); ?>-div">

	<label for="<?php echo esc_attr( $this->meta_key ); ?>">
		<?php esc_html_e( $this->labels['singular'] ); ?>
	</label>
	
	<?php wp_nonce_field( $this->basename, "{$this->meta_key}_nonce"); ?>

	<?php do_action("adv_term_fields_add_form_field_{$this->meta_key}"); ?>

	<?php if ( ! empty( $this->labels['description'] ) ) : ?>
		<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
	<?php endif; ?>

</div>