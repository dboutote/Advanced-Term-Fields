<?php
/**
 * Quick-edit form view
 *
 * Displays the form field for quick-editing terms.
 *
 * @package Advanced_Term_Fields
 * @subpackage Views
 *
 * @since 0.1.0
 *
 */
?>
<?php
if ( ( $this->custom_column_name !== $column_name ) || ( 'edit-tags' !== $screen ) || ! in_array( $taxonomy, $this->allowed_taxonomies ) ) {
	return false;
};
?>
<fieldset>

	<div class="inline-edit-col">

		<label>

			<span class="title">
				<?php esc_html_e( $this->labels['singular'] ); ?>
			</span>

			<span class="input-text-wrap">

				<?php wp_nonce_field( $this->basename, "{$this->meta_key}_nonce"); ?>

				<?php do_action("adv_term_fields_show_inner_field_qedit_{$this->meta_key}", $column_name, $screen, $taxonomy); ?>

			</span>

		</label>

	</div>

</fieldset>