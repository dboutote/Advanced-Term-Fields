<div class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">

	<label for="<?php echo esc_attr( $this->meta_key ); ?>">
		<?php esc_html_e( $this->labels['singular'] ); ?>
	</label>



	<div>
		<input type="hidden" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" value="" />
	</div>

	<a class="button-secondary wp-term-images-media">
		<?php esc_html_e( 'Set Featured Image', 'wp-term-toolbox' ); ?>
	</a>	
	
	<?php if ( ! empty( $this->labels['description'] ) ) : ?>
		<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
	<?php endif; ?>

</div>