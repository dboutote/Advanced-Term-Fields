<div class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap" id="termimagediv">

	<label for="<?php echo esc_attr( $this->meta_key ); ?>">
		<?php esc_html_e( $this->labels['singular'] ); ?>
	</label>
	
	<div class="inside">
		<input type="hidden" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" value="" />
		<a title="<?php echo esc_attr_e('Set Featured Image');?>" href="#" id="set-term-thumbnail-add" data-update="<?php echo esc_attr_e('Set Featured Image');?>" data-choose="<?php echo esc_attr_e('Featured Image');?>" data-delete="<?php echo esc_attr_e('Remove featured image');?>" class="button set-term-thumbnail">
			<?php _e( 'Set Featured Image', 'wp-term-toolbox' ); ?>
		</a>			
	</div>
	
	<?php if ( ! empty( $this->labels['description'] ) ) : ?>
		<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
	<?php endif; ?>

</div>