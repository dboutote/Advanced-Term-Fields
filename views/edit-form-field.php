<?php
/**
 * Edit form view
 *
 * Displays the form field for editing terms.
 *
 * @package Advanced_Term_Fields
 * @subpackage Views
 *
 * @since 0.1.0
 *
 */
?>
<tr class="form-field term-<?php echo esc_attr( $this->meta_key ); ?>-wrap">

	<th scope="row">
		<label for="<?php echo esc_attr( $this->meta_key ); ?>">
			<?php esc_html_e( $this->labels['singular'] ); ?>
		</label>
	</th>

	<td>

		<div id="term-<?php echo esc_attr( $this->meta_key ); ?>-div">

			<?php wp_nonce_field( $this->basename, "{$this->meta_key}_nonce"); ?>

			<?php do_action("adv_term_fields_show_inner_field_edit_{$this->meta_key}", $term, $taxonomy ); ?>

			<?php if ( ! empty( $this->labels['description'] ) ) : ?>
				<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
				<?php endif; ?>
			</div>

		</div><!-- /#term-div -->

	</td>

</tr>