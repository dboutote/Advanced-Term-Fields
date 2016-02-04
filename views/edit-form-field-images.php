<?php
/**
 * Edit form view
 *
 * Displays the form field for editing terms.
 *
 * @package Advanced_Term_Fields
 * @subpackage Adv_Term_Fields_Images\Views
 *
 * @since 0.1.0
 *
 */
?>

<?php 
$thumbnail_id = $this->get_meta( $term->term_id );  
$btn_class = ( '' !== $thumbnail_id ) ? '' : 'button ';
?>

<div class="inside">

	<input type="hidden" name="<?php echo esc_attr( $this->meta_key ); ?>" id="<?php echo esc_attr( $this->meta_key ); ?>" value="<?php echo $this->get_meta( $term->term_id ); ?>" size="20" />

	<a title="<?php echo esc_attr_e('Set Featured Image');?>" href="#" id="set-term-thumbnail-add" data-update="<?php echo esc_attr_e('Set Featured Image');?>" data-choose="<?php echo esc_attr_e('Featured Image');?>" data-delete="<?php echo esc_attr_e('Remove featured image');?>" class="<?php echo $btn_class;?>set-term-thumbnail">

		<?php  if ( '' !== $thumbnail_id ) : ?>

			<?php $image_attributes = wp_get_attachment_image_src( $thumbnail_id );

			if( $image_attributes ) {

				$output = sprintf(
					'<img data-%1$s="%2$s" data-id="%2$s" class="term-%1$s" src="%3$s" width="%4$s" height="%5$s" />',
					$this->data_type,
					esc_attr( $thumbnail_id ),
					esc_attr( $image_attributes[0] ),
					esc_attr ($image_attributes[1] ),
					esc_attr ($image_attributes[2] )
				);

				echo $output;

			}; ?>

		<?php else : ?>
		
			<?php _e( 'Set Featured Image', 'adv-term-fields' ); ?>
			
		<?php endif; ?>

	</a>

	<?php if( '' !== $thumbnail_id ) : ?>
		<a title="<?php echo esc_attr_e('Remove Featured Image');?>" href="#" id="del-term-thumbnail-edit" class="del-term-thumbnail">
			<?php _e( 'Remove Featured Image', 'adv-term-fields' ); ?>
		</a>
	<?php endif; ?>

</div>