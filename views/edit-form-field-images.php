<tr class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">

	<th scope="row" valign="top">
		<label for="<?php echo esc_attr( $this->meta_key ); ?>">
			<?php esc_html_e( $this->labels['singular'] ); ?>
		</label>
	</th>

	<td>
		<div id="termimagediv">
		
			<?php $thumbnail_id = $this->get_meta( $term->term_id ); ?>
		
			<div class="inside">
				<input type="hidden" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" value="<?php echo $this->get_meta( $term->term_id ); ?>" size="20" />
				<a title="<?php echo esc_attr_e('Set Featured Image');?>" href="#" id="set-term-thumbnail-add" data-update="<?php echo esc_attr_e('Set Featured Image');?>" data-choose="<?php echo esc_attr_e('Featured Image');?>" data-delete="<?php echo esc_attr_e('Remove featured image');?>" class="set-term-thumbnail">
					<?php if ( '' !== $thumbnail_id ) : ?>
						<?php 
						$image_attributes = wp_get_attachment_image_src( $thumbnail_id );

						if( $image_attributes ) : 

							$output = sprintf(
								'<img data-%1$s="%2$s" data-id="%2$s" class="term-%1$s" src="%3$s" width="%4$s" height="%5$s" />',
								$this->data_type,
								esc_attr( $thumbnail_id ),
								esc_attr( $image_attributes[0] ),				
								esc_attr ($image_attributes[1] ),
								esc_attr ($image_attributes[2] )
							);
							
							echo $output;
							
						endif; ?>
						
					<?php else : ?>
						<?php _e( 'Set Featured Image', 'wp-term-toolbox' ); ?>
					<?php endif; ?>
				</a>	
				
				<?php if( '' !== $thumbnail_id ) : ?>
					<a title="<?php echo esc_attr_e('Remove Featured Image');?>" href="#" id="del-term-thumbnail-edit" class="del-term-thumbnail">
							<?php _e( 'Remove Featured Image', 'wp-term-toolbox' ); ?>
					</a>
				<?php endif; ?>
				
			</div>
			
		<?php if ( ! empty( $this->labels['description'] ) ) : ?>
			<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
		<?php endif; ?>			
			
		</div><!-- /#termimagediv -->
		
	</td>
	
</tr>