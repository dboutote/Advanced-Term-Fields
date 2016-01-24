jQuery( document ).ready( function() {
    'use strict';
	
    jQuery( '#the-list' ).on( 'click',  '.editinline', function() {

        var tag_id = jQuery( this ).parents( 'tr' ).attr( 'id' ),
			meta_value  = jQuery( 'td.' + i10n_WPTTIcons.custom_column + ' i', '#' + tag_id ).attr( 'data-' + i10n_WPTTIcons.data_type );
			
        jQuery( ':input[name="' + i10n_WPTTIcons.meta_key + '"]', '.inline-edit-row' ).val( meta_value );
    } );
	
} );
