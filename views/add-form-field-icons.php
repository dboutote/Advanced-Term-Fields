<div class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">

	<label for="<?php echo esc_attr( $this->meta_key ); ?>">
		<?php esc_html_e( $this->labels['singular'] ); ?>
	</label>

	<input class="regular-text" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" type="text" value="" size="20"  />

	<input type="button" data-target="#<?php echo esc_attr( $this->meta_key ); ?>" class="button dashicons-picker" value="<?php esc_html_e( 'Choose Icon', 'wp-term-toolbox' ); ?>" />

	<?php if ( ! empty( $this->labels['description'] ) ) : ?>
		<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
	<?php endif; ?>

</div>