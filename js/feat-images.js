( function ($) {
    'use strict';

	//i10n_WPTTImages.custom_column_name
	//i10n_WPTTImages.meta_key
	//i10n_WPTTImages.data_type


	function ClearDeleteLink( parent = '' ){
		$('.del-term-thumbnail', parent ).remove();
	}



	/* Globals */
	var featModal,
		term_image_working,
		set_link,
		set_link_parent;

	$( '.set-term-thumbnail' ).on( 'click', function ( e ) {
		set_link = $(this);
		set_link_parent = set_link.closest('.inside');

		e.preventDefault();


		// Already adding
		if ( term_image_working ) {
			return;
		}

		// Open the modal
		if ( featModal ) {
			featModal.open();
			return;
		}

		// Create the media frame.
		featModal = wp.media.frames.featModal = wp.media( {

			// Set the title of the modal.
			title: set_link.data('choose'),
			// Tell the modal to show only images.
			library: { type: 'image' },

			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: set_link.data('update'),
			},

			multiple: false
		} );



		// Picking an image
		featModal.on( 'select', function () {
				
			// remove the existing delete link
			ClearDeleteLink( set_link_parent );

			// Prevent doubles
			term_image_lock( 'lock' );

			// Get the image
			var image = featModal.state().get( 'selection' ).first().toJSON();

			if ( '' !== image ) {

				set_link.html(
					$('<img />').attr({
						'id' : "term-img-" + image.id,
						'src' : image.url,
						'class' : 'term-feat-img',
						'data-term-img' : image.id
						})
				).after(
					$('<a />').attr({
						'href' : '#',
						'class' : 'del-term-thumbnail',
						'data-term-img' : image.id
						})
						.text('Remove featured image')
				);

				// set the meta value
				$( ':input[name="' + i10n_WPTTImages.meta_key + '"]', set_link_parent ).val( image.id );

				if ( ! set_link.hasClass( 'quick' ) ) {
					//$( '#' + i10n_WPTTImages.meta_key ).val( image.id );
					//$( '#wp-term-images-photo' ).attr( 'src', image.url ).show();
					//$( '.wp-term-images-remove' ).show();
				} else {
					$( 'button.wp-term-images-media' ).hide();
					$( 'a.button', '.inline-edit-row' ).show();
					$( ':input[name="term-image"]', '.inline-edit-row' ).val( image.id );
					$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', image.url ).show();
				}
			}

			term_image_lock( 'unlock' );
		} );

		// Open the modal
		featModal.open();
	} );



	$('#termimagediv').on('click', '.del-term-thumbnail', function(e){
		e.preventDefault();
		var parent = $(this).closest('.inside');
		var term_id = $(this).data('term-img');
		var target_img = $('img[data-term-img='+term_id+']', parent );
		var target_img_link = target_img.parent('a');

		$( ':input[name="' + i10n_WPTTImages.meta_key + '"]', parent ).val('');
		$( 'img[data-term-img='+term_id+']', parent ).remove();
		target_img_link.html('Select featured image');

		$(this).remove();
	})
	
$('#the-list').on('click', '.del-term-thumbnail', function (e) {
	e.preventDefault();
	var parent = $(this).closest('.inside');
	var term_id = $(this).data('term-img');
	var target_img = $('img[data-term-img='+term_id+']', parent );
	var target_img_link = target_img.parent('a');

	$( ':input[name="' + i10n_WPTTImages.meta_key + '"]', parent ).val('');
	$( 'img[data-term-img='+term_id+']', parent ).remove();
	target_img_link.html('Select featured image');

	$(this).remove();
});


	/**
	 * Remove image
	 *
	 * @param {object} event The event
	 */
	$( '.wp-term-images-remove' ).on( 'click', function ( event ) {
		event.preventDefault();

		// Clear image metadata
		if ( ! $( this ).hasClass( 'quick' ) ) {
			$( '#term-image' ).val( 0 );
			$( '#wp-term-images-photo' ).attr( 'src', '' ).hide();
			$( '.wp-term-images-remove' ).hide();
		} else {
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'a.button', '.inline-edit-row' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
	} );

	/**
	 * Lock the image fieldset
	 *
	 * @param {boolean} lock_or_unlock
	 */
	function term_image_lock( lock_or_unlock ) {
		if ( lock_or_unlock === 'unlock' ) {
			term_image_working = false;
			$( '.wp-term-images-media' ).prop( 'disabled', false );
		} else {
			term_image_working = true;
			$( '.wp-term-images-media' ).prop( 'disabled', true );
		}
	}

	/**
	 * Quick edit interactions
	 */



    $('#the-list').on('click', '.editinline', function (e) {
        var tr_id = $(this).parents('tr').attr('id'),
			target_img = $('td.' + i10n_WPTTImages.custom_column_name + ' img', '#' + tr_id),
			img_id = '',
			img_src = '';

		// if there's an image
		if( typeof( target_img ) !== 'undefined' && target_img.length > 0 ) {
			img_id = target_img.data('id');
			img_src = target_img.attr('src');
			$( '.set-term-thumbnail', '.inline-edit-row' ).html(
				$('<img />').attr({
					'id' : "term-img-" + img_id,
					'src' : img_src,
					'class' : 'term-feat-img',
					'data-term-img' : img_id
					})
			)
		}

        $(':input[name="' + i10n_WPTTImages.meta_key + '"]', '.inline-edit-row').val(img_id);
    });




    $( '.editinline' ).on( 'click', function() {
        var tag_id = $( this ).parents( 'tr' ).attr( 'id' ),
			image  = $( 'td.image img', '#' + tag_id ).attr( 'src' );

		if ( typeof( image ) !== 'undefined' ) {
			$( 'button.wp-term-images-media' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( image );
			$( 'a.button', '.inline-edit-row' ).show();
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', image ).show();
		} else {
			$( 'a.button', '.inline-edit-row' ).hide();
			$( ':input[name="term-image"]', '.inline-edit-row' ).val( '' );
			$( 'img.wp-term-images-media', '.inline-edit-row' ).attr( 'src', '' ).hide();
			$( 'button.wp-term-images-media' ).show();
		}
    } );

})(jQuery);