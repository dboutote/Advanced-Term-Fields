( function( $ ) {
	
    'use strict';

    $( '.editinline' ).on( 'click', function() {
        var tag_id = $( this ).parents( 'tr' ).attr( 'id' ),
			icon  = $( 'td.icon i', '#' + tag_id ).attr( 'data-icon' );

        $( ':input[name="term-icon"]', '.inline-edit-row' ).val( icon );
    } );
} )( jQuery );