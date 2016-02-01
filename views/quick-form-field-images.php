<?php
if ( ( $this->custom_column_name !== $column_name ) || ( 'edit-tags' !== $screen ) || ! in_array( $name, $this->taxonomies ) ) {
	return false;
} ?>

<fieldset>

	<div class="inline-edit-col">
		<label>
			<span class="title">
				<?php esc_html_e( $this->labels['singular'] ); ?>
			</span>
			<span class="input-text-wrap">
				
				
			<div class="inside">
				<input id="inline-<?php echo esc_attr( $this->meta_key ); ?>" type="hidden" class="ptitle" name="<?php echo esc_attr( $this->meta_key ); ?>" value="" size="20" />
				<p>
					<a title="<?php echo esc_attr_e('Set Featured Image');?>" href="#" id="inline-set-term-thumbnail" data-update="<?php echo esc_attr_e('Set Featured Image');?>" data-choose="<?php echo esc_attr_e('Featured Image');?>" data-delete="<?php echo esc_attr_e('Remove featured image');?>" data-target="#inline-<?php echo esc_attr( $this->meta_key ); ?>" class="set-term-thumbnail">
						<?php _e( 'Set Featured Image', 'wp-term-toolbox' ); ?>
					</a>
				</p>
			</div>		
			
			</span>
		</label>
	</div>
	
</fieldset>