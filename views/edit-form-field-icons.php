<tr class="form-field <?php echo esc_attr( $this->meta_key ); ?>-wrap">
	
	<th scope="row" valign="top">
		<label for="<?php echo esc_attr( $this->meta_key ); ?>">
			<?php esc_html_e( $this->labels['singular'] ); ?>
		</label>
	</th>
	
	<td>
		<?php 
		$icon = sprintf(
			'<i data-%1$s="%2$s" class="term-%1$s dashicons %2$s"></i>',
			$this->data_type,
			esc_attr( $this->get_meta( $term->term_id ) )
			);	
		?>
		
		<div id="wp-tt-icon-meta-wrap" class="icon-meta-wrap">
			<div class="icon-img">
				<?php echo $icon;?>
			</div>
			<div class="icon-utils">
				<input name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" type="text" value="<?php echo $this->get_meta( $term->term_id ); ?>" size="20" />
				<input type="button" data-target="#<?php echo esc_attr( $this->meta_key ); ?>" class="button dashicons-picker" value="<?php esc_html_e( 'Choose Icon', 'wp-term-toolbox' ); ?> " />
			</div>
			
			<?php if ( ! empty( $this->labels['description'] ) ) : ?>
				<p class="description"><?php esc_html_e( $this->labels['description'] ); ?></p>
			<?php endif; ?>	
		</div>
	
	</td>
	
</tr>